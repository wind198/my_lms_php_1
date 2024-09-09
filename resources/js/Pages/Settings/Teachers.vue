<script setup lang="ts">
import { textMap } from "@/constants/text";
import { datetimeFormater } from "@/helper/formatter";
import type { IUser } from "@/types/entities/user.type";
import { cloneDeep, set } from "lodash-es";
import { VDataTable } from "vuetify/components";
import { AppLinkClasses } from "../../constants";

import ServerTableHeadCell from "@/Components/common/ServerTableHeadCell.vue";
import ServerTablePagination from "@/Components/common/ServerTablePagination.vue";
import TableColumnConfigurationButton from "@/Components/common/TableColumnConfigurationButton.vue";
import TableFilterToolbar from "@/Components/common/TableFilterToolbar.vue";
import useColumnConfigurationForTable from "@/hooks/useColumnConfigurationForTable";
import useServerTable from "@/hooks/useServerTable";
import { router } from "@inertiajs/vue3";
import dayjs from "dayjs";
import ListPage from "../../Components/common/ListPage.vue";
import { concatClasses, removeValueFromObject } from "../../helper";
import type { IServerTableParams } from "../../types/common/server-table.type";
import { computed, ref, toRefs } from "vue";
import useResource from "@/hooks/useResource";
import TableBulkActionToolbar from "@/Components/common/TableBulkActionToolbar.vue";
type IProps = {
    data: IUser[];
    params: IServerTableParams<IUser>;
};

const props = defineProps<IProps>();

const { toggleVisibity, visibleHeaders, headerWithVisibility } =
    useColumnConfigurationForTable({
        headers: [
            {
                title: textMap.nouns.fullName,
                value: "full_name",
                sortable: true,
                align: "start",
            },
            {
                title: textMap.nouns.created_at,
                value: "created_at",
                sortable: true,
                align: "start",
            },
            {
                title: textMap.nouns.email,
                value: "email",
                sortable: true,
                align: "start",
            },
            {
                title: textMap.nouns.phone,
                value: "phone",
                sortable: true,
                align: "start",
            },
        ],
        intialShow: ["full_name", "email", "phone"],
    });

const { data, params } = toRefs(props);

const {
    handleChangeFilter,
    handleChangeOrder,
    handleChangePage,
    handleChangePerPage,
} = useServerTable({
    data: data,
    headers: visibleHeaders,
    params: params as any,
});

const onChangeFilter = (v: any, keys: string[]) => {
    let newFilters = cloneDeep(params.value.filters ?? {});

    //  update filter value
    let formatedValue = v;
    if (keys[0] === "created_at" && keys[1] === "gte") {
        if (v) {
            formatedValue = dayjs(v).startOf("day").toISOString();
        }
    } else if (keys[0] === "created_at" && keys[1] === "lte") {
        if (v) {
            formatedValue = dayjs(v).endOf("day").toISOString();
        }
    }
    set(newFilters, keys, formatedValue);

    // clear invalid filter values
    newFilters = removeValueFromObject(
        newFilters,
        (i: any) => i === undefined || (typeof i === "string" && !i.trim())
    );

    handleChangeFilter(newFilters);
};
const createTeacherUrl = window.route("settings.teachers.create");

const onRowClick = (e: Event, { item }: { item: IUser }) => {
    const showUrl = window.route("settings.teachers.show", {
        teacher: item.id,
    });

    router.get(showUrl);
};

const selected = ref<number[]>([]);

const { resourcePlural } = useResource();

const deleteManyUrl = computed(() =>
    window.route(`settings.${resourcePlural}.destroy-many`, {
        ids: selected.value,
    })
);
</script>
<template>
    <ListPage :create-url="createTeacherUrl">
        <VDataTable
            :show-select="true"
            @click:row="onRowClick"
            density="compact"
            :items="data"
            :headers="visibleHeaders as any"
            :hide-default-footer="true"
            :items-per-page="params.per_page"
            v-model="selected"
        >
            <template v-slot:top="{}">
                <TableFilterToolbar
                    :filters="{
                        q: params.filters?.q,
                        created_at: params.filters?.created_at ?? {},
                    }"
                    @update-filter="onChangeFilter($event.value, $event.keys)"
                >
                    <template v-slot:append="">
                        <TableColumnConfigurationButton
                            @toggle-column="toggleVisibity($event)"
                            :headers="headerWithVisibility"
                        ></TableColumnConfigurationButton>
                    </template>
                </TableFilterToolbar>
                <TableBulkActionToolbar
                    @confirm-delete="selected = []"
                    :delete-url="deleteManyUrl"
                    :selected="selected"
                ></TableBulkActionToolbar>
            </template>
            <template  v-slot:'header.data-table-select'="{
                columns,
            }">
                <tr>
                    <template v-for="column in columns" :key="column.key">
                        <ServerTableHeadCell
                            :value="column.key"
                            :sortable="column.sortable"
                            :current-order="params.order"
                            :is-active="params.order_by === column.key"
                            :align="column.align"
                            :title="column.title"
                            @click="handleChangeOrder($event)"
                        ></ServerTableHeadCell>
                    </template></tr
            ></template>
            <template v-slot:item.created_at="{ value }">
                {{ datetimeFormater.standard(value) }}
            </template>
            <template v-slot:item.email="{ value }">
                <a
                    :class="concatClasses(AppLinkClasses)"
                    :href="`mailto:${value}`"
                    >{{ value }}</a
                >
            </template>
            <template v-slot:item.phone="{ value }">
                <a
                    :class="concatClasses(AppLinkClasses)"
                    :href="`tel:${value}`"
                    >{{ value }}</a
                >
            </template>
        </VDataTable>
        <ServerTablePagination
            @update-page="handleChangePage"
            @update-per-page="handleChangePerPage"
            :length="params.last_page"
            :page="params.current_page"
            :per-page="params.per_page"
            :total-items="params.total"
        ></ServerTablePagination>
    </ListPage>
</template>

<script setup lang="ts">
import ColumnsLayoutForDashboard from "@/Layouts/ColumnsLayoutForDashboard.vue";
import SettingsLayout from "@/Layouts/SettingsLayout.vue";
import { textMap } from "@/constants/text";
import { datetimeFormater } from "@/helper/formatter";
import type { IUser } from "@/types/entities/user.type";
import { cloneDeep, set } from "lodash-es";
import { VCheckboxBtn, VDataTable, VIcon } from "vuetify/components";
import { AppLinkClasses, MenuLinkClasses } from "../../constants";

import ServerTableHeadCell from "@/Components/common/ServerTableHeadCell.vue";
import ServerTablePagination from "@/Components/common/ServerTablePagination.vue";
import TableColumnConfigurationButton from "@/Components/common/TableColumnConfigurationButton.vue";
import TableFilterToolbar from "@/Components/common/TableFilterToolbar.vue";
import useColumnConfigurationForTable from "@/hooks/useColumnConfigurationForTable";
import useServerTable from "@/hooks/useServerTable";
import dayjs from "dayjs";
import ListPage from "../../Components/common/ListPage.vue";
import { concatClasses, removeValueFromObject } from "../../helper";
import type { IServerTableParams } from "../../types/common/server-table.type";
import { Link, router } from "@inertiajs/vue3";
import { computed, ref, toRaw, toRef, toRefs, toValue, unref } from "vue";
import TableBulkActionToolbar from "../../Components/common/TableBulkActionToolbar.vue";
import useResource from "../../hooks/useResource";
import useResourceRoutes from "@/hooks/useResourceRoutes";
type IProps = {
    data: IUser[];
    params: IServerTableParams<IUser>;
};

const props = defineProps<IProps>();

const { createRoute, getEditRoute } = useResourceRoutes();

const { toggleVisibity, visibleHeaders, headerWithVisibility } =
    useColumnConfigurationForTable({
        headers: [
            {
                title: textMap.nouns.title,
                value: "title",
                sortable: true,
                align: "start",
            },
            {
                title: textMap.nouns.description,
                value: "description",
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
                title: textMap.nouns.action,
                value: "actions",
                sortable: false,
                align: "center",
                width: 40,
            },
        ],
        intialShow: ["title", "description"],
    });

const { data, params } = toRefs(props);

const {
    handleChangeFilter,
    handleChangeOrder,
    handleChangePage,
    handleChangePerPage,
} = useServerTable({
    data: data,
    headers: headerWithVisibility,
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

const onRowClick = (e: Event, { item }: { item: IUser }) => {
    const showUrl = window.route("study.courses.show", {
        course: item.id,
    });

    router.get(showUrl);
};

const onUpdateSortBy = (v: any) => {
    if (!v.length) {
        handleChangeOrder();
        return;
    }
    const { key, order } = v[0];
    handleChangeOrder(key, order);
};
</script>
<template>
    <SettingsLayout>
        <ListPage :create-url="createRoute">
            <VDataTable
                @click:row="onRowClick"
                density="compact"
                :items="data"
                :headers="visibleHeaders as any"
                :hide-default-footer="true"
                :items-per-page="params.per_page"
                @update:sort-by="onUpdateSortBy"
                :sort-by="[{ key: params.order_by, order: params.order }]"
            >
                <template v-slot:top="{}">
                    <TableFilterToolbar
                        :filters="{
                            q: params.filters?.q,
                            created_at: params.filters?.created_at ?? {},
                        }"
                        @update-filter="
                            onChangeFilter($event.value, $event.keys)
                        "
                    >
                        <template v-slot:append="">
                            <TableColumnConfigurationButton
                                @toggle-column="toggleVisibity($event)"
                                :headers="headerWithVisibility"
                            ></TableColumnConfigurationButton>
                        </template>
                    </TableFilterToolbar>
                </template>

                <template v-slot:item.created_at="{ value }">
                    {{ datetimeFormater.standard(value) }}
                </template>
                <template v-slot:item.email="{ value }">
                    <a
                        :class="concatClasses(MenuLinkClasses)"
                        :href="`mailto:${value}`"
                        >{{ value }}</a
                    >
                </template>
                <template v-slot:item.phone="{ value }">
                    <a
                        :class="concatClasses(MenuLinkClasses)"
                        :href="`tel:${value}`"
                        >{{ value }}</a
                    >
                </template>
                <template v-slot:item.actions="{ item }">
                    <div class="d-flex">
                        <Link
                            @click.stop=""
                            :class="[...AppLinkClasses, 'pa-2']"
                            :href="getEditRoute(item.id) ?? ''"
                        >
                            <VIcon>mdi-pencil</VIcon>
                        </Link>
                    </div>
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
    </SettingsLayout>
</template>

<script lang="ts">
export default {
    layout: (h, page) =>
        h(ColumnsLayoutForDashboard, () => h(SettingsLayout, () => page)),
};
</script>
<script setup lang="ts">
import ColumnsLayoutForDashboard from "@/Layouts/ColumnsLayoutForDashboard.vue";
import SettingsLayout from "@/Layouts/SettingsLayout.vue";
import { textMap } from "@/constants/text";
import { datetimeFormater } from "@/helper/formatter";
import type { IUser } from "@/types/entities/user.type";
import { cloneDeep, set } from "lodash-es";
import { VDataTable } from "vuetify/components";
import { AppLinkClasses } from "../../constants";

import PageTitle from "@/Components/common/PageTitle.vue";
import ServerTableHeadCell from "@/Components/common/ServerTableHeadCell.vue";
import ServerTablePagination from "@/Components/common/ServerTablePagination.vue";
import TableFilterToolbar from "@/Components/common/TableFilterToolbar.vue";
import useServerTable from "@/hooks/useServerTable";
import { usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { concatClasses, removeValueFromObject } from "../../helper";
import type { IServerTableParams } from "../../types/common/server-table.type";
type IProps = {
    data: IUser[];
    params: IServerTableParams<IUser>;
};

const props = defineProps<IProps>();

const headers = [
    {
        title: textMap.nouns.title,
        value: "title",
        sortable: true,
        align: "left",
    },
    {
        title: textMap.nouns.created_at,
        value: "created_at",
        sortable: true,
        align: "left",
    },
];

const {
    handleChangeFilter,
    handleChangeOrder,
    handleChangePage,
    handleChangePerPage,
} = useServerTable({
    data: props.data,
    headers,
    params: props.params as any,
});

const onChangeFilter = (v: any, keys: string[]) => {
    let newFilters = cloneDeep(props.params.filters);

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

const createMajorUrl = window.route("study.majors.create");
</script>
<template>
    <div class="major-list">
        <PageTitle
            :title="textMap.nouns.list({ item: textMap.nouns.major })"
            :createUrl="createMajorUrl"
        >
        </PageTitle>
        <VDataTable
            density="compact"
            :items="props.data"
            :headers="headers as any"
            :hide-default-footer="true"
            :items-per-page="props.params.per_page"
        >
            <template v-slot:top="{}">
                <TableFilterToolbar
                    :filters="{
                        q: props.params.filters.q,
                        created_at: props.params.filters.created_at ?? {},
                    }"
                    @update-filter="onChangeFilter($event.value, $event.keys)"
                >
                </TableFilterToolbar>
            </template>
            <template v-slot:headers="{ columns }">
                <tr>
                    <template v-for="column in columns" :key="column.key">
                        <ServerTableHeadCell
                            :value="column.key"
                            :sortable="column.sortable"
                            :current-order="props.params.order"
                            :is-active="props.params.order_by === column.key"
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
            :length="props.params.last_page"
            :page="props.params.current_page"
            :per-page="props.params.per_page"
            :total-items="props.params.total"
        ></ServerTablePagination>
    </div>
</template>

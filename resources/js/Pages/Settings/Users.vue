<script setup lang="ts">
import SettingsLayout from "@/Layouts/SettingsLayout.vue";
import { textMap } from "@/constants/text";
import { datetimeFormater } from "@/helper/formatter";
import type { IUser } from "@/types/entities/user.type";
import { cloneDeep, set } from "lodash-es";
import {
    VBtn,
    VCheckbox,
    VCheckboxBtn,
    VDataTable,
    VIcon,
    VSelect,
} from "vuetify/components";
import {
    AppLinkClasses,
    GenderSelectItems,
    MenuLinkClasses,
} from "../../constants";

import ServerTableHeadCell from "@/Components/common/ServerTableHeadCell.vue";
import ServerTablePagination from "@/Components/common/ServerTablePagination.vue";
import TableColumnConfigurationButton from "@/Components/common/TableColumnConfigurationButton.vue";
import TableFilterToolbar from "@/Components/common/TableFilterToolbar.vue";
import useColumnConfigurationForTable from "@/hooks/useColumnConfigurationForTable";
import useServerTable from "@/hooks/useServerTable";
import { Link, router } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { computed, ref, toRefs } from "vue";
import ListPage from "../../Components/common/ListPage.vue";
import TableBulkActionToolbar from "../../Components/common/TableBulkActionToolbar.vue";
import {
    concatClasses,
    getColumnShownPerResource,
    removeValueFromObject,
} from "../../helper";
import useResource from "../../hooks/useResource";
import type { IServerTableParams } from "../../types/common/server-table.type";
import useResourceRoutes from "../../hooks/useResourceRoutes";
type IProps = {
    data: IUser[];
    params: IServerTableParams<IUser>;
};

const props = defineProps<IProps>();

const { resource } = useResource();

const {
    createRoute,
    getDestroyManyRoute,
    getEditManyRoute,
    getEditRoute,
    getShowRoute,
} = useResourceRoutes();

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
                title: textMap.nouns.gender,
                value: "gender",
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
            {
                title: textMap.nouns.address,
                value: "address",
                sortable: true,
                align: "start",
            },
            {
                title: textMap.nouns.educationBackground,
                value: "education_background",
                sortable: true,
                align: "start",
            },
            ...(resource === "student"
                ? [
                      {
                          title: textMap.nouns.generation,
                          value: "generation_id",
                          sortable: true,
                          align: "start" as const,
                      },
                  ]
                : []),
            {
                title: textMap.nouns.action,
                value: "actions",
                sortable: false,
                align: "center",
                width: "40px",
            },
        ],
        intialShow: getColumnShownPerResource(resource) ?? [
            "full_name",
            "email",
            "phone",
            ...(resource === "student" ? ["generation_id"] : []),
            "actions",
        ],
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

const onUpdateSortBy = (v: any) => {
    if (!v.length) {
        handleChangeOrder();
        return;
    }
    const { key, order } = v[0];
    handleChangeOrder(key, order);
};

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
    const showUrl = getShowRoute(item.id);
    if (!showUrl) return;

    router.get(showUrl);
};

const selected = ref<number[]>([]);

const onClickEditMany = () => {
    const url = getEditManyRoute(selected.value);
    if (!url) return;
    router.get(url);
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
                show-select
                v-model="selected"
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
                        <template
                            v-slot:inner-append="{ setRef, visibilityMap }"
                        >
                            <VSelect
                                :ref="setRef"
                                :items="GenderSelectItems"
                                item-value="id"
                                item-title="title"
                                filterKey="gender"
                                alwaysOn
                                v-show="visibilityMap['gender']"
                                :label="textMap.nouns.gender"
                                :model-value="params.filters?.gender"
                                @update:model-value="
                                    onChangeFilter($event, ['gender'])
                                "
                            ></VSelect>
                        </template>
                    </TableFilterToolbar>
                    <TableBulkActionToolbar
                        @confirm-delete="selected = []"
                        :delete-url="getDestroyManyRoute(selected)"
                        :selected="selected"
                    >
                        <template v-slot:actions-prepend="">
                            <VBtn
                                @click="onClickEditMany"
                                variant="text"
                                color="primary"
                                append-icon="mdi-pencil"
                            >
                                {{ textMap.verbs.update }}
                            </VBtn>
                        </template>
                    </TableBulkActionToolbar>
                </template>

                <!-- <template v-slot:data-table-select="">
                    <VCheckbox></VCheckbox
                ></template> -->
                <!-- <template v-slot:headers="{ columns }">
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
                                :id="`head-cell-${column.key}`"
                                v-if="column.key !== 'data-table-select'"
                            >
                            </ServerTableHeadCell>
                            <th v-else class="px-2">
                                <VCheckboxBtn></VCheckboxBtn>
                            </th>
                        </template></tr
                ></template> -->
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
                <template v-slot:item.created_at="{ value }">
                    {{ datetimeFormater.standard(value) }}
                </template>
                <template v-slot:item.generation_id="{ item }">
                    {{ item.generation?.title }}
                </template>
                <template v-slot:item.gender="{ value }">
                    <!-- @vue-expect-error -->
                    {{ textMap.nouns[value] }}
                </template>
                <template v-slot:item.education_background="{ value }">
                    <!-- @vue-expect-error -->
                    {{ textMap.nouns[value] }}
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

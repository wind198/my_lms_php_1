<script setup lang="ts">
import { textMap } from "@/constants/text";
import TableSearchField from "./TableSearchField.vue";
import AppDateSelectorMenu from "./AppDateSelectorMenu.vue";
import { VBtn, VIcon, VList, VMenu, VSheet, VSwitch } from "vuetify/components";
import { computed, reactive, ref, watch, watchEffect } from "vue";
import { get } from "lodash-es";

type IProps = {
    filters: Record<string, any>; // Keep the current value for each filter in the toolbar
    defaultValues?: Record<string, any>; // Keep the default values for each filter in the toolbar
};

const emit = defineEmits(["update-filter"]);

const props = defineProps<IProps>();

const visibilityStatusList = ref<
    {
        keyStr: string;
        value: boolean;
        label: string;
        alwaysOn?: boolean;
    }[]
>([]);

const shouldHideFilterToggleButton = computed(() =>
    visibilityStatusList.value?.every((i) => i.alwaysOn)
);

const visibilityMap = computed(() =>
    Object.fromEntries(
        visibilityStatusList.value.map(({ keyStr, value }) => [keyStr, value])
    )
);

const setRef = (el) => {
    if (!el) {
        return;
    }
    const key: string = el.filterKey ?? el.attributes?.filterKey?.value;
    const label = el.label ?? el.attributes?.label?.value;
    const alwaysOn = !!el.alwaysOn?.value;
    if (!key) return;
    if (visibilityStatusList.value?.find((i) => i.keyStr === key)) {
        return;
    }
    const hasCurrentValue = get(props.filters, key.split(".")) !== undefined;

    const value = alwaysOn || hasCurrentValue ? true : false;
    visibilityStatusList.value.push({ keyStr: key, value, label, alwaysOn });
};

const toggleFilter = (keyStr: string) => {
    const newVisibilityStatusList = visibilityStatusList.value.map((i) => {
        if (i.alwaysOn) {
            return i;
        }
        if (i.keyStr === keyStr) {
            if (i.value) {
                emit("update-filter", {
                    keys: keyStr.split("."),
                    value: undefined,
                });
            }
            return { ...i, value: !i.value };
        }
        return i;
    });

    visibilityStatusList.value = newVisibilityStatusList;
};

const filterDialogOpen = ref(false);
</script>

<template>
    <div class="table-toolbar d-flex justify-end align-center pa-2">
        <slot name="inner-prepend"></slot>

        <slot>
            <!-- Table search field -->
            <TableSearchField
                always-on
                :ref="setRef"
                v-model="props.filters.q"
                @debounce-change="
                    emit('update-filter', { keys: ['q'], value: $event })
                "
                filterKey="q"
                v-show="visibilityMap['q']"
            ></TableSearchField>
            <!-- Date selector for "created_at.gte" -->
            <AppDateSelectorMenu
                :ref="setRef"
                class="date-selector"
                v-model="(props.filters.created_at || {}).gte"
                :label="textMap.verbs.createAfter"
                @update:model-value="
                    emit('update-filter', {
                        keys: ['created_at', 'gte'],
                        value: $event,
                    })
                "
                v-show="visibilityMap['created_at.gte']"
                filterKey="created_at.gte"
            ></AppDateSelectorMenu>
            <!-- Date selector for "created_at.lte" -->
            <AppDateSelectorMenu
                :ref="setRef"
                class="date-selector"
                v-model="(props.filters.created_at || {}).lte"
                :label="textMap.verbs.createBefore"
                @update:model-value="
                    emit('update-filter', {
                        keys: ['created_at', 'lte'],
                        value: $event,
                    })
                "
                filterKey="created_at.lte"
                v-show="visibilityMap['created_at.lte']"
            ></AppDateSelectorMenu>
        </slot>
        <slot
            :setRef="setRef"
            :visibilityMap="visibilityMap"
            name="inner-append"
        ></slot>

        <!-- VMenu to show/hide additional filters -->
        <VMenu
            v-model="filterDialogOpen"
            :close-on-content-click="false"
            v-if="!shouldHideFilterToggleButton"
        >
            <template v-slot:activator="{ props: activatorProps }">
                <VBtn icon variant="text" v-bind="activatorProps">
                    <VIcon>mdi-filter-settings-outline</VIcon>
                </VBtn>
            </template>
            <VSheet class="filter-menu-sheet">
                <div class="d-flex flex-column pr-3 pl-4 py-2">
                    <p class="text-subtitle-1">
                        {{
                            textMap.nouns.list
                                .concat(" ")
                                .concat(textMap.nouns.filter.toLowerCase())
                        }}
                    </p>
                    <VSwitch
                        density="compact"
                        flat
                        v-for="{
                            keyStr,
                            label,
                            value,
                        } in visibilityStatusList.filter((i) => !i.alwaysOn)"
                        :filterKey="keyStr"
                        :model-value="value"
                        :label="label"
                        @update:model-value="toggleFilter(keyStr)"
                        hide-details="auto"
                    ></VSwitch>
                </div>
            </VSheet>
        </VMenu>
        <slot name="append"></slot>
    </div>
</template>

<style lang="scss" scoped>
.table-toolbar > *:not(style):not(:last-child) {
    margin-right: 8px;
}
.date-selector {
    width: 200px;
}
.filter-menu-sheet {
    min-width: 240px;
}
</style>

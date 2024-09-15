<script setup lang="ts">
import { textMap } from "@/constants/text";
import { VBtn, VIcon, VMenu, VSheet, VSwitch } from "vuetify/components";

type IProps = {
    headers: { visible: boolean; value: string; title: string }[];
};

const props = defineProps<IProps>();

const emit = defineEmits(["toggle-column"]);
</script>
<template>
    <VMenu :close-on-content-click="false">
        <template v-slot:activator="{ props: activatorProps }">
            <VBtn icon v-bind="activatorProps" flat>
                <VIcon>mdi-view-column-outline</VIcon></VBtn
            >
        </template>
        <VSheet class="column-menu-sheet">
            <div class="d-flex flex-column pr-3 pl-4 py-2">
                <p class="text-subtitle-1">
                    {{
                        textMap.nouns.list
                            .concat(" ")
                            .concat(textMap.nouns.column.toLowerCase())
                    }}
                </p>
                <VSwitch
                    density="compact"
                    v-for="{ title: label, value, visible } in props.headers"
                    :model-value="visible"
                    :key="value"
                    :label="label"
                    @update:model-value="emit('toggle-column', value)"
                    hide-details="auto"
                    :id="`column-${value}-toggler`"
                ></VSwitch>
            </div>
        </VSheet>
    </VMenu>
</template>
<style scoped>
.column-menu-sheet {
    min-width: 240px;
}
.column-actions-toggler {
    display: none;
}
</style>

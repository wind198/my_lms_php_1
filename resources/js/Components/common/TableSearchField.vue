<script setup lang="ts">
import { textMap } from "@/constants/text";
import { debounce } from "lodash-es";
import { VTextField } from "vuetify/components";

const props = defineProps<{
    label?: string;
    filterKey?: string;
    alwaysOn?: boolean;
}>();

const modelValue = defineModel();

const emit = defineEmits(["debounce-change"]);

const handleUpdateModelValue = debounce((v: string) => {
    emit("debounce-change", v);
}, 500);

defineExpose({
    filterKey: props.filterKey,
    label: props.label,
    alwaysOn: props.alwaysOn,
});
</script>
<template>
    <VTextField
        :label="props.label ?? textMap.verbs.search"
        :key="props.filterKey"
        clearable
        hide-details="auto"
        density="compact"
        variant="outlined"
        class="search-field flex-grow-0"
        dense
        v-model="modelValue"
        prepend-inner-icon="mdi-magnify"
        @update:model-value="handleUpdateModelValue($event)"
    ></VTextField>
</template>
<style scoped>
.search-field {
    min-width: 240px;
}
</style>

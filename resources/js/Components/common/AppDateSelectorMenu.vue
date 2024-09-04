<script setup lang="ts">
import { ref } from "vue";
import { VDatePicker, VMenu, VTextField } from "vuetify/components";
import { dateFormater } from "../../helper/formatter";
import dayjs from "dayjs";

const props = defineProps<{
    label: string;
    filterKey?: string;
    alwaysOn?: boolean;
}>();

const modelValue = defineModel({
    set(v: any) {
        // return dayjs(v).toDate();
        return v;
    },
    get(v: any) {
        // return dayjs(v).toDate();
        if (!v) {
            return undefined;
        }
        return dayjs(v).toDate();
    },
});

const handleClear = () => {
    modelValue.value = "";
};

defineExpose({
    filterKey: props.filterKey,
    label: props.label,
    alwaysOn: props.alwaysOn,
});
</script>
<template>
    <div class="app-date-selector-menu">
        <VMenu :close-on-content-click="false">
            <template v-slot:activator="{ props: activatorProps }">
                <VTextField
                    clearable
                    :model-value="dateFormater.standard(modelValue)"
                    variant="outlined"
                    density="compact"
                    hide-details="auto"
                    v-bind="activatorProps"
                    :label="props.label"
                    :readonly="true"
                    @click:clear="handleClear"
                >
                </VTextField>
            </template>
            <VDatePicker
                :title="props.label"
                v-model="modelValue"
                hide-header
            ></VDatePicker>
        </VMenu>
    </div>
</template>
<style scoped></style>

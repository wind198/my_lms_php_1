<script setup lang="ts">
import { VDatePicker, VMenu, VTextField } from "vuetify/components";
import { dateFormater } from "../../helper/formatter";
import dayjs from "dayjs";
import { useAttrs } from "vue";

const props = defineProps<{
    label: string;
}>();

defineOptions({ inheritAttrs: false });

const attrs = useAttrs();

const { filterKey, alwaysOn, ...otherAttrs } = attrs;

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
    filterKey: filterKey,
    label: props.label,
    alwaysOn: !!alwaysOn,
});
</script>
<template>
    <div class="app-date-selector-menu" v-bind="otherAttrs">
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

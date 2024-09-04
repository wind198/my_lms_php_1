<script setup lang="ts">
import { ref } from "vue";
import { VForm } from "vuetify/components";
import FormActions from "./FormActions.vue";
import useCreatePage from "../../hooks/useCreatePage";

type IProps = {
    processing?: boolean;
    deleteUrl?: string;
};

const props = defineProps<IProps>();

const { isEdit, recordData } = useCreatePage();

const formRef = ref<VForm | null>(null);

const emit = defineEmits(["submit"]);

const onSubmit = async () => {
    const validateRes = await formRef?.value?.validate();
    if (!validateRes?.valid) return;
    emit("submit");
};
</script>
<template>
    <VForm ref="formRef" @submit.prevent="onSubmit" validate-on="lazy">
        <div class="d-flex flex-column">
            <slot></slot>
        </div>

        <FormActions :delete-url="deleteUrl" :processing="props.processing"></FormActions>
    </VForm>
</template>
<style scoped></style>

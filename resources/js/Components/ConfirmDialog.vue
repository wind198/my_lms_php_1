<script setup lang="ts">
import { textMap } from "@/constants/text";
import useConfirmDialogStore from "@/store/useConfirmDialog";
import { storeToRefs } from "pinia";
import { VBtn, VCard, VDialog } from "vuetify/components";

const confirmDialogStore = useConfirmDialogStore();

const emit = defineEmits();

const onClickConfirm = () => {
    emit("accept-confirm-dialog");
    confirmDialogStore.onConfirm();
    confirmDialogStore.closeDialog();
};
const onClickCancel = () => {
    emit("cancel-confirm-dialog");
    confirmDialogStore.closeDialog();
};

const { content, isOpen, title } = storeToRefs(confirmDialogStore);
</script>
<template>
    <VDialog :model-value="isOpen" width="auto" :close-on-back="true">
        <VCard max-width="400" :text="content" :title="title">
            <template v-slot:actions>
                <VBtn
                    color="primary"
                    class="ms-auto mr-2"
                    :text="textMap.verbs.confirm"
                    @click="onClickConfirm"
                ></VBtn>
                <VBtn
                    :text="textMap.verbs.cancel"
                    @click="onClickCancel"
                ></VBtn>
            </template>
        </VCard>
    </VDialog>
</template>
<style scoped></style>

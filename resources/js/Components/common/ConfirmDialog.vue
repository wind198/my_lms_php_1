<script setup lang="ts">
import { textMap } from "@/constants/text";
import {
    VBtn,
    VCard,
    VCardActions,
    VCardText,
    VDialog,
} from "vuetify/components";

type IProps = {
    disableCloseOnClickAway?: boolean;
    title: string;
};

const props = defineProps<IProps>();

const isOpen = defineModel<boolean>();

const emit = defineEmits(["confirm", "cancel"]);

const onClickConfirm = () => {
    isOpen.value = false;
    emit("confirm");
};
const onClickCancel = () => {
    isOpen.value = false;
    emit("cancel");
};

const onClickAway = () => {
    if (props.disableCloseOnClickAway) {
        return;
    }
    isOpen.value = false;
};
</script>
<template>
    <VDialog max-width="500" v-model="isOpen">
        <VCard :title="props.title">
            <VCardText>
                <slot></slot>
            </VCardText>

            <VCardActions>
                <VBtn flat color="primary" @click="onClickConfirm">{{
                    textMap.verbs.confirm
                }}</VBtn>
                <VBtn flat @click="onClickCancel">{{
                    textMap.verbs.cancel
                }}</VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
</template>
<style scoped></style>

<script setup lang="ts">
import { textMap } from "@/constants/text";
import { VBtn, VCard, VDialog } from "vuetify/components";

type IProps = {
    title: string;
    text: string;
};

const props = defineProps<IProps>();

const isOpen = defineModel<boolean>();

const emit = defineEmits()
 

</script>
<template>
    <VDialog v-model="isOpen" width="auto">
        <VCard max-width="400" :text="text" :title="title">
            <template vslot:actions>
                <VBtn
                    class="ms-auto mr-2"
                    :text="textMap.verbs.confirm"
                    @click="isOpen = false"
                ></VBtn>
                <VBtn
                    :text="textMap.verbs.cancel"
                    @click="isOpen = false"
                ></VBtn>
            </template>
        </VCard>
    </VDialog>
</template>
<style scoped></style>

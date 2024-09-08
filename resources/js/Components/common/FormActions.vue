<script setup lang="ts">
import { textMap } from "@/constants/text";
import useShowPage from "@/hooks/useShowPage";
import useConfirmDialogStore from "@/store/useConfirmDialog";
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { VBtn } from "vuetify/components";

type IProps = {
    processing?: boolean;
    deleteUrl?: string;
};

const props = defineProps<IProps>();

const { openDialog } = useConfirmDialogStore();

const { resource, primaryField, recordData } = useShowPage();

const deleteTitle = computed(() =>
    [textMap.verbs.delete, textMap.nouns[resource]].join(" ")
);

const deleteConfirmMessage = computed(() =>
    textMap.messages.areYourSure({
        action: [
            textMap.verbs.delete,
            textMap.nouns[resource],
            recordData[primaryField],
        ].join(" "),
    })
);

const onClickDeleteUrl = () => {
    if (!props.deleteUrl) {
        return;
    }
    openDialog({
        title: deleteTitle.value,
        content: deleteConfirmMessage.value,
        onConfirm() {
            router.delete(props.deleteUrl!);
        },
    });
};
</script>
<template>
    <div class="actions d-flex align-center justify-end">
        <VBtn
            flat
            color="error"
            type="button"
            v-if="deleteUrl"
            class="mr-2"
            @click="onClickDeleteUrl"
        >
            {{ textMap.verbs.delete }}
        </VBtn>
        <VBtn flat color="primary" type="submit" :loading="props.processing">
            {{ textMap.verbs.save }}
        </VBtn>
    </div>
</template>
<style scoped></style>

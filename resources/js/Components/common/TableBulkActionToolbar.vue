<script setup lang="ts">
import { computed } from "vue";
import useResource from "../../hooks/useResource";
import { textMap } from "../../constants/text";
import { VBtn } from "vuetify/components";
import useConfirmDialogStore from "../../store/useConfirmDialog";
import { router } from "@inertiajs/vue3";

type IProps = {
    deleteUrl?: string;
    selected: number[];
};

const props = defineProps<IProps>();

const { openDialog } = useConfirmDialogStore();

const { resource, resourcePlural } = useResource();

const selectionText = computed(() => {
    return [
        textMap.verbs.selected,
        props.selected.length,
        textMap.nouns[resource],
    ].join(" ");
});

const deleteTitle = computed(() =>
    [textMap.verbs.delete, textMap.nouns[resource]].join(" ")
);

const deleteConfirmMessage = computed(() =>
    textMap.messages.areYourSure({
        action: [
            textMap.verbs.delete,
            props.selected.length,
            textMap.nouns[resource],
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
    <div v-if="selected.length" class="px-4 py-2 d-flex justify-between">
        <span>{{ selectionText }}</span>
        <div class="d-flex actions">
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
        </div>
    </div>
</template>
<style scoped></style>

<script setup lang="ts">
import { computed } from "vue";
import useResource from "../../hooks/useResource";
import { textMap } from "../../constants/text";
import { VBtn, VIcon } from "vuetify/components";
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
            textMap.nouns[resource].toLowerCase(),
        ].join(" "),
    })
);

const emit = defineEmits(["confirm-delete"]);

const onClickDeleteUrl = () => {
    if (!props.deleteUrl) {
        return;
    }
    openDialog({
        title: deleteTitle.value,
        content: deleteConfirmMessage.value,
        onConfirm() {
            router.delete(props.deleteUrl!);
            emit("confirm-delete");
        },
    });
};
</script>
<template>
    <div
        v-if="selected.length"
        class="px-4 py-2 d-flex align-center justify-space-between bg-grey-lighten-2 rounded"
    >
        <span>{{ selectionText }}</span>
        <div class="d-flex actions">
            <slot name="actions-prepend"></slot>
            <slot>
                <VBtn
                    variant="text"
                    color="error"
                    type="button"
                    v-if="deleteUrl"
                    class="mr-2"
                    @click="onClickDeleteUrl"
                    append-icon="mdi-delete"
                >
                    {{ textMap.verbs.delete }}
                </VBtn>
            </slot>
            <slot name="actions-append"></slot>
        </div>
    </div>
</template>
<style scoped></style>

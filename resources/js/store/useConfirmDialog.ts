import { defineStore } from "pinia";

type IConfirmDialogProps = {
    title: string;
    content: string;
    isOpen: boolean;
    onConfirm: () => void;
    onClose?: () => void;
};

const useConfirmDialogStore = defineStore("confirm-dialog", {
    state: (): IConfirmDialogProps => ({
        isOpen: false,
        title: "",
        content: "",
        onConfirm: () => {},
    }),
    actions: {
        openDialog(
            payload: Pick<
                IConfirmDialogProps,
                "content" | "title" | "onConfirm" | "onClose"
            >
        ) {
            const { content, onConfirm, title, onClose } = payload;

            Object.assign(this, {
                isOpen: true,
                title,
                content,
                onConfirm,
                onClose,
            });
        },
        closeDialog() {
            Object.assign(this, {
                isOpen: false,
                title: "",
                content: "",
                onConfirm: () => {},
                onClose: undefined,
            });
        },
    },
});

export default useConfirmDialogStore;

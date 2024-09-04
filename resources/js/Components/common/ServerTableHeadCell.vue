<script setup lang="ts">
import type { IOrder } from "@/types/common/order.type";
import { VIcon } from "vuetify/components";

type IProps = {
    title: string | undefined;
    value: string | null;
    sortable?: boolean;
    align?: string;
    isActive?: boolean;
    currentOrder?: IOrder;
};

const props = defineProps<IProps>();

const emit = defineEmits(["click"]);

const handleClickHeadCell = () => {
    if (!props.sortable) {
        return;
    }
    emit("click", props.value);
};
</script>

<template>
    <th
        @click="handleClickHeadCell"
        :class="
            [
                `head-cell-${props.value}`,
                `text-${props.align ?? 'left'}`,
                props.sortable ? 'cursor-pointer' : '',
            ].join(' ')
        "
    >
        {{ props.title }}
        <VIcon v-if="props.isActive">
            {{
                props.currentOrder === "desc"
                    ? "mdi-arrow-down"
                    : "mdi-arrow-up"
            }}
        </VIcon>
    </th>
</template>
<style scoped></style>

<script setup lang="ts">
import { DefaultTablePerPageItems } from "@/constants";
import { computed } from "vue";
import { VPagination, VSelect } from "vuetify/components";

type IProps = {
    perPage: number;
    page: number;
    totalItems: number;
    length: number;
};

const emit = defineEmits(["update-per-page", "update-page"]);

const props = defineProps<IProps>();

const paginationText = computed(() => {
    const startItemIndex = (props.page - 1) * props.perPage + 1;
    const totalItems = props.totalItems;
    const endItemIndex = Math.min(totalItems, props.page * props.perPage);
    return `${startItemIndex} to ${endItemIndex} of ${totalItems}`;
});
</script>
<template>
    <div class="server-table-pagination d-flex justify-end align-center">
        <span class="mb-0 text-body-2 align-self-center mr-4"
            >{{ paginationText }}
        </span>
        <VSelect
            label="Per page"
            class="flex-grow-0 item-per-page-select"
            v-model="props.perPage"
            @update:model-value="emit('update-per-page', $event)"
            :items="DefaultTablePerPageItems"
        >
        </VSelect>
        <VPagination
            v-model="props.page"
            :length="props.length"
            @update:model-value="emit('update-page', $event)"
            :total-visible="5"
        ></VPagination>
    </div>
</template>
<style scoped>
.item-per-page-select {
    width: 100px;
}
</style>

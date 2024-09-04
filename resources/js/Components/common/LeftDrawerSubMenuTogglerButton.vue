<script setup lang="ts">
import { computed } from "vue";
import { VIcon } from "vuetify/components";

type IProps = {
    isOpen: boolean;
};

const props = defineProps<IProps>();

const iconText = computed(() => {
    if (props.isOpen) return "mdi-chevron-down";
    return "mdi-chevron-up";
});
</script>
<template>
    <button class="sublist-toggler">
        <slot></slot>
        <VIcon class="ml-2">{{ iconText }} </VIcon>
    </button>
</template>
<style scoped>
.sublist-toggler {
    font-weight: 600;
    text-align: left;
    padding: 8px 12px;
    display: flex;
    justify-content: space-between;
}
</style>

<script setup lang="ts">
const props: any = defineProps();
</script>
<template>
    <a v-bind="props">
        <slot></slot>
    </a>
</template>
<style scoped></style>

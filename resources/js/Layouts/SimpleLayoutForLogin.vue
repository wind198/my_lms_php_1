<script setup lang="ts">
import Banner from "@/Components/Banner.vue";
</script>
<template>
    <div class="h-screen w-100 d-flex align-stretch">
        <div class="flex-fill w-50 left bg-primary"></div>
        <div
            class="flex-fill w-50 right d-flex flex-column justify-center px-2"
        >
            <slot></slot>
        </div>
        <Banner></Banner>
    </div>
</template>
<style scoped></style>

<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { textMap } from "../../constants/text";
import { ICreatePageProps } from "../../types/create-page.type";
import PageTitle from "./PageTitle.vue";
import useCreatePage from "../../hooks/useCreatePage";

const page = usePage<ICreatePageProps>();
const resource = page.props.resource;

const { isEdit } = useCreatePage();

const title = (isEdit ? textMap.verbs.edit : textMap.verbs.create)
    .concat(" ")
    // @ts-expect-error
    .concat(textMap.nouns[resource]?.toLowerCase());
</script>
<template>
    <div class="create-page">
        <PageTitle :title="title"></PageTitle>
        <slot></slot>
    </div>
</template>
<style scoped></style>

<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { provide } from "vue";
import { textMap } from "../../constants/text";
import { PageProps } from "../../types";
import {
    IHasPrimaryField,
    PRIMARY_FIELD_PROVIDE_KEY,
} from "../../types/common/has-primary-field.type";
import {
    IHasResource,
    RESOURCE_PROVIDE_KEY,
} from "../../types/common/has-resource.type";
import PageTitle from "./PageTitle.vue";
import { defineProps } from "vue";

type IProps = { createUrl?: string };

const props = defineProps<IProps>();

type IListPageProps = PageProps & IHasResource & IHasPrimaryField;

const page = usePage<IListPageProps>();

const title = textMap.nouns.list
    .concat(" ")
    // @ts-expect-error
    .concat(textMap.nouns[page.props.resource]?.toLowerCase());
</script>
<template>
    <div class="list-page">
        <PageTitle :title="title" :create-url="props.createUrl"></PageTitle>
        <slot></slot>
    </div>
</template>
<style scoped></style>

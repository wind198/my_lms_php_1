<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import PageTitle from "./PageTitle.vue";
import { IHasResource } from "../../types/common/has-resource.type";
import { PageProps } from "../../types";
import { textMap } from "../../constants/text";
import { IEntity } from "../../types/entities/entity.type";
import { provide } from "vue";
import { IHasRecordData } from "../../types/common/has-record-data.type";
import { IHasPrimaryField } from "../../types/common/has-primary-field.type";
import useShowPage from "../../hooks/useShowPage";

type IProps = { editUrl?: string };

const props = defineProps<IProps>();

const { recordData, resource, primaryField } = useShowPage();

const title = [
    textMap.verbs.viewDetail.concat(": "),
    // @ts-expect-error
    textMap.nouns[resource],
    // @ts-expect-error
    recordData[primaryField],
].join(" ");
</script>

<template>
    <div class="show-page">
        <PageTitle :edit-url="editUrl" :title="title"></PageTitle>
        <slot></slot>
    </div>
</template>
<style scoped></style>

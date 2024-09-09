<script setup lang="ts">
import { camelCase } from "lodash-es";
import {
    VListItem,
    VListItemSubtitle,
    VListItemTitle,
} from "vuetify/components";
import ShowPage from "../../Components/common/ShowPage.vue";
import SimpleShowLayout from "../../Components/common/SimpleShowLayout.vue";
import { textMap } from "../../constants/text";
import useCreatePage from "../../hooks/useCreatePage";

const { resource, recordData, resourcePlural } = useCreatePage();

const editUrl = window.route(`settings.${resourcePlural}.edit`, {
    [resource]: recordData.id,
});

const textFieldsForShowUser = ["title", "description"] as const;

type ITextField = (typeof textFieldsForShowUser)[number];

const getLabel = (i: ITextField) => {
    // @ts-expect-error
    return textMap.nouns[camelCase(i)];
};

const getValue = (i: ITextField) => {
    return recordData[i];
};
</script>
<template>
    <ShowPage :edit-url="editUrl">
        <SimpleShowLayout>
            <VListItem v-for="i in textFieldsForShowUser">
                <VListItemTitle>
                    {{ getLabel(i) }}
                </VListItemTitle>

                <VListItemSubtitle> {{ getValue(i) }} </VListItemSubtitle>
            </VListItem>
        </SimpleShowLayout>
    </ShowPage>
</template>
<style scoped></style>

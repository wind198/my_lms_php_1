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
import { router } from "@inertiajs/vue3";
import ColumnsLayoutForDashboard from "@/Layouts/ColumnsLayoutForDashboard.vue";
import SettingsLayout from "@/Layouts/SettingsLayout.vue";

const { resource, recordData, resourcePlural } = useCreatePage();

const editUrl = window.route(`study.${resourcePlural}.edit`, {
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
    <SettingsLayout
        ><ShowPage :edit-url="editUrl">
            <SimpleShowLayout>
                <VListItem v-for="i in textFieldsForShowUser">
                    <VListItemTitle>
                        {{ getLabel(i) }}
                    </VListItemTitle>

                    <VListItemSubtitle> {{ getValue(i) }} </VListItemSubtitle>
                </VListItem>
            </SimpleShowLayout>
        </ShowPage>
    </SettingsLayout>
</template>
<style scoped></style>

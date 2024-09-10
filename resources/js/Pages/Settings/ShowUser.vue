<script lang="ts">
export default {
    // @ts-expect-error
    layout: (h, page) =>
        h(ColumnsLayoutForDashboard, () => h(SettingsLayout, () => page)),
};
</script>
<script setup lang="ts">
import { camelCase } from "lodash-es";
import {
    VListItem,
    VListItemSubtitle,
    VListItemTitle,
    VRadioGroup,
} from "vuetify/components";
import ShowPage from "../../Components/common/ShowPage.vue";
import SimpleShowLayout from "../../Components/common/SimpleShowLayout.vue";
import { textMap } from "../../constants/text";
import useCreatePage from "../../hooks/useCreatePage";
import { Link, router } from "@inertiajs/vue3";
import { computed } from "vue";
import { AppLinkClasses, MenuLinkClasses } from "@/constants";
import ColumnsLayoutForDashboard from "@/Layouts/ColumnsLayoutForDashboard.vue";
import SettingsLayout from "@/Layouts/SettingsLayout.vue";
import { dateFormater } from "@/helper/formatter";

const { resource, recordData, resourcePlural } = useCreatePage();

const editUrl = window.route(`settings.${resourcePlural}.edit`, {
    [resource]: recordData.id,
});

const textFieldsForShowUser = [
    "full_name",
    "gender",
    "email",
    "phone",
    "address",
    "education_background",
    "education_background",
    "dob",
] as const;

type ITextField = (typeof textFieldsForShowUser)[number];

const getLabel = (i: ITextField) => {
    // @ts-expect-error
    return textMap.nouns[camelCase(i)];
};

const getValue = (i: ITextField) => {
    if (i === "dob") {
        return !i ? "" : dateFormater.standard(i);
    }
    if (i === "education_background") {
        // @ts-expect-error
        return textMap.nouns[recordData[i]];
    }
    // @ts-expect-error
    return recordData[i];
};

const generationUrl = computed(() => {
    if (!recordData.generation?.id) {
        return;
    }
    return window.route("settings.generations.show", {
        generation: recordData.generation?.id,
    });
});
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
            <VListItem v-if="generationUrl">
                <VListItemTitle>
                    {{ textMap.nouns.generation }}
                </VListItemTitle>
                <VListItemSubtitle>
                    <Link :class="[AppLinkClasses]" :href="generationUrl">
                        {{ recordData.generation?.title }}
                    </Link>
                </VListItemSubtitle></VListItem
            >
        </SimpleShowLayout>
    </ShowPage>
</template>
<style scoped></style>

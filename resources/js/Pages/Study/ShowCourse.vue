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
import { Link, router } from "@inertiajs/vue3";
import ColumnsLayoutForDashboard from "@/Layouts/ColumnsLayoutForDashboard.vue";
import SettingsLayout from "@/Layouts/SettingsLayout.vue";
import { IMajor } from "@/types/entities/major.type";
import { ICourse } from "@/types/entities/course.type";
import { AppLinkClasses } from "@/constants";

const { resource, recordData, resourcePlural } = useCreatePage<ICourse>();

const editUrl = window.route(`study.${resourcePlural}.edit`, {
    [resource]: recordData.id,
});

const textFieldsForShowUser = ["title", "description"] as (keyof ICourse)[];

type ITextField = (typeof textFieldsForShowUser)[number];

const getLabel = (i: ITextField) => {
    // @ts-expect-error
    return textMap.nouns[camelCase(i)];
};

const getValue = (i: ITextField) => {
    if (i === "major") {
        return recordData.major?.title;
    }
    return recordData[i];
};
const majorShowUrl = window.route(`study.majors.show`, {
    major: recordData.major?.id,
});
</script>
<template>
    <SettingsLayout>
        <ShowPage :edit-url="editUrl">
            <SimpleShowLayout>
                <VListItem v-for="i in textFieldsForShowUser">
                    <VListItemTitle>
                        {{ getLabel(i) }}
                    </VListItemTitle>

                    <VListItemSubtitle> {{ getValue(i) }} </VListItemSubtitle>
                </VListItem>
                <VListItem>
                    <VListItemTitle>
                        {{ textMap.nouns.major }}
                    </VListItemTitle>
                    <VListItemSubtitle>
                        <Link :class="[AppLinkClasses]" :href="majorShowUrl">
                            {{ recordData.major?.title }}
                        </Link>
                    </VListItemSubtitle>
                </VListItem>
            </SimpleShowLayout>
        </ShowPage>
    </SettingsLayout>
</template>
<style scoped></style>

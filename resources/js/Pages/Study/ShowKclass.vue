<script setup lang="ts">
import SettingsLayout from "@/Layouts/SettingsLayout.vue";
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
import { IKclass } from "../../types/entities/kclass.type";
import { joinWords } from "../../helper";

const { resource, recordData, resourcePlural } = useCreatePage<IKclass>();

const editUrl = window.route(`study.${resourcePlural}.edit`, {
    [resource]: recordData.id,
});

const textFieldsForShowUser = ["title", "description"] as (keyof IKclass)[];

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
                        {{ textMap.nouns.course }}
                    </VListItemTitle>
                    <VListItemSubtitle>
                        {{ recordData.course?.title }}
                    </VListItemSubtitle>
                </VListItem>
                <VListItem>
                    <VListItemTitle>
                        {{
                            joinWords(
                                textMap.adjectives.main,
                                textMap.nouns.teacher.toLowerCase()
                            )
                        }}
                    </VListItemTitle>
                    <VListItemSubtitle>
                        {{ recordData.mainTeacher?.full_name }}
                    </VListItemSubtitle>
                </VListItem>
            </SimpleShowLayout>
        </ShowPage>
    </SettingsLayout>
</template>
<style scoped></style>

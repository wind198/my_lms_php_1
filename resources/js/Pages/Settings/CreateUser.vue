<script lang="ts">
export default {
    layout: (h, page) =>
        h(ColumnsLayoutForDashboard, () => h(SettingsLayout, () => page)),
};
</script>
<script setup lang="ts">
import SimpleFormLayout from "@/Components/common/SimpleFormLayout.vue";
import {
    EducationBackgroundList,
    GenderOptionList,
    eduBgSelectItems,
} from "@/constants";
import { textMap } from "@/constants/text";
import { validationRules } from "@/helper/validation";
import { IUser, type IEducationBackground } from "@/types/entities/user.type";
import { useForm } from "@inertiajs/vue3";
import { camelCase } from "lodash-es";
import { computed } from "vue";
import { VRadio, VRadioGroup, VSelect, VTextField } from "vuetify/components";
import CreatePage from "../../Components/common/CreatePage.vue";
import ColumnsLayoutForDashboard from "../../Layouts/ColumnsLayoutForDashboard.vue";
import SettingsLayout from "../../Layouts/SettingsLayout.vue";
import useCreatePage from "../../hooks/useCreatePage";
import { IGeneration } from "@/types/entities/generation.type";
import AppDateSelectorMenu from "@/Components/common/AppDateSelectorMenu.vue";

const textFieldsForCreateUser = [
    "first_name",
    "last_name",
    "email",
    "phone",
    "address",
] as const;

type ITextField = (typeof textFieldsForCreateUser)[number];

type IProps = {
    generations: IGeneration[];
};

const props = defineProps<IProps>();

const getRules = (i: ITextField) => {
    switch (i) {
        case "first_name":
        case "last_name":
            return [validationRules.required(), validationRules.maxLength(50)];

        case "email":
            return [
                validationRules.required(),
                validationRules.email(),
                validationRules.maxLength(50),
            ];

        case "phone":
            return [validationRules.maxLength(50)];

        case "address":
            return [validationRules.maxLength(200)];

        default:
            return [];
    }
};

const { isEdit, recordData, resource, resourcePlural } = useCreatePage();

const defaultEducationBackground = computed((): IEducationBackground => {
    switch (resource) {
        case "student":
            return "high_school";
        case "teacher":
            return "bachelor";
        default:
            return "high_school";
    }
});

const form = useForm(
    recordData
        ? (recordData as IUser)
        : ({
              first_name: "",
              last_name: "",
              email: "",
              phone: "",
              address: "",
              note: "",
              education_background: defaultEducationBackground.value,
          } as IUser)
);

const onSubmit = () => {
    const transformedData = form.transform((data) => {
        return {
            ...data,
            first_name: data.first_name.trim(),
            last_name: data.last_name.trim(),
            email: data.email.trim(),
        };
    });
    if (!isEdit) {
        transformedData.post(window.route(`settings.${resource + "s"}.store`));
    } else {
        transformedData.patch(
            window.route(`settings.${resourcePlural}.update`, {
                [resource]: recordData.id,
            })
        );
    }
};

const deleteUrl = computed((): string | undefined => {
    if (!isEdit) {
        return undefined;
    }
    return window.route(`settings.${resourcePlural}.destroy`, {
        [resource]: recordData.id,
    });
});
</script>
<template>
    <CreatePage>
        <SimpleFormLayout
            :delete-url="deleteUrl"
            @submit="onSubmit"
            :processing="form.processing"
        >
            <VRadioGroup v-model="form.gender">
                <VRadio
                    v-for="i in GenderOptionList"
                    :key="i"
                    :label="textMap.nouns[i]"
                    :value="i"
                ></VRadio>
            </VRadioGroup>

            <VTextField
                v-for="i in textFieldsForCreateUser"
                :label="textMap.nouns[camelCase(i)]"
                v-model="form[i]"
                :rules="getRules(i)"
                :error-messages="form.errors?.[i]"
                @update:model-value="form.errors[i] = undefined"
            ></VTextField>
            <VSelect
                :items="eduBgSelectItems"
                item-title="title"
                item-value="value"
                :label="textMap.nouns.educationBackground"
                v-model="form.education_background"
            ></VSelect>
            <!-- <AppDateSelectorMenu
                :label="textMap.nouns.dob"
            ></AppDateSelectorMenu> -->
            <VSelect
                :label="textMap.nouns.generation"
                v-if="resource === 'student'"
                :items="generations"
                item-value="id"
                item-title="title"
                v-model="form.generation_id"
            >
            </VSelect>
        </SimpleFormLayout>
    </CreatePage>
</template>
<style scoped></style>

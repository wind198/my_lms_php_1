<script lang="ts">
export default {
    layout: (h, page) =>
        h(ColumnsLayoutForDashboard, () => h(SettingsLayout, () => page)),
};
</script>
<script setup lang="ts">
import SimpleFormLayout from "@/Components/common/SimpleFormLayout.vue";
import { EducationBackgroundList } from "@/constants";
import { textMap } from "@/constants/text";
import { validationRules } from "@/helper/validation";
import { useForm } from "@inertiajs/vue3";
import { camelCase } from "lodash-es";
import { computed } from "vue";
import { VSelect, VTextField } from "vuetify/components";
import CreatePage from "../../Components/common/CreatePage.vue";
import ColumnsLayoutForDashboard from "../../Layouts/ColumnsLayoutForDashboard.vue";
import useCreatePage from "../../hooks/useCreatePage";
import { IMajor } from "../../types/entities/major.type";
import SettingsLayout from "../../Layouts/SettingsLayout.vue";

const textFieldsForCreateMajor = ["title", "description"] as const;

type ITextField = (typeof textFieldsForCreateMajor)[number];

const getRules = (i: ITextField) => {
    switch (i) {
        case "title":
            return [validationRules.required(), validationRules.maxLength(50)];

        case "description":
            return [validationRules.maxLength(250)];

        default:
            return [];
    }
};

const { isEdit, recordData, resource, resourcePlural } = useCreatePage();

const form = useForm<Omit<IMajor, "id">>(
    recordData
        ? (recordData as IMajor)
        : {
              title: "",
              description: "",
          }
);

const selectEduBgItems = EducationBackgroundList.map((i) => ({
    value: i,
    title: textMap.nouns[i],
}));

const onSubmit = () => {
    const transformedData = form.transform((data) => {
        return {
            ...data,
        };
    });
    if (!isEdit) {
        transformedData.post(window.route(`study.${resource + "s"}.store`));
    } else {
        transformedData.patch(
            window.route(`study.${resourcePlural}.update`, {
                [resource]: recordData.id,
            })
        );
    }
};

const deleteUrl = computed((): string | undefined => {
    if (!isEdit) {
        return undefined;
    }
    return window.route(`study.${resourcePlural}.destroy`, {
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
            <VTextField
                v-for="i in textFieldsForCreateMajor"
                :label="textMap.nouns[camelCase(i)]"
                v-model="form[i]"
                :rules="getRules(i)"
                :error-messages="form.errors?.[i]"
                @update:model-value="form.errors[i] = undefined"
            ></VTextField>
        </SimpleFormLayout>
    </CreatePage>
</template>
<style scoped></style>

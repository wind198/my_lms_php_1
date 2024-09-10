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
import SettingsLayout from "../../Layouts/SettingsLayout.vue";
import { ICourse } from "@/types/entities/course.type";
import { IMajor } from "@/types/entities/major.type";

const textFieldsForCreateCourse = ["title", "description"] as const;

type IProps = {
    majors: IMajor[];
};

const props = defineProps<IProps>();

type ITextField = (typeof textFieldsForCreateCourse)[number];

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

const form = useForm<Omit<ICourse, "id">>(
    recordData
        ? (recordData as ICourse)
        : ({
              title: "",
              description: "",
          } as ICourse)
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
                v-for="i in textFieldsForCreateCourse"
                :label="textMap.nouns[camelCase(i)]"
                v-model="form[i]"
                :rules="getRules(i)"
                :error-messages="form.errors?.[i]"
                @update:model-value="form.errors[i] = undefined"
            ></VTextField>
            <VSelect
                :label="textMap.nouns.major"
                :items="majors"
                item-value="id"
                item-title="title"
                v-model="form.major_id"
            >
            </VSelect>
        </SimpleFormLayout>
    </CreatePage>
</template>
<style scoped></style>

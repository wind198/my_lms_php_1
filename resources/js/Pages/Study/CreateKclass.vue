<script setup lang="ts">
import SimpleFormLayout from "@/Components/common/SimpleFormLayout.vue";
import { textMap } from "@/constants/text";
import { validationRules } from "@/helper/validation";
import { useForm } from "@inertiajs/vue3";
import { camelCase } from "lodash-es";
import { VTextField } from "vuetify/components";
import CreatePage from "../../Components/common/CreatePage.vue";
import useCreatePage from "../../hooks/useCreatePage";
import useResourceRoutes from "../../hooks/useResourceRoutes";
import { IKclass } from "../../types/entities/kclass.type";

const textFieldsForCreateMajor = ["title", "description"] as (keyof IKclass)[];

type ITextField = (typeof textFieldsForCreateMajor)[number];

const getRules = (i: ITextField) => {
    switch (i) {
        case "title":
            return [validationRules.required(), validationRules.maxLength(50)];

        case "description":
            return [validationRules.maxLength(250)];

        case "main_teacher_id":
        case "course_id":
            return [validationRules.required()];

        default:
            return [];
    }
};

const { isEdit, recordData, resource, resourcePlural } =
    useCreatePage<IKclass>();

const { getDestroyRoute, getUpdateRoute, storeRoute } = useResourceRoutes();

const form = useForm<Partial<IKclass>>(
    recordData
        ? (recordData as IKclass)
        : {
              title: "",
              description: "",
              course_id: undefined,
              main_teacher_id: undefined,
              code: "",
          }
);

const onSubmit = () => {
    const transformedData = form.transform((data) => {
        return {
            ...data,
        };
    });
    if (!isEdit) {
        transformedData.post(storeRoute.value!);
    } else {
        transformedData.patch(getUpdateRoute(recordData?.id));
    }
};
</script>
<template>
    <CreatePage>
        <SimpleFormLayout
            :delete-url="getDestroyRoute(recordData?.id)!"
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

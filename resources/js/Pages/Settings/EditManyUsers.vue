<script setup lang="ts">
import PageTitle from "@/Components/common/PageTitle.vue";
import SimpleFormLayout from "@/Components/common/SimpleFormLayout.vue";
import { GenderOptionList, eduBgSelectItems } from "@/constants";
import { textMap } from "@/constants/text";
import { validationRules } from "@/helper/validation";
import useResource from "@/hooks/useResource";
import { IGeneration } from "@/types/entities/generation.type";
import { IUser } from "@/types/entities/user.type";
import { useForm } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";
import {
    VExpansionPanels,
    VExpansionPanel,
    VChip,
    VForm,
    VSelect,
    VRadioGroup,
    VRadio,
    VCheckboxBtn,
} from "vuetify/components";

type IProps = {
    selectedUsers: IUser[];
    generations: IGeneration[];
};

const props = defineProps<IProps>();

type IFormData = Partial<
    Pick<IUser, "education_background" | "generation_id" | "gender">
>;

const { resourcePlural } = useResource();

const editableSettings: Record<keyof IFormData, boolean> = reactive({
    education_background: true,
    gender: true,
    generation_id: true,
});

const getSettingLabel = (i: keyof IFormData) => {
    switch (i) {
        case "education_background":
            return textMap.nouns.educationBackground;
        case "gender":
            return textMap.nouns.gender;
        case "generation_id":
            return textMap.nouns.generation;
    }
};

const editableSettingsCount = computed(
    () => Object.keys(editableSettings).length
);
const selectedEditableSettingsCount = computed(
    () =>
        Object.keys(editableSettings).filter((i) => editableSettings[i]).length
);

const selectedIntroText = [
    textMap.verbs.selected,
    props.selectedUsers.length,
    textMap.nouns.user,
].join(" ");

const settingsIntroText = computed(() =>
    [
        textMap.verbs.selected,
        `${selectedEditableSettingsCount.value}/${editableSettingsCount.value}`,
        textMap.nouns.info.toLowerCase(),
    ].join(" ")
);

const form = useForm<IFormData>({
    gender: "female",
    education_background: undefined,
    generation_id: undefined,
});

const apendDeleteIcon = computed(() => {
    if (selectedEditableSettingsCount.value < 2) {
        return undefined;
    }
    return "mdi-delete";
});

const submit = () => {
    form.transform((data) => {
        const { education_background, gender, generation_id } = data;

        const output = {
            ...(editableSettings.education_background && {
                education_background,
            }),
            ...(editableSettings.gender && { gender }),
            ...(editableSettings.generation_id && { generation_id }),
        } as Record<string, any>;
        return output;
    }).patch(
        window.route(`settings.${resourcePlural}.update-many`, {
            ids: props.selectedUsers.map((i) => i.id),
        }),
        {
            onFinish: () => {
                form.reset();
            },
        }
    );
};
</script>
<template>
    <div class="update-many-users">
        <PageTitle :title="textMap.title.updateManyUser"></PageTitle>
        <VExpansionPanels>
            <VExpansionPanel
                :min-height="40"
                :ripple="false"
                :title="selectedIntroText"
                :elevation="0"
            >
                <template v-slot:text="">
                    <div class="d-flex flex-wrap py-2">
                        <VChip
                            class="mr-1 mb-2"
                            v-for="u in selectedUsers"
                            :key="u.id"
                        >
                            {{ u.full_name }}
                        </VChip>
                    </div>
                </template>
            </VExpansionPanel>
            <VExpansionPanel
                :min-height="40"
                :ripple="false"
                :title="settingsIntroText"
                :elevation="0"
            >
                <template v-slot:text="">
                    <div class="d-flex flex-column">
                        <VCheckboxBtn
                            class="flex-grow-1"
                            color="primary"
                            :label="getSettingLabel(k)"
                            v-for="(v, k) in editableSettings"
                            :key="k"
                            v-model="editableSettings[k]"
                        >
                        </VCheckboxBtn>
                    </div>
                </template>
            </VExpansionPanel>
        </VExpansionPanels>
        <SimpleFormLayout class="mt-4 px-3 update-form" @submit="submit">
            <p class="font-weight-medium mb-3">
                {{ textMap.title.updateForm }}
            </p>
            <VRadioGroup
                :label="textMap.nouns.gender"
                :append-icon="apendDeleteIcon"
                v-model="form.gender"
                v-if="editableSettings.gender"
                direction="horizontal"
                inline
                @click:append="
                    editableSettings.gender = !editableSettings.gender
                "
                color="primary"
                :rules="[validationRules.required()]"
            >
                <VRadio
                    v-for="i in GenderOptionList"
                    :key="i"
                    :label="textMap.nouns[i]"
                    :value="i"
                ></VRadio>
            </VRadioGroup>
            <VSelect
                v-if="editableSettings.education_background"
                :items="eduBgSelectItems"
                item-title="title"
                item-value="value"
                :label="textMap.nouns.educationBackground"
                v-model="form.education_background"
                :append-icon="apendDeleteIcon"
                @click:append="
                    editableSettings.education_background =
                        !editableSettings.education_background
                "
                :rules="[validationRules.required()]"
            ></VSelect>
            <VSelect
                :append-icon="apendDeleteIcon"
                v-if="editableSettings.generation_id"
                :label="textMap.nouns.generation"
                :items="generations"
                item-value="id"
                item-title="title"
                v-model="form.generation_id"
                @click:append="
                    editableSettings.generation_id =
                        !editableSettings.generation_id
                "
                :rules="[validationRules.required()]"
            ></VSelect>
        </SimpleFormLayout>
    </div>
</template>
<style scoped>
.update-form {
    width: 800px;
}
</style>

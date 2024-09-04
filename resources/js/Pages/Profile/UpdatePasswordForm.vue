<script lang="ts">
export default {
    // @ts-expect-error
    layout: (h, page) => {
        return h(ColumnsLayoutForDashboard, () =>
            h(UserProfileLayout, () => page)
        );
    },
};
</script>
<script setup lang="ts">
import SimpleFormLayout from "@/Components/common/SimpleFormLayout.vue";
import ColumnsLayoutForDashboard from "@/Layouts/ColumnsLayoutForDashboard.vue";
import UserProfileLayout from "@/Layouts/UserProfileLayout.vue";
import { textMap } from "@/constants/text";
import { validationRules } from "@/helper/validation";
import { useForm } from "@inertiajs/vue3";
import { reactive, ref } from "vue";
import { VTextField } from "vuetify/components";

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const textFields = [
    "current_password",
    "password",
    "password_confirmation",
] as const;

type ITextField = (typeof textFields)[number];

const getTextFieldLabel = (i: ITextField) => {
    switch (i) {
        case "current_password":
            return textMap.nouns.password
                .concat(" ")
                .concat(textMap.adjectives.current);
        case "password":
            return textMap.nouns.password
                .concat(" ")
                .concat(textMap.adjectives.new);
        case "password_confirmation":
            return textMap.verbs.retype
                .concat(" ")
                .concat(textMap.nouns.password)
                .concat(" ")
                .concat(textMap.adjectives.new);

        default:
            break;
    }
};

const getInputRef = (i: ITextField) => {
    switch (i) {
        case "current_password":
            return "currentPasswordInput";
        case "password":
            return "passwordInput";
        default:
            break;
    }
};

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const passwordShowStatus = reactive({
    current_password: false,
    password: false,
    password_confirmation: false,
});

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <SimpleFormLayout @submit="updatePassword">
        <VTextField
            v-for="i in textFields"
            :key="i"
            v-model="form[i]"
            :label="getTextFieldLabel(i)"
            :error-messages="form.errors[i]"
            @update:model-value="form.errors[i] = undefined"
            :ref="getInputRef(i)"
            :rules="[validationRules.required()]"
            :type="passwordShowStatus[i] ? 'text' : 'password'"
            :append-inner-icon="
                passwordShowStatus[i] ? 'mdi-eye-off' : 'mdi-eye'
            "
            @click:append-inner="passwordShowStatus[i] = !passwordShowStatus[i]"
        ></VTextField>
    </SimpleFormLayout>
</template>

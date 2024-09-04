<script setup lang="ts">
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    VForm,
    VCard,
    VTextField,
    VCardTitle,
    VCardSubtitle,
    VBtn,
    VCardActions,
    VCardText,
} from "vuetify/components";
import { textMap } from "@/constants/text";
import { validationRules } from "@/helper/validation";
import { ref } from "vue";
import { IS_DEV } from "@/constants/environments";

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const formData = useForm({
    email: IS_DEV ? "tuanbk1908@gmail.com" : "",
    password: IS_DEV ? "123123" : "",
    remember: false,
});

const submit = () => {
    formData.post(route("login"), {
        onFinish: () => {
            formData.reset("password");
        },
    });
};

const showingPassword = ref(false);
</script>

<template>
    <Head title="Log in"></Head>
    <VForm ref="loginFormRef" @submit.prevent="submit">
        <VCard elevation="0">
            <VCardTitle>{{ textMap.messages.welcome }}. </VCardTitle>
            <VCardSubtitle class="text-on-surface">
                {{ textMap.messages.pleaseLogin }}
            </VCardSubtitle>
            <VCardText>
                <VTextField
                    :rules="[
                        validationRules.required(),
                        validationRules.email(),
                    ]"
                    v-model="formData.email"
                    :label="textMap.nouns.email"
                    type="email"
                    :error-messages="formData.errors.email"
                    @update:model-value="formData.errors.email = undefined"
                >
                </VTextField>
                <VTextField
                    :rules="[validationRules.required()]"
                    :type="showingPassword ? 'text' : 'password'"
                    v-model="formData.password"
                    :label="textMap.nouns.password"
                    :error-messages="formData.errors.password"
                    :append-icon="!showingPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="showingPassword = !showingPassword"
                    @update:model-value="formData.errors.password = undefined"
                ></VTextField>
            </VCardText>
            <VCardActions>
                <VBtn
                    type="submit"
                    variant="flat"
                    color="primary"
                    :loading="formData.processing"
                >
                    {{ textMap.verbs.login }}
                </VBtn>
            </VCardActions>
        </VCard>
    </VForm>
</template>

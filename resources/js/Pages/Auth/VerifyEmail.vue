<script setup lang="ts">
import { computed } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { VBtn } from "vuetify/components";

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);

const logout = () => {
    router.post(window.route("logout"));
};
</script>

<template>
    <Head title="Email Verification" />
    <div class="pa-3">
        <p class="text-h6 mb-2">Thanks for signing up!</p>
        <p class="text-body-1 mb-2">
            Before getting started, could you verify your email address by
            clicking on the link we just emailed to you? If you didn't receive
            the email, we will gladly send you another.
        </p>

        <div
            class="text-body-2 text-primary text-weigt-bold"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 d-flex items-center justify-end width-100">
                <VBtn
                    type="submit"
                    flat
                    color="primary"
                    :loading="form.processing"
                >
                    Resend Verification Email
                </VBtn>

                <VBtn @click="logout" flat> Logout </VBtn>
            </div>
        </form>
    </div>
</template>

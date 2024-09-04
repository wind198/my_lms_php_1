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
import { useForm, usePage } from "@inertiajs/vue3";
import { camelCase } from "lodash-es";
import { VCard, VTextField } from "vuetify/components";

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user;

const textFields = [
    "first_name",
    "last_name",
    "phone",
    "email",
    "address",
    "education_background",
    "note",
] as const;

type ITextField = (typeof textFields)[number];

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    phone: user.phone,
    email: user.email,
    address: user.address,
    education_background: user.education_background,
    note: user.note,
});

const getLabel = (i: ITextField) => {
    // @ts-expect-error
    return textMap.nouns[camelCase(i)] ?? i;
};

const onSubmit = () => {
    form.post("/profile/update");
};
</script>

<template>
    <div>
        <SimpleFormLayout @submit="onSubmit">
            <VTextField
                v-for="i in textFields"
                :key="i"
                v-model="form[i]"
                :label="getLabel(i)"
                :error-messages="form.errors[i]"
                @update:model-value="form.errors[i] = undefined"
            ></VTextField>
        </SimpleFormLayout>
    </div>
</template>

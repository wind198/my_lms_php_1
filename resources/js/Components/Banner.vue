<script setup>
import { ref, watchEffect } from "vue";
import { usePage } from "@inertiajs/vue3";
import { VBtn, VIcon, VSnackbar } from "vuetify/components";
import { textMap } from "@/constants/text";

const page = usePage();
const show = ref(true);
const style = ref("success");
const message = ref("");

watchEffect(async () => {
    style.value = page.props.flash?.message?.type || "success";
    message.value = page.props.flash?.message?.content || "";
    show.value = true;
});
</script>

<template>
    <div>
        <VSnackbar
            :timeout="5000"
            close-on-back
            :height="40"
            v-if="message"
            v-model="show"
            multi-line
            :color="style"
        >
            {{ message }}

            <template v-slot:actions>
                <VBtn
                    icon
                    size="small"
                    color="style"
                    variant="text"
                    @click="show = false"
                >
                    <VIcon>mdi-close</VIcon>
                </VBtn>
            </template>
        </VSnackbar>
    </div>
</template>

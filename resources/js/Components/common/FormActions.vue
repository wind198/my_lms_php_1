<script setup lang="ts">
import { textMap } from "@/constants/text";
import { VBtn } from "vuetify/components";

type IProps = {
    processing?: boolean;
    deleteUrl?: string;
};

const props = defineProps<IProps>();
</script>
<template>
    <div class="actions d-flex align-center justify-end">
        <VBtn flat color="error" type="button" v-if="deleteUrl" class="mr-2">
            {{ textMap.verbs.delete }}
        </VBtn>
        <VBtn flat color="primary" type="submit" :loading="props.processing">
            {{ textMap.verbs.save }}
        </VBtn>
    </div>
</template>
<style scoped></style>

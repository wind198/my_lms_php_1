<script setup lang="ts">
import { textMap } from "@/constants/text";
import { Link } from "@inertiajs/vue3";
import { VBtn } from "vuetify/components";

type IProps = {
    title: string;
    subtitle?: string;
    createUrl?: string;
    editUrl?: string;
};

const props = defineProps<IProps>();
</script>
<template>
    <div class="d-flex align-center justify-space-between mb-4">
        <div class="title-wrapper">
            <slot>
                <h1 class="text-h6">
                    {{ props.title }}
                </h1>
                <p class="text-subtitle-2 mt-2" v-if="props.subtitle"></p>
            </slot>
        </div>
        <div class="actions">
            <slot name="actions">
                <div class="buttons d-flex align-end" v-if="props.createUrl">
                    <Link :href="props.createUrl">
                        <VBtn color="primary" flat append-icon="mdi-plus">
                            {{ textMap.verbs.create }}
                        </VBtn>
                    </Link>
                </div>
                <div class="buttons d-flex align-end" v-if="props.editUrl">
                    <Link :href="props.editUrl">
                        <VBtn color="primary" flat append-icon="mdi-pencil">
                            {{ textMap.verbs.edit }}
                        </VBtn>
                    </Link>
                </div>
            </slot>
        </div>
    </div>
</template>
<style scoped></style>

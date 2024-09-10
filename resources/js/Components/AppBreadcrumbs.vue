<script setup lang="ts">
import useCheckLinkActive from "@/hooks/useCheckLinkActive";
import { PageProps } from "@/types";
import { IHasBreadcrumbs } from "@/types/common/has-breadcrumbs.type";
import { Link, usePage } from "@inertiajs/vue3";
import { VBreadcrumbs, VIcon } from "vuetify/components";

const page = usePage<PageProps & IHasBreadcrumbs>();

const { checkLinkActiveState } = useCheckLinkActive();

const br = page.props.breadcrumbs.map((i) => {
    const isActive = checkLinkActiveState(i.url);
    return { ...i, isActive };
});
</script>
<template>
    <div class="d-flex align-center">
        <template v-for="(l, index) in br" :key="index">
            <Link
                :class="[...(l.isActive ? ['font-weight-medium'] : [])]"
                class="text-decoration-none text-black text-body-2 breadcrumb"
                :href="l.url"
                >{{ l.text }}</Link
            >
            <VIcon v-if="index < br.length - 1" small class="mx-2 separator">
                mdi-chevron-right
            </VIcon>
        </template>
    </div>
</template>
<style lang="scss" scoped>
.separator {
    font-size: 1em;
}
</style>

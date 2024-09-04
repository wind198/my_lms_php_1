<script setup lang="ts">
import PageTitle from "@/Components/common/PageTitle.vue";
import { textMap } from "@/constants/text";
import useCheckLinkActive from "@/hooks/useCheckLinkActive";
import { Link } from "@inertiajs/vue3";
import { VCard, VCardText, VIcon } from "vuetify/components";

const tabs = [
    {
        text: textMap.nouns.userInfo,
        value: "user-info",
        href: window.route("profile.user-info"),
    },
    {
        text: textMap.verbs.updateItem({ item: textMap.nouns.password }),
        value: "update-pass",
        href: window.route("profile.update-password"),
    },
];

const { checkLinkActiveState } = useCheckLinkActive();
</script>
<template>
    <div class="user-profile-wrapper">
        <PageTitle :title="textMap.nouns.userProfile"> </PageTitle>

        <div class="user-profile d-flex h-100">
            <div class="tabs d-flex flex-column border-e">
                <Link
                    class="tab px-2 pl-5 py-2 mb-1 text-body-2 text-black text-decoration-none cursor-pointer position-relative"
                    v-for="tab in tabs"
                    :filterKey="tab.value"
                    :href="tab.href"
                    :class="{
                        active: checkLinkActiveState(tab.href, true),
                    }"
                >
                    <VIcon class="active-indicator"> mdi-arrow-right </VIcon>
                    {{ tab.text }}</Link
                >
            </div>
            <div class="user-profile-content flex-fill px-4">
                <slot> </slot>
            </div>
        </div>
    </div>
</template>
<style scoped lang="scss">
.user-profile {
    --tab-width: 220px;
    .tabs {
        padding-right: 16px;
    }
    .user-profile-content {
        padding-left: 24px;
    }
}

:deep(.profile-tabs .v-tab) {
    font-weight: normal;
    text-transform: none;
}

.active-indicator {
    font-size: 0.875rem;
    display: none;
    position: absolute;
    left: 0;
    transform: translateY(2px);
}

.tab.active .active-indicator {
    display: inline;
}
</style>

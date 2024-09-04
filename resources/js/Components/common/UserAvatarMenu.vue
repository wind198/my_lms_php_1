<script setup lang="ts">
import { NavLinkClasses } from "@/constants";
import { textMap } from "@/constants/text";
import { concatClasses } from "@/helper";
import type { IUser } from "@/types/entities/user.type";
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import {
    VAvatar,
    VList,
    VListItem,
    VListItemTitle,
    VMenu,
} from "vuetify/components";

const page = usePage();

const currentUser = computed(() => {
    return page.props.auth.user as unknown as IUser;
});

const profileUrl = window.route("profile.user-info");

const logout = () => {
    router.post(window.route("logout"));
};
</script>
<template>
    <VMenu :close-on-content-click="false">
        <template v-slot:activator="{ props: activatorProps }">
            <VAvatar v-bind="activatorProps" class="cursor-pointer">
                {{ currentUser.full_name[0] }}
            </VAvatar>
        </template>
        <VList class="menu-option-list">
            <Link
                :href="profileUrl"
                :class="NavLinkClasses.concat('px-4', 'py-2', 'd-block')"
            >
                {{ textMap.nouns.profile }}</Link
            >
            <button
                :class="
                    NavLinkClasses.concat(
                        'px-4',
                        'py-2',
                        'd-block',
                        'text-left'
                    )
                "
                @click="logout"
            >
                {{ textMap.verbs.logout }}
            </button>
        </VList>
    </VMenu>
</template>
<style scoped>
.menu-option-list {
    min-width: 240px;
    display: flex;
    flex-direction: column;
    align-items: stretch;
}
</style>

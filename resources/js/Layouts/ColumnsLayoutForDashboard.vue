<script setup lang="ts">
import { textMap } from "@/constants/text";
import {
    VApp,
    VAppBar,
    VContainer,
    VList,
    VMain,
    VNavigationDrawer,
    VResponsive,
    VListItem,
    VListItemTitle,
    VExpansionPanel,
    VExpansionPanels,
} from "vuetify/components";
import { Link, usePage } from "@inertiajs/vue3";
import { concatClasses } from "@/helper";
import { AppLinkClasses, NavLinkClasses } from "@/constants";
import LeftDrawerSubMenuTogglerButton from "@/Components/common/LeftDrawerSubMenuTogglerButton.vue";
import { computed, ref } from "vue";
import type { IUser } from "@/types/entities/user.type";
import UserAvatarMenu from "@/Components/common/UserAvatarMenu.vue";
import useCheckLinkActive from "@/hooks/useCheckLinkActive";
import Banner from "@/Components/Banner.vue";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";
import useConfirmDialogStore from "@/store/useConfirmDialog";

const settingLinks = [
    {
        to: "/settings/students",
        text: textMap.nouns.student,
        value: "students",
    },
    {
        to: "/settings/teachers",
        text: textMap.nouns.teacher,
        value: "teachers",
    },
];
const studyLinks = [
    {
        to: "/study/majors",
        text: textMap.nouns.major,
        value: "majors",
    },
    {
        to: "/study/courses",
        text: textMap.nouns.course,
        value: "courses",
    },
];

const expansionPanelValues = ["human_management", "study"] as const;

type IExpansionPanelValue = (typeof expansionPanelValues)[number];

const { checkLinkActiveState } = useCheckLinkActive();

const page = usePage();

const getClassesForNavLink = (to: string) =>
    NavLinkClasses.concat(
        "px-4",
        "py-2",
        "d-block",
        checkLinkActiveState(to) ? "active" : ""
    );

const subListOpenState = ref<IExpansionPanelValue[]>([
    "human_management",
    ...(page.url.includes("study") ? (["study"] as const) : []),
]);

const confirmDialogStore = useConfirmDialogStore();
</script>
<template>
    <VResponsive class="rounded h-100">
        <VApp>
            <VAppBar :title="textMap.appTitle.long">
                <template v-slot:append>
                    <div class="d-flex align-center px-2">
                        <UserAvatarMenu></UserAvatarMenu>
                    </div>
                </template>
            </VAppBar>

            <VNavigationDrawer>
                <VExpansionPanels
                    multiple
                    v-model="subListOpenState"
                    class="pa-2"
                >
                    <VExpansionPanel
                        :title="textMap.nouns.human_management"
                        :elevation="0"
                        value="human_management"
                    >
                        <template v-slot:text="">
                            <div
                                v-for="link in settingLinks"
                                :key="link.value"
                                v-ripple
                            >
                                <Link
                                    :class="getClassesForNavLink(link.to)"
                                    :href="link.to"
                                    v-ripple
                                    >{{ link.text }}
                                </Link>
                            </div>
                        </template>
                    </VExpansionPanel>
                    <VExpansionPanel
                        :title="textMap.nouns.study"
                        :elevation="0"
                        value="study"
                    >
                        <template v-slot:text="">
                            <div
                                v-for="link in studyLinks"
                                :key="link.value"
                                v-ripple
                            >
                                <Link
                                    :class="getClassesForNavLink(link.to)"
                                    :href="link.to"
                                    v-ripple
                                    >{{ link.text }}
                                </Link>
                            </div>
                        </template>
                    </VExpansionPanel>
                </VExpansionPanels>
            </VNavigationDrawer>

            <VMain class="bg-white">
                <VContainer>
                    <slot></slot>
                </VContainer>
            </VMain>
        </VApp>
        <Banner></Banner>
        <ConfirmDialog></ConfirmDialog>
    </VResponsive>
</template>
<style scoped lang="scss">
.item-list {
    padding: 8px;
    display: flex;
    flex-direction: column;
}

:deep(.v-expansion-panel-text__wrapper) {
    padding: 0;
}
:deep(.v-expansion-panel-title) {
    padding-left: 12px;
    padding-right: 12px;
    font-weight: 600;
}
</style>

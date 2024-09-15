import { computed, reactive, ref } from "vue";
import { setColumnShownForResource } from "../helper";
import useResource from "./useResource";

export type IuseColumnConfigurationForTable = {
    headers: {
        value: string;
        title: string;
        sortable: boolean;
        width?: number | string;
        align: "start" | "center" | "end";
    }[];
    intialShow: string[];
};
export default function useColumnConfigurationForTable(
    options: IuseColumnConfigurationForTable
) {
    const { resource } = useResource();

    const headerWithVisibility = ref(
        options.headers.map((i) => {
            const visible = options.intialShow.includes(i.value);
            return {
                ...i,
                visible: visible || i.value === "actions",
            };
        })
    );

    const headerVisibilityMap = computed(() => {
        Object.fromEntries(
            headerWithVisibility.value.map(({ value, visible }) => [
                value,
                visible,
            ])
        );
    });

    const visibleHeaders = computed(() => {
        return headerWithVisibility.value.filter((i) => i.visible);
    });

    const toggleVisibity = (value: string) => {
        headerWithVisibility.value = headerWithVisibility.value.map((i) => {
            if (i.value === value) {
                return { ...i, visible: !i.visible };
            }
            return i;
        });
        setColumnShownForResource(
            resource,
            headerWithVisibility.value
                .filter((i) => i.visible)
                .map((i) => i.value)
        );
    };

    return {
        visibleHeaders,
        headerWithVisibility,
        headerVisibilityMap,
        toggleVisibity,
    };
}

import { computed, reactive, ref } from "vue";

export type IuseColumnConfigurationForTable = {
    headers: {
        value: string;
        title: string;
        sortable: boolean;
        align: "start" | "center" | "end";
    }[];
    intialShow: string[];
};
export default function useColumnConfigurationForTable(
    options: IuseColumnConfigurationForTable
) {
    const headerWithVisibility = ref(
        options.headers.map((i) => {
            const visible = options.intialShow.includes(i.value);
            return {
                ...i,
                visible,
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
    };

    return {
        visibleHeaders,
        headerWithVisibility,
        headerVisibilityMap,
        toggleVisibity,
    };
}

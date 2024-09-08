import { router } from "@inertiajs/vue3";
import type { IOrder } from "../types/common/order.type";
import type { IServerTableParams } from "../types/common/server-table.type";
import type { IEntity } from "../types/entities/entity.type";
import { reactive, type ComputedRef } from "vue";
import { cloneDeep } from "lodash-es";
import { removeValueFromObject } from "../helper";
import { stringify } from "qs";
import { DefaultTablePerPage } from "../constants";
import { Ref } from "vue";

// type IProps<T> = {
//     data: T[];
//     params: IServerTableParams<T>;
//     headers: any[];
// };

export default function useServerTable<T>({
    data,
    headers,
    params,
}: {
    data: Ref<T[]>;
    params: Ref<IServerTableParams<T>>;
    headers: Ref<any[]>;
}) {
    const handleChangeOrder = (key: string) => {
        const matchColumn = headers.value.find((i) => i.value === key);
        if (!matchColumn?.sortable) {
            return;
        }
        const urlParams = new URLSearchParams(window.location.search);
        const newOrder = params.value.order === "desc" ? "asc" : "desc";

        if (key === "created_at" && newOrder === "desc") {
            urlParams.delete("order");
            urlParams.delete("order_by");
        } else {
            urlParams.set("order_by", key);
            urlParams.set("order", newOrder);
        }

        params.value.order = newOrder;
        params.value.order_by = key as keyof T;

        const data = Object.fromEntries(urlParams.entries());

        router.get(window.location.pathname, data, { preserveState: true });
    };

    const handleChangePage = (v: number) => {
        const urlParams = new URLSearchParams(window.location.search);
        if (v === 1) {
            urlParams.delete("page");
        } else {
            urlParams.set("page", v.toString());
        }

        const data = Object.fromEntries(urlParams.entries());

        router.get(window.location.pathname, data, { preserveState: true });
    };
    const handleChangePerPage = (v: number) => {
        const urlParams = new URLSearchParams(window.location.search);
        if (v === DefaultTablePerPage) {
            urlParams.delete("per_page");
        } else {
            urlParams.set("per_page", v.toString());
        }

        const data = Object.fromEntries(urlParams.entries());

        router.get(window.location.pathname, data, { preserveState: true });
    };

    const handleChangeFilter = (newFilters: Record<string, any>) => {
        const urlParams = new URLSearchParams(window.location.search);

        let paramsObject = Object.fromEntries(urlParams.entries());

        paramsObject = removeValueFromObject(paramsObject, (_, k) =>
            k.startsWith("filter")
        );

        const data = {
            ...paramsObject,
            filters: newFilters,
        };

        let newUrl = window.location.pathname;

        const queryStr = stringify(data);

        newUrl += `?${queryStr}`;

        router.visit(newUrl, { preserveState: true });
    };

    return {
        handleChangeFilter,
        handleChangePage,
        handleChangeOrder,
        handleChangePerPage,
    };
}

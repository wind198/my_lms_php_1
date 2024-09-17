import { usePage } from "@inertiajs/vue3";
import { PageProps } from "../types";
import { IHasResource } from "../types/common/has-resource.type";
import useResource from "./useResource";
import { IHasIndexRoute } from "../types/common/has-index-route";
import { computed, toRefs } from "vue";

export default function useResourceRoutes() {
    const { resource, resourcePlural } = useResource();

    const page = usePage<PageProps & IHasIndexRoute>();

    const { index_route: indexRoute } = toRefs(page.props);

    const createRoute = computed(() =>
        indexRoute.value
            ? window.route([indexRoute.value, "create"].join("."))
            : undefined
    );
    const storeRoute = computed(() =>
        indexRoute.value
            ? window.route([indexRoute.value, "store"].join("."))
            : undefined
    );
    const getShowRoute = (recordId?: number, $resource = resource) =>
        indexRoute.value && recordId
            ? window.route([indexRoute.value, "show"].join("."), {
                  [resource]: recordId,
              })
            : undefined;
    const getEditRoute = (recordId?: number) =>
        indexRoute.value && recordId
            ? window.route([indexRoute.value, "edit"].join("."), {
                  [resource]: recordId,
              })
            : undefined;
    const getUpdateRoute = (recordId?: number) =>
        indexRoute.value && recordId
            ? window.route([indexRoute.value, "update"].join("."), {
                  [resource]: recordId,
              })
            : undefined;
    const getDestroyRoute = (recordId?: number) =>
        indexRoute.value && recordId
            ? window.route([indexRoute.value, "destroy"].join("."), {
                  [resource]: recordId,
              })
            : undefined;
    const getEditManyRoute = (ids: number[]) =>
        indexRoute.value
            ? window.route([indexRoute.value, "edit-many"].join("."), {
                  ids,
              })
            : undefined;
    const getDestroyManyRoute = (ids: number[]) =>
        indexRoute.value
            ? window.route([indexRoute.value, "destroy-many"].join("."), {
                  ids,
              })
            : undefined;

    return {
        indexRoute,
        createRoute,
        getDestroyRoute,
        getEditManyRoute,
        getDestroyManyRoute,
        getEditRoute,
        getShowRoute,
        getUpdateRoute,
        storeRoute,
    };
}

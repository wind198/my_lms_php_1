import { usePage } from "@inertiajs/vue3";
import { PageProps } from "../types";
import { IHasResource } from "../types/common/has-resource.type";

export default function useResource() {
    const page = usePage<PageProps & IHasResource>();

    const resource: string = page.props.resource;
    const resourcePlural: string =
        page.props.resource_plural || page.props.resource + "s";

    return { resource, resourcePlural };
}

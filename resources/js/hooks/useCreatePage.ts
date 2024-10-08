import { usePage } from "@inertiajs/vue3";
import { ICreatePageProps } from "../types/create-page.type";
import { IEntity } from "../types/entities/entity.type";

export default function useCreatePage<T extends IEntity>() {
    const page = usePage<ICreatePageProps<T>>();

    const isEdit = !!page.props.recordData;

    const recordData = page.props.recordData;

    const resource = page.props.resource;
    const resourcePlural =
        page.props.resource_plural || page.props.resource + "s";

    return { isEdit, recordData, resource, resourcePlural };
}

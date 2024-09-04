import { usePage } from "@inertiajs/vue3";
import { IShowPageProps } from "../types/show-page.type";

export default function useShowPage() {
    const page = usePage<IShowPageProps>();

    const recordData = page.props.recordData;

    const resource = page.props.resource;

    const primaryField = page.props.primaryField;

    return { recordData, primaryField, resource };
}

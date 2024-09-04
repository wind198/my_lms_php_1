import { usePage } from "@inertiajs/vue3";
import { removeTrailingSlash } from "../helper";

export default function useCheckLinkActive() {
    const page = usePage();

    const checkLinkActiveState = (path: string, strict = false): boolean => {
        if (path.startsWith("http")) {
            const urlObject = new URL(path);
            path = urlObject.pathname;
        }
        if (strict) {
            return path === page.url;
        } else {
            return path.startsWith(page.url);
        }
    };

    return { checkLinkActiveState };
}

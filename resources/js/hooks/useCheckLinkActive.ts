import { usePage } from "@inertiajs/vue3";
import { removeTrailingSlash } from "../helper";

export default function useCheckLinkActive() {
    const page = usePage();

    const checkLinkActiveState = (path: string, strict = false): boolean => {
        if (path.startsWith("http")) {
            const urlObject = new URL(path);
            path = urlObject.pathname;
        }
        const currentPathname = page.url.replace(/\/+$/, "");

        if (strict) {
            return path === currentPathname;
        } else {
            return path.startsWith(currentPathname);
        }
    };

    return { checkLinkActiveState };
}

import { textMap } from "./text";

export const DefaultTablePerPageItems = [10, 25, 50, 100];
export const DefaultTablePerPage = DefaultTablePerPageItems[0];
export const DefaultTableOrderBy = "created_at";

export const MenuLinkClasses = ["text-blue-accent-4", "_menu-link"];
export const AppLinkClasses = ["text-blue-darken-3", "text-decoration-none"];
export const NavLinkClasses = ["text-black", "_menu-link", "text-body-2"];

export const EducationBackgroundList = [
    "high_school",
    "bachelor",
    "master",
    "phd",
] as const;

export const eduBgSelectItems = EducationBackgroundList.map((i) => ({
    value: i,
    title: textMap.nouns[i],
}));

export const GenderOptionList = ["male", "female"] as const;
export const GenderSelectItems = GenderOptionList.map((i) => ({
    value: i,
    title: textMap.nouns[i],
}));

export const LIST_TABLE_COLUMNS_SHOWN = "list_table_columns_shown";

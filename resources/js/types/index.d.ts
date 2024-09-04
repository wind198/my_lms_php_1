import { Config } from "ziggy-js";
import { EducationBackgroundList } from "../constants";

export const IEducationBackground = typeof EducationBackgroundList[number];

export interface User {
    full_name: string;
    first_name: string;
    last_name: string;
    email: string;
    password: string; // Optional because password may not be needed on the frontend
    user_type: IUserType;
    created_at: string; // Assuming dates are in ISO string format
    updated_at?: string; // Assuming dates are in ISO string format
    note?: string;
    phone?: string;
    address?: string;
    education_background?: IEducationBackground;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>
> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
};

declare
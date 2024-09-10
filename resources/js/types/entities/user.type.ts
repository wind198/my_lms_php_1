import { EducationBackgroundList, GenderOptionList } from "../../constants";
import type { IEntity } from "./entity.type";
import { IGeneration } from "./generation.type";

export type IUserType = "student" | "teacher" | "staff";

export type IEducationBackground = (typeof EducationBackgroundList)[number];

export type IGender = (typeof GenderOptionList)[number];

export type IUser = IEntity & {
    first_name: string;
    last_name: string;
    full_name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    phone?: string | null;
    address?: string | null;
    note?: string | null;
    user_type: IUserType;
    education_background: IEducationBackground;
    generation_id?: number;
    generation?: IGeneration;
    dob: string;
    gender: IGender;
};

import { EducationBackgroundList } from "../../constants";
import { ICourse } from "./course.type";
import { IEntityDescriptive } from "./entity-descriptive.type";
import type { IEntity } from "./entity.type";
import { ITimeStamp } from "./timestamp.type";
import { IUser } from "./user.type";

export type IKclass = IEntity &
    ITimeStamp &
    IEntityDescriptive & {
        code: string;
        course_id: number;
        course?: ICourse;
        main_teacher_id: number;
        mainTeacher?: IUser;
    };

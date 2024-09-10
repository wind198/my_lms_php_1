import { EducationBackgroundList } from "../../constants";
import { IEntityDescriptive } from "./entity-descriptive.type";
import type { IEntity } from "./entity.type";
import { IMajor } from "./major.type";

export type ICourse = IEntity &
    IEntityDescriptive & { major_id?: number; major?: IMajor };

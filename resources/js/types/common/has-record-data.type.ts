import { InjectionKey } from "vue";
import { IEntity } from "../entities/entity.type";

export type IHasRecordData<T = IEntity> = {
    recordData: T;
};

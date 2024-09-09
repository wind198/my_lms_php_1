import { PageProps } from ".";
import { IHasRecordData } from "./common/has-record-data.type";
import { IHasResource } from "./common/has-resource.type";

export type ICreatePageProps<T> = PageProps & IHasResource & IHasRecordData<T>;

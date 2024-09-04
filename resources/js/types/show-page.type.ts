import { PageProps } from ".";
import { IHasPrimaryField } from "./common/has-primary-field.type";
import { IHasRecordData } from "./common/has-record-data.type";
import { IHasResource } from "./common/has-resource.type";

export type IShowPageProps = PageProps & IHasResource & IHasRecordData & IHasPrimaryField

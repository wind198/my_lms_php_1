import type { IOrder } from "./order.type";

export type IServerTableParams<T> = {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    next_page_url: string;
    prev_page_url: string;
    order: IOrder;
    order_by: keyof T;
    filters?: Record<string, any>;
};

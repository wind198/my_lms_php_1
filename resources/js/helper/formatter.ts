import dayjs from "dayjs";

export const dateFormater = {
    standard(v: any) {
        if (!v) {
            return "";
        }

        return dayjs(v).format("DD/MM/YYYY");
    },
};
export const datetimeFormater = {
    standard(v: any) {
        if (!v) {
            return "";
        }

        return dayjs(v).format("DD/MM/YYYY HH:mm");
    },
};

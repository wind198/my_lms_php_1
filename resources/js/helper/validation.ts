import { string } from "yup";
import { textMap, translateFromJson } from "../constants/text";

export const validationRules = {
    required:
        (msg: string = textMap.validations.required) =>
        (value: any) => {
            if (!value && value !== 0) return msg;
            if (typeof value === "string" && !value.trim()) return msg;
            return true;
        },
    email:
        (msg: string = textMap.validations.email) =>
        (value: string) => {
            if (typeof value !== "string") {
                return msg;
            }
            const emailValidation = string().email(msg);
            try {
                emailValidation.validateSync(value);
                return true;
            } catch (error) {
                console.error(error);

                return msg;
            }
        },
    min: (min: number, msg?: string) => (value: number) => {
        msg = msg || textMap.validations.min({ min });
        if (typeof value !== "number") {
            return msg;
        }
        return value >= min || msg;
    },
    minLength: (minLength: number, msg?: string) => (value: string) => {
        msg = msg || textMap.validations.minLength({ minLength });
        if (typeof value !== "string") {
            return msg;
        }
        return value.length >= minLength || msg;
    },
    max: (max: number, msg?: string) => (value: number) => {
        msg = msg || textMap.validations.max({ max });
        if (typeof value !== "number") {
            return msg;
        }
        return value <= max || msg;
    },
    maxLength: (maxLength: number, msg?: string) => (value: string) => {
        msg = msg || textMap.validations.maxLength({ maxLength });
        if (typeof value !== "string") {
            return msg;
        }
        return value.length <= maxLength || msg;
    },
};

export const processBackendErrorMsg = (msg: string) => {
    if (!msg) return msg;
    if (msg.includes("These credentials do not match our records")) {
        return textMap.validations.notCorrect({
            item: textMap.nouns.email_or_password,
        });
    }
    try {
        const jsonMsg = JSON.parse(msg);
        const translatedMsg = translateFromJson(jsonMsg)
        return translatedMsg;
    } catch (error) {
        console.error(error);
        return msg;
    }
};

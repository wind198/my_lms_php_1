import { camelCase, reverse, shuffle, snakeCase, uniq } from "lodash-es";
import { boolean } from "yup";

export const concatClasses = (classes: string[]) =>
    uniq([...classes].filter(Boolean).reverse())
        .reverse()
        .join(" ");

export const isNullOrUndefined = (v: any) => v === undefined || v === null;

export const removeValueFromObject = (
    input: Record<string, any>,
    removeIterator: (v: any, k: string) => boolean
) => {
    const output: Record<string, any> = {};

    for (const k in input) {
        if (Object.prototype.hasOwnProperty.call(input, k)) {
            const element = input[k];

            // Skip if the removeIterator function returns true
            if (removeIterator(element, k)) {
                continue;
            }

            // Handle primitives, arrays, and null
            if (
                element === null ||
                Array.isArray(element) ||
                ["string", "number", "boolean"].includes(typeof element) ||
                element instanceof Date
            ) {
                output[k] = element;
                continue;
            }

            // Handle nested objects (recursion)
            if (typeof element === "object") {
                const nestedObject = removeValueFromObject(
                    element,
                    removeIterator
                );

                // Only add the nested object if it's not empty
                if (Object.keys(nestedObject).length > 0) {
                    output[k] = nestedObject;
                }
                continue;
            }
        }
    }

    return output;
};

export function generateRandomPassword(): string {
    const upperCaseLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const lowerCaseLetters = "abcdefghijklmnopqrstuvwxyz";
    const numbers = "0123456789";
    const symbols = '!@#$%^&*(),.?":{}|<>';

    const shuffledGroups = shuffle(
        [upperCaseLetters, lowerCaseLetters, numbers, symbols].map((i) =>
            i.split("")
        )
    );

    const output = [] as string[];

    for (let i = 0; i < 8; i++) {
        const group = shuffledGroups[i % shuffledGroups.length];
        output.push(shuffle(group)[0]);
    }
    return output.join("");
}

export function removeTrailingSlash(url: string) {
    return url.endsWith("/") ? url.slice(0, -1) : url;
}

export const keysToSnake = (input) => {
    if (typeof input !== "object" || input === null) {
        return input;
    }

    if (Array.isArray(input)) {
        return input.map(keysToSnake);
    }

    const output = {};
    for (const key in input) {
        if (Object.prototype.hasOwnProperty.call(input, key)) {
            const camelCaseKey = snakeCase(key);
            let element = input[key];

            // Recursively apply keysToCamel if the element is an object or an array
            if (element && typeof element === "object") {
                element = keysToSnake(element);
            }

            output[camelCaseKey] = element;
        }
    }

    return output;
};

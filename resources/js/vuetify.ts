// resources/js/vuetify.js
import { createVuetify } from "vuetify";
import "vuetify/styles";
import { mdi } from "vuetify/iconsets/mdi";
import * as directives from "vuetify/directives";
import "@mdi/font/css/materialdesignicons.css";
export default createVuetify({
    icons: {
        defaultSet: "mdi",
        sets: {
            mdi,
        },
    },
    directives,
});

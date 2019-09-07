import Vue from "vue";
import store from "~/store";
import VueI18n from "vue-i18n";
import VeeValidate, { Validator } from "vee-validate";
import { customRule } from "~/helpers/customRule";
import enLocale from "element-ui/lib/locale/lang/en";
import viLocale from "element-ui/lib/locale/lang/vi";
import ElementLocale from "element-ui/lib/locale";

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: "vi",
    fallbackLocale: "en",
    messages: {},
    silentTranslationWarn: true
});

const config = {
    i18n: i18n
};

Vue.use(VeeValidate, config);

/**
 * @param {String} locale
 */
export async function loadMessages(locale) {
    let messages = {};
    const requireContext = require.context("~/lang/en", false, /.*\.js$/);
    requireContext.keys().forEach(e => {
        const content = require("~/lang/" + locale + "/" + e.substring(2))
            .default;
        const dict = {
            [e.substring(2).slice(0, -3)]: content
        };
        messages = { ...messages, ...dict };
    });
    if (locale == "vi") {
        messages = { ...messages, ...viLocale };
    } else if (locale == "en") {
        messages = { ...messages, ...enLocale };
    }
    i18n.setLocaleMessage(locale, messages);

    //setup for vee-validate
    const validateMessage = await import(`vee-validate/dist/locale/${locale}`);

    Validator.localize(locale, validateMessage);

    if (i18n.locale !== locale) {
        i18n.locale = locale;
    }

    customRule();
}

(async function() {
    await loadMessages(store.getters["lang/locale"]);
    ElementLocale.i18n((key, value) => i18n.t(key, value));
})();

export default i18n;

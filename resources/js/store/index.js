import Vue from "vue";
import Vuex from "vuex";
import createLogger from "vuex/dist/logger";

Vue.use(Vuex);

// Load store modules dynamically.
const requireContext = require.context("./modules", false, /.*\.js$/);
const debug = process.env.NODE_ENV !== "production";

const modules = requireContext
    .keys()
    .map(file => [file.replace(/(^.\/)|(\.js$)/g, ""), requireContext(file)])
    .reduce((modules, [name, module]) => {
        if (module.namespaced === undefined) {
            module.namespaced = true;
        }

        return { ...modules, [name]: module };
    }, {});

export default new Vuex.Store({
    modules,
    strict: debug,
    plugins: debug ? [createLogger()] : []
});

import store from "~/store";

export default async (to, from, next) => {
    if (!store.getters["auth/check"]) {
        // console.log(store)
        next("/login");
    } else {
        next();
    }
};

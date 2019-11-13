// import store from "~/store";
// import route from "~/router";
//
// export default (to, from, next) => {
//     let hasRole = true;
//     if (route.meta && route.meta.roles) {
//         const roles = route.meta.roles;
//         const userRoles = store.getters["auth/roles"];
//         if (Array.isArray(roles)) {
//             const intersection = userRoles.filter(n => {
//                 return roles.indexOf(n.name) !== -1;
//             });
//             if (intersection.length === 0) {
//                 hasRole = false;
//             }
//         } else {
//             const index = userRoles.findIndex(
//                 item => item.name === String(roles)
//             );
//             if (index === -1) {
//                 hasRole = false;
//             }
//         }
//     }
//     if (hasRole) {
//         next();
//     } else {
//         next({ name: "not_found" });
//     }
// };

import store from "~/store";
export default (to, from, next) => {
    let hasRole = true;
    const userRoles = store.getters["auth/roles"];
    to.matched.forEach(router => {
        if (router.meta && router.meta.roles && userRoles) {
            const roles = router.meta.roles;
            if (Array.isArray(roles)) {
                const intersection = userRoles.filter(n => {
                    return roles.indexOf(n.name) !== -1;
                });
                if (intersection.length === 0) {
                    hasRole = false;
                }
            } else {
                const index = userRoles.findIndex(
                    item => item.name === String(roles)
                );
                if (index === -1) {
                    hasRole = false;
                }
            }
        }
    });
    if (hasRole) {
        next();
    } else {
        next({ name: "not_found" });
    }
};

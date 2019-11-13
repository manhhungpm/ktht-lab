// import store from "~/store";
// import route from "~/router";
//
// export default (to, from, next) => {
//     let hasPermission = true;
//     if (route.meta && route.meta.permissions) {
//         const permissions = route.meta.permissions;
//         const userPermissions = store.getters["auth/permissions"];
//         if (Array.isArray(permissions)) {
//             const intersection = userPermissions.filter(n => {
//                 return permissions.indexOf(n.name) !== -1;
//             });
//             if (intersection.length === 0) {
//                 hasPermission = false;
//             }
//         } else {
//             const index = userPermissions.findIndex(
//                 item => item.name === String(permissions)
//             );
//             if (index === -1) {
//                 hasPermission = false;
//             }
//         }
//     }
//     if (hasPermission) {
//         next();
//     } else {
//         next({ name: "not_found" });
//     }
// };

import store from "~/store";
export default (to, from, next) => {
    let hasPermission = true;
    const userPermissions = store.getters["auth/permissions"];
    to.matched.forEach(router => {
        if (router.meta && router.meta.permissions && userPermissions) {
            const permissions = router.meta.permissions;
            if (Array.isArray(permissions)) {
                const intersection = userPermissions.filter(n => {
                    return permissions.indexOf(n.name) !== -1;
                });
                if (intersection.length === 0) {
                    hasPermission = false;
                }
            } else {
                const index = userPermissions.findIndex(
                    item => item.name === String(permissions)
                );
                if (index === -1) {
                    hasPermission = false;
                }
            }
        }
    });
    if (hasPermission) {
        next();
    } else {
        next({ name: "not_found" });
    }
};

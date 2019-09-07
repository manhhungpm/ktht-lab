import Vue from "vue";
import store from "~/store";

Vue.prototype.$hasRole = function(roles) {
    let hasRole = true;
    if (roles !== null && roles !== undefined) {
        const userRoles = store.getters["auth/roles"];
        if (userRoles !== null) {
            if (Array.isArray(roles)) {
                const intersection = userRoles.filter(n => {
                    return roles.indexOf(n.name) !== -1;
                });
                if (intersection.length === 0) {
                    hasRole = false;
                }
            } else {
                if (userRoles) {
                    const index = userRoles.findIndex(
                        item => item.name === String(roles)
                    );
                    if (index === -1) {
                        hasRole = false;
                    }
                }
            }
        }
    }
    return hasRole;
};

Vue.prototype.$can = function(permissions) {
    let hasPermission = true;
    if (permissions !== null && permissions !== undefined) {
        const userPermissions = store.getters["auth/permissions"];
        if (userPermissions !== null) {
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
    }
    return hasPermission;
};

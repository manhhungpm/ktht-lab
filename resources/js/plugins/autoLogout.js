import $ from "jquery";
import store from "~/store";
import moment from "moment";
import {
    TIME_NOT_ACTIVE_TO_LOGOUT,
    TIME_CHECK_ACTIVE_TO_LOGOUT
} from "../constants/code";

window.localStorage.setItem(
    "lastActive",
    moment().format("YYYY-MM-DD HH:mm:ss")
);

setTimeout(() => {
    if (store.getters["auth/check"]) {
        $(document).ready(function() {
            window.localStorage.setItem(
                "lastActive",
                moment().format("YYYY-MM-DD HH:mm:ss")
            );
            $(document).mousemove(function(event) {
                window.localStorage.setItem(
                    "lastActive",
                    moment().format("YYYY-MM-DD HH:mm:ss")
                );
                // console.log("a", window.localStorage.getItem("lastActive"));
            });
        });
    }
}, 2000);

setInterval(async function() {
    if (window.location.pathname != "/login") {
        // console.log(moment().format("YYYY-MM-DD HH:mm:ss"));
        if (
            moment(
                window.localStorage.getItem("lastActive"),
                "YYYY-MM-DD HH:mm:ss"
            )
                .add(TIME_NOT_ACTIVE_TO_LOGOUT, "seconds")
                .isSameOrBefore(moment())
        ) {
            if (store.getters["auth/check"]) {
                await store.dispatch("auth/logout");
                // window.location = "/login";
                window.location.reload();
                // console.log("login");
            } else {
                await window.location.reload();
                // console.log("reload");
            }
        }
    }
}, 1000 * TIME_CHECK_ACTIVE_TO_LOGOUT);

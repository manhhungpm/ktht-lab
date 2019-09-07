import axios from "axios";
import store from "~/store";
import router from "~/router";
import swal from "sweetalert2";
import i18n from "~/plugins/i18n";
import { notifyTryAgain, notify } from "~/helpers/bootstrap-notify";
import * as responseCode from "../constants/responseCode";

axios.defaults.baseURL = "/api";
// Request interceptor
axios.interceptors.request.use(request => {
    const token = store.getters["auth/token"];
    if (token) {
        request.headers.common["Authorization"] = `Bearer ${token}`;
    }

    const locale = store.getters["lang/locale"];
    if (locale) {
        request.headers.common["Accept-Language"] = locale;
    }

    // request.headers['X-Socket-Id'] = Echo.socketId()

    return request;
});

// Response interceptor
axios.interceptors.response.use(
    response => response,
    error => {
        const { status, data } = error.response;

        if (status >= 500) {
            if (data.code == responseCode.QUERY_ERROR) {
                notify(
                    "Thông báo",
                    "Dữ liệu không thể xóa do đang được sử dụng!"
                );
            } else {
                notifyTryAgain();
            }
        }

        if (status === 401 && store.getters["auth/check"]) {
            swal({
                type: "warning",
                title: i18n.t("alert.unauthen.title"),
                text: i18n.t("alert.unauthen.content"),
                reverseButtons: true,
                confirmButtonText: i18n.t("alert.button.ok"),
                cancelButtonText: i18n.t("alert.button.cancel")
            }).then(() => {
                store.commit("auth/LOGOUT");

                // window.location = "auth/sso-login";
            });
        }

        return Promise.reject(error);
    }
);

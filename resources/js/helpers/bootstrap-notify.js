import "bootstrap-notify";
import i18n from "~/plugins/i18n";

export const notify = (title, message, type = "danger", icon = null) => {
    let iconClass = "";

    if (icon == null) {
        switch (type) {
            case "danger":
                iconClass = "icon la la-exclamation-circle";
                break;
            case "success":
                iconClass = "icon la la-check";
                break;
            case "info":
                iconClass = "icon la la-info-circle";
                break;
            case "warning":
                iconClass = "icon la la-exclamation-triangle";
                break;
            default:
                break;
        }
    } else {
        iconClass = "icon " + icon;
    }

    $.notify(
        {
            title: title,
            message: message,
            icon: iconClass
        },
        {
            type: type,
            z_index: 99999,
            timer: 4000,
            animate: {
                enter: "animated fadeInDown",
                exit: "animated fadeOutDown"
            },
            template:
                '<div data-notify="container" class="m-alert alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss"></button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                "</div>" +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                "</div>"
        }
    );
};

export const notifyTryAgain = () =>
    notify(i18n.t("alert.notice"), i18n.t("alert.try_again"), "danger");
export const notifyTagSuccess = objectName =>
    notify(
        i18n.t("alert.notice"),
        i18n.t("alert.tag.tag_success", [objectName]),
        "success"
    );
export const notifyIgnoreSuccess = objectName =>
    notify(
        i18n.t("alert.notice"),
        i18n.t("alert.ignore.ignore_success", [objectName]),
        "success"
    );
export const notifyAddSuccess = objectName =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.add_success", [objectName]),
        "success"
    );
export const notifyUpdateSuccess = objectName =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.edit_success", [objectName]),
        "success"
    );
export const notifyDeleteSuccess = objectName =>
    notify(
        i18n.t("alert.notice"),
        i18n.t("notification.delete_success", [objectName]),
        "success"
    );
export const notifyExportFail = objectName =>
    notify(i18n.t("alert.notice"), "Không có bản ghi để xuất file", "danger");
export const notifyImportSuccess = objectName =>
    notify(
        i18n.t("alert.notice"),
        i18n.t("Import thành công", [objectName]),
        "success"
    );
export const notifyActiveSuccess = objectName =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.active_success", [objectName]),
        "success"
    );
export const notifyDisableSuccess = objectName =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.disable_success", [objectName]),
        "success"
    );
export const notifyLockSuccess = objectName =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.lock_success", [objectName]),
        "success"
    );
export const notifyUnLockSuccess = objectName =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.lock_success", [objectName]),
        "success"
    );
export const notifyBypassSuccess = objectName =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.bypass_success", [objectName]),
        "success"
    );
export const notifyNoPermission = objectName =>
    notify(
        i18n.t("label.notification"),
        i18n.t("errors.not_permission", [objectName]),
        "danger"
    );
export const notifyNoRecord = () =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.must_select_at_least_one_record"),
        "danger"
    );
export const notifyGiveBackSuccess = () =>
    notify(
        i18n.t("label.notification"),
        i18n.t("notification.give_back_success"),
        "success"
    )

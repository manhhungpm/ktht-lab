import i18n from "~/plugins/i18n";

export const generateTableAction = (
    type,
    action,
    colorInput,
    iconInput,
    titleInput,
    text = "",
    typeIcon = "la"
) => {
    let color = "",
        icon = "",
        title = "";

    switch (type) {
        case "edit":
            color = "primary";
            icon = "la-edit";
            title = i18n.t("button.edit");
            break;
        case "delete":
            color = "danger";
            icon = "la-trash";
            title = "Xóa";
            break;
        case "ignore":
            color = "danger";
            icon = "la-times-circle-o";
            title = i18n.t("button.ignore");
            break;
        case "tag":
            color = "primary";
            icon = "la-tag";
            title = i18n.t("button.tag");
            break;
        case "disable":
            color = "danger";
            icon = "la-ban";
            title = i18n.t("button.disable");
            break;
        case "active":
            color = "success";
            icon = "la-check-circle";
            title = i18n.t("button.active");
            break;
        case "refreshKey":
            color = "accent";
            icon = "la-refresh";
            title = i18n.t("button.reset_key");
            break;
        case "stop":
            color = "danger";
            icon = "la-stop";
            title = "Stop";
            break;
        case "start":
            color = "warning";
            icon = "la-play";
            title = "Start";
            break;
        case "download":
            color = "accent";
            icon = "la-download";
            title = "Download";
            break;
        case "showHistory":
            color = "warning";
            icon = "la-history";
            title = i18n.t("button.show_history");
            break;
        default:
            color = colorInput;
            icon = iconInput;
            title = titleInput;
            break;
    }

    if (text == "") {
        return (
            '<a href="javascript:;" data-action="' +
            action +
            '" class="m-portlet__nav-link btn m-btn m-btn--hover-' +
            color +
            ' m-btn--icon m-btn--icon-only m-btn--pill table-action" title="' +
            title +
            '">\n' +
            '   <i class="' +
            typeIcon +
            " " +
            icon +
            '"></i>\n' +
            "</a>"
        );
    } else {
        return (
            '<a href="javascript:;" data-action="' +
            action +
            '" class="m-portlet__nav-link btn m-btn m-btn--pill table-action" title="' +
            title +
            '" style="text-decoration: underline;">\n' +
            `${htmlEscapeEntities(text)}` +
            "</a>"
        );
    }
};

export const htmlEscapeEntities = function(d) {
    return typeof d === "string"
        ? d
              .replace(/</g, "&lt;")
              .replace(/>/g, "&gt;")
              .replace(/"/g, "&quot;")
        : d;
};

export const reloadIntelligently = table => {
    table.reload();
};

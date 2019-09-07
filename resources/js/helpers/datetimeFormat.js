import moment from "moment";

export const toNormalDate = datetimeStr => {
    if (moment(datetimeStr, "DD/MM/YYYY").isValid()) return datetimeStr;
    else return moment(datetimeStr, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY");
};

export const isValidDate = datetimeStr => {
    return moment(datetimeStr).isValid();
};

import moment from "moment";
import numeral from "numeral";

export const formatNumber = numberStr => {
    return numeral(numberStr).format("0,0.[00]");
};

export const toNormalDate = datetimeStr => {
    if (moment(datetimeStr, "DD/MM/YYYY").isValid()) return datetimeStr;
    else
        return moment(datetimeStr, "YYYY-MM-DD HH:mm:ss.u").format(
            "DD/MM/YYYY"
        );
};

export const isValidDate = datetimeStr => {
    return moment(datetimeStr).isValid();
};

export const formatDate = datetimeStr => {
    if (moment(datetimeStr, "DD/MM/YYYY").isValid()) return datetimeStr;
    else
        return moment(datetimeStr, "YYYY-MM-DD HH:mm:ss.u").format(
            "DD/MM/YYYY HH:mm:ss"
        );
};

import {
    PASSWORD_REGEX,
    PHONE_REGEX,
    USER_NAME_REGEX
} from "../constants/regex";
import VeeValidate, { Validator } from "vee-validate";
import moment from "moment";
import i18n from "~/plugins/i18n";

export function customRule() {
    VeeValidate.Validator.extend("isPassword", {
        getMessage: "",
        validate: function(value) {
            return PASSWORD_REGEX.test(value);
        }
    });

    VeeValidate.Validator.extend("isUsername", {
        getMessage: "",
        validate: function(value) {
            return USER_NAME_REGEX.test(value);
        }
    });

    VeeValidate.Validator.extend("isPhone", {
        getMessage: field => {
            return `${field} không phải là số điện thoại. Vui lòng nhập lại`;
        },
        validate: function(value) {
            return PHONE_REGEX.test(value);
        }
    });

    VeeValidate.Validator.extend(
        "isGte",
        {
            validate: (value, [otherValue]) => {
                return parseInt(value) >= parseInt(otherValue);
            },
            getMessage: (field, [target]) => {
                return `${field} phải lớn hơn hoặc bằng ${target}`;
            }
        },
        {
            hasTarget: true
        }
    );

    /**
     * The field is within x (unit) after target field
     *  targetObj:field Ref
     *      unit: ['days','months','years',..]
     *      value: int
     *
     */
    VeeValidate.Validator.extend(
        "afterWithinAMonth",
        {
            validate: (value, [otherValue]) => {
                if (otherValue != null && value != null) {
                    return moment(value, "DD/MM/YYYY").isSameOrBefore(
                        moment(otherValue, "DD/MM/YYYY").add(31, "days")
                    );
                }
                return true;
            },
            getMessage: (field, [otherValue]) => {
                return `${field} phải sau không quá 31 ngày so với ${otherValue}`;
            }
        },
        {
            hasTarget: true
        }
    );

    VeeValidate.Validator.extend("afterWithinAWeek", {
        validate: value => {
            if (value[0] && value[1]) {
                // console.log(moment(value[0]))
                return (
                    moment(value[1], "YYYY-MM-DD HH:mm:ss").isSameOrBefore(
                        moment(value[0], "YYYY-MM-DD HH:mm:ss").add(7, "days")
                    ) &&
                    moment(value[1], "YYYY-MM-DD HH:mm:ss").isSameOrAfter(
                        moment(value[0], "YYYY-MM-DD HH:mm:ss")
                    )
                );
            }
            return true;
        },
        getMessage: () => {
            return `Khoảng thời gian không được lớn hơn 7 ngày`;
        }
    });

    VeeValidate.Validator.extend("beforeToday", {
        validate: value => {
            if (value[0] && value[1]) {
                // console.log(moment(value[0]))
                return moment(value[1], "YYYY-MM-DD HH:mm:ss").isBefore(
                    moment().startOf("day")
                );
            }
            return true;
        },
        getMessage: () => {
            return `Khoảng thời gian phải trước ngày hôm nay`;
        }
    });

    VeeValidate.Validator.extend(
        "afterWithinMonths",
        {
            validate: (value, [otherValue]) => {
                if (otherValue != null && value != null) {
                    return moment(value, "MM/YYYY").isSameOrBefore(
                        moment(otherValue, "MM/YYYY").add(12, "months")
                    );
                }
                return true;
            },
            getMessage: (field, [params]) => {
                return `${field} phải sau không quá 12 tháng so với ${params}`;
            }
        },
        {
            hasTarget: true
        }
    );

    VeeValidate.Validator.extend(
        "afterDate",
        {
            validate: (value, [otherValue]) => {
                if (otherValue != null && otherValue != "") {
                    return (
                        moment(value, "DD/MM/YYYY").isAfter(
                            moment(otherValue, "DD/MM/YYYY")
                        ) ||
                        moment(value, "MM/YYYY").isAfter(
                            moment(otherValue, "MM/YYYY")
                        ) ||
                        value == otherValue
                    );
                }
                return true;
            },
            getMessage: (field, [target]) => {
                return `${field} phải sau hoặc bằng ${target}`;
            }
        },
        {
            hasTarget: true
        }
    );

    VeeValidate.Validator.extend("requiredFormRangeControl", {
        getMessage: field => {
            return `${field} ${i18n.t("rule.required_form_range_control")}`;
        },
        validate: function(value) {
            if (value) {
                if (value.from && value.to) {
                    return true;
                } else {
                    return false;
                }
            } else return false;
        }
    });

    VeeValidate.Validator.extend("toGreaterOrEqualFromFormRangeControl", {
        getMessage: function() {
            return `${i18n.t("rule.to_greater_from_form_range_control")}`;
        },
        validate: function(value) {
            if (value) {
                if (value.from && value.to) {
                    if (value.from <= value.to) {
                        return true;
                    } else return false;
                }
            } else return true;
        }
    });

    VeeValidate.Validator.extend(
        "fromAfterIsGreaterToBeforeFormRangeControl",
        {
            validate: (value, [otherValue]) => {
                if (value && otherValue) {
                    if (value.from && otherValue.to) {
                        if (value.from <= otherValue.to) {
                            return false;
                        }
                    }
                }
                return true;
            },
            getMessage: (field, [target]) => {
                return `Giá trị bắt đầu của ${field} phải lớn hơn giá trị đến của ${target}`;
            }
        },
        {
            hasTarget: true
        }
    );

    const dictionary = {
        vi: {
            messages: {
                isPassword:
                    "Mật khẩu phải bao gồm chữ hoa, chữ thường,số, kí tự đặc biệt và tối thiểu 8 kí tự",
                isUsername:
                    "Tên người dùng chỉ sử dụng chữ cái không dấu hoặc chữ số và không được bắt đầu bằng chữ số",
                decimal: (field, [target]) =>
                    `${field} phải là số thập phân và chỉ chứa ${target} số sau dấu .`,
                after: (field, [target]) => `${field} phải sau ${target}`,
                before: (field, [target]) => `${field} phải trước ${target}`
            }
        },
        en: {
            messages: {
                isPassword:
                    "Passwords must include uppercase, lowercase, numeric, special characters, and a minimum of 8 characters",
                isUsername:
                    "User names are only alphanumeric or numeric and may not begin with a digit"
            }
        }
    };

    VeeValidate.Validator.localize(dictionary);
}

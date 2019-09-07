<template>
    <div
        class="form-group m-form__group"
        :class="{ 'has-danger': error, row: inline }"
    >
        <label v-if="label" :class="{ 'col-3 pt-md-2': inline }"
            >{{ label }}
            <span v-if="required" class="text-danger">(*)</span></label
        >
        <div :class="{ 'col-9': inline }">
            <el-date-picker
                :key="name"
                v-model="time"
                class="date-range-picker-full-width"
                style="width:100%"
                :type="type"
                :range-separator="$t('component.date_picker.range_separator')"
                :start-placeholder="
                    $t('component.date_picker.start_placeholder')
                "
                :end-placeholder="$t('component.date_picker.end_placeholder')"
                :format="format"
                :value-format="valueFormat"
                :picker-options="pickerOptions"
                :default-time="defaultTime"
            >
            </el-date-picker>

            <div
                v-if="error"
                class="form-control-feedback"
                style="margin-top: 8px;"
            >
                {{ error }}
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment";

export default {
    name: "TheDateRange",
    props: {
        value: {
            type: [String, Array]
        },
        type: {
            type: String,
            default: "daterange"
        },
        format: {
            type: String,
            default: "dd/MM/yyyy"
        },
        valueFormat: {
            type: String,
            default: "yyyy-MM-dd HH:mm:ss"
        },
        label: {
            type: String,
            default: null
        },
        inline: {
            type: Boolean,
            default: true
        },
        name: {
            type: String,
            default: null
        },
        required: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: null
        },
        shortcut: {
            type: Boolean,
            default: false
        },
        //hung
        nameShortcut: {
            type: Array,
            default: () => {}
        },
        disabledDate: {
            type: String, //greaterThanToday,lessThanToday
            default: null,
            validator: function(value) {
                return (
                    ["greaterThanToday", "lessThanToday"].indexOf(value) !== -1
                );
            }
        },
        defaultTime: {
            type: Array,
            default: () => {
                return ["00:00:00", "23:59:59"];
            }
        }
    },
    data() {
        return {
            time: this.value,
            disableDate: this.disabledDate
        };
    },
    computed: {
        pickerOptions() {
            var pickerOpt = {};

            //Shortcuts
            if (this.shortcut) {
                var shortcuts = [];
                const arr = this.nameShortcut;

                arr.forEach(function(element) {
                    switch (element) {
                        case "today":
                            shortcuts.push({
                                text: "Hôm nay",
                                onClick(picker) {
                                    const end = moment()
                                        .endOf("day")
                                        .toDate();
                                    const start = moment()
                                        .startOf("day")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "yesterday":
                            shortcuts.push({
                                text: "Hôm qua",
                                onClick(picker) {
                                    const end = moment()
                                        .subtract(1, "days")
                                        .endOf("day")
                                        .toDate();
                                    const start = moment()
                                        .subtract(1, "days")
                                        .startOf("day")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "last_7_days":
                            shortcuts.push({
                                text: "7 ngày gần đây",
                                onClick(picker) {
                                    const end = moment().toDate();
                                    const start = moment()
                                        .subtract(1, "weeks")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "last_30_days":
                            shortcuts.push({
                                text: "30 ngày gần đây",
                                onClick(picker) {
                                    const end = moment().toDate();
                                    const start = moment()
                                        .subtract(1, "months")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "this_month":
                            shortcuts.push({
                                text: "Tháng này",
                                onClick(picker) {
                                    const end = moment()
                                        .endOf("month")
                                        .toDate();
                                    const start = moment().toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "last_month":
                            shortcuts.push({
                                text: "Tháng trước",
                                onClick(picker) {
                                    const end = moment()
                                        .subtract(1, "months")
                                        .endOf("month")
                                        .toDate();
                                    const start = moment()
                                        .subtract(1, "months")
                                        .startOf("month")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "next_month":
                            shortcuts.push({
                                text: "Tháng sau",
                                onClick(picker) {
                                    const end = moment()
                                        .add(1, "months")
                                        .endOf("month")
                                        .toDate();
                                    const start = moment()
                                        .add(1, "months")
                                        .startOf("month")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "next_30_days":
                            shortcuts.push({
                                text: "30 ngày tiếp",
                                onClick(picker) {
                                    const start = moment()
                                        .add(1, "days")
                                        .toDate();
                                    const end = moment()
                                        .add(30, "days")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "next_7_days":
                            shortcuts.push({
                                text: "7 ngày tiếp",
                                onClick(picker) {
                                    const start = moment()
                                        .add(1, "days")
                                        .toDate();
                                    const end = moment()
                                        .add(1, "weeks")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "tomorrow":
                            shortcuts.push({
                                text: "Ngày mai",
                                onClick(picker) {
                                    const end = moment()
                                        .add(1, "days")
                                        .endOf("day")
                                        .toDate();
                                    const start = moment()
                                        .add(1, "days")
                                        .startOf("day")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        default:
                            break;
                    }
                });

                pickerOpt.shortcuts = shortcuts;
            }

            //Disable date
            if (this.disableDate != null) {

                switch (this.disableDate) {
                    case "greaterThanToday":
                        pickerOpt.disabledDate = function(date) {
                            var day = new Date();
                            return date > day;
                        };
                        break;
                    case "lessThanToday":
                        pickerOpt.disabledDate = function(date) {
                            var day = new Date();
                            day.setDate(day.getDate() - 1);
                            return date < day;
                        };
                        break;
                }
            }
            return pickerOpt;
        }
    },
    watch: {
        time(val) {
            this.$emit("input", val);
        },
        value: {
            handler(newVal, oldVal) {
                if (newVal != oldVal) {
                    this.time = newVal;
                }
            },
            deep: true
        }
    },
    mounted() {},
    $_veeValidate: {
        value() {
            return this.value;
        },
        name() {
            return this.name;
        }
    }
};
</script>

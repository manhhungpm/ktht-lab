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
                range-separator="-"
                :start-placeholder="$t('label.month_start')"
                :end-placeholder="$t('label.month_end')"
                :format="format"
                :value-format="valueFormat"
                :picker-options="pickerOptions"
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
<style>
.time-filter {
    margin: auto;
    margin-bottom: 15px;
}
</style>
<script>
import moment from "moment";

export default {
    name: "MonthRange",
    props: {
        value: {
            type: [String, Array]
        },
        inline: {
            type: Boolean,
            default: true
        },
        type: {
            type: String,
            default: "monthrange"
        },
        format: {
            type: String,
            default: "MM/yyyy"
        },
        valueFormat: {
            type: String,
            default: "yyyy-MM-dd"
        },
        label: {
            type: String,
            default: null
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
        }
        // defaultTime: {
        //     type: Array,
        //     default: () => {
        //         return ["00:00:00", "23:59:59"];
        //     }
        // }
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
            const $this = this;
            //Shortcuts
            if (this.shortcut) {
                var shortcuts = [];
                const arr = this.nameShortcut;

                arr.forEach(function(element) {
                    switch (element) {
                        case "this_year":
                            shortcuts.push({
                                text: $this.$t(
                                    "component.date_picker.short_cut.this_year"
                                ),
                                onClick(picker) {
                                    const end = moment()
                                        .endOf("year")
                                        .startOf("month")
                                        .toDate();
                                    const start = moment()
                                        .startOf("year")
                                        .toDate();
                                    picker.$emit("pick", [start, end]);
                                }
                            });
                            break;
                        case "last_12_months":
                            shortcuts.push({
                                text: $this.$t(
                                    "component.date_picker.short_cut.last_12_months"
                                ),
                                onClick(picker) {
                                    const end = moment()
                                        .startOf("month")
                                        .subtract(1, "months")
                                        .toDate();
                                    const start = moment()
                                        .startOf("month")
                                        .subtract(12, "months")
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
            // if (this.disableDate != null) {
            //     switch (this.disableDate) {
            //         case "greaterThanToday":
            //             pickerOpt.disabledDate = function(date) {
            //                 var day = new Date();
            //                 return date > day;
            //             };
            //             break;
            //         case "lessThanToday":
            //             pickerOpt.disabledDate = function(date) {
            //                 var day = new Date();
            //                 day.setDate(day.getDate() - 1);
            //                 return date < day;
            //             };
            //             break;
            //     }
            // }
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

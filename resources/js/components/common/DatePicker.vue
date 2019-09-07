<template>
    <input
        ref="input"
        type="text"
        class="form-control m-input"
        :placeholder="placeholder"
        autocomplete="off"
    />
</template>
<script>
import "bootstrap-datepicker";
import moment from "moment";

export default {
    name: "DatePicker",
    props: {
        value: {
            type: String
        },
        autoclose: {
            type: Boolean,
            default: true
        },
        language: {
            type: String,
            default: "vi"
        },
        clearBtn: {
            type: Boolean,
            default: true
        },
        todayHighlight: {
            type: Boolean,
            default: true
        },
        daysOfWeekDisabled: {
            type: Array,
            default: () => []
        },
        placeholder: {
            type: String,
            default: "Vui lòng chọn một ngày"
        },
        viewMode: {
            type: String,
            default: "days"
        },
        minViewMode: {
            type: String,
            default: "days"
        }
    },
    watch: {
        value: function(value) {
            $(this.$el).val(value);
            switch (this.minViewMode) {
                case "days":
                    $(this.$el).datepicker(
                        "setDate",
                        moment(value, "DD/MM/YYYY").toDate()
                    );
                    break;
                case "months":
                    $(this.$el).datepicker(
                        "setDate",
                        moment(value, "MM/YYYY").toDate()
                    );
                    break;
                case "years":
                    $(this.$el).datepicker(
                        "setDate",
                        moment(value, "YYYY").toDate()
                    );
                    break;
            }
        },
        language() {
            $(this.$el).datepicker("destroy");
            this.init();
        }
    },

    created() {
        switch (this.minViewMode) {
            case "days":
                this.multiLang("dd/mm/yyyy");
                break;
            case "months":
                this.multiLang("mm/yyyy");
                break;
            case "years":
                this.multiLang("yyyy");
                break;
        }
    },
    mounted() {
        this.init();
        this.listenEvent();
    },

    destroyed: function() {
        $(this.$el).datepicker("destroy");
    },

    methods: {
        multiLang(format) {
            $.fn.datepicker.dates["en"] = {
                days: [
                    "Sunday",
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday"
                ],
                daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                months: [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                ],
                monthsShort: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"
                ],
                today: "Today",
                clear: "Clear",
                format: format,
                titleFormat: "MM yyyy" /* Leverages same syntax as 'format' */,
                weekStart: 0
            };
            $.fn.datepicker.dates["vi"] = {
                days: [
                    "Chủ nhật",
                    "Thứ Hai",
                    "Thứ Ba",
                    "Thứ Tư",
                    "Thứ Năm",
                    "Thứ Sáu",
                    "Thứ Bảy"
                ],
                daysShort: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
                daysMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
                months: [
                    "Tháng 1",
                    "Tháng 2",
                    "Tháng 3",
                    "Tháng 4",
                    "Tháng 5",
                    "Tháng 6",
                    "Tháng 7",
                    "Tháng 8",
                    "Tháng 9",
                    "Tháng 10",
                    "Tháng 11",
                    "Tháng 12"
                ],
                monthsShort: [
                    "Th1",
                    "Th2",
                    "Th3",
                    "Th4",
                    "Th5",
                    "Th6",
                    "Th7",
                    "Th8",
                    "Th9",
                    "Th10",
                    "Th11",
                    "Th12"
                ],
                today: "Hôm nay",
                clear: "Xóa",
                format: format,
                titleFormat: "MM, yyyy" /* Leverages same syntax as 'format' */,
                weekStart: 0
            };
        },
        init() {
            $(this.$el)
                .datepicker({
                    language: this.language,
                    clearBtn: this.clearBtn,
                    todayHighlight: this.todayHighlight,
                    daysOfWeekDisabled: this.daysOfWeekDisabled,
                    viewMode: this.viewMode,
                    minViewMode: this.minViewMode,
                    autoclose: this.autoclose
                })
                .on("changeDate", e => {
                    this.$emit("input", $(this.$el).val());
                });
            if (this.value) {
                $(this.$el).val(this.value);
                switch (this.minViewMode) {
                    case "days":
                        $(this.$el).datepicker(
                            "setDate",
                            moment(this.value, "DD/MM/YYYY").toDate()
                        );
                        break;
                    case "months":
                        $(this.$el).datepicker(
                            "setDate",
                            moment(this.value, "MM/YYYY").toDate()
                        );
                        break;
                    case "years":
                        $(this.$el).datepicker(
                            "setDate",
                            moment(this.value, "YYYY").toDate()
                        );
                        break;
                }
            }
        },
        listenEvent() {
            $(this.$el)
                .datepicker()
                .on("clearDate", () => {
                    $(this.$el).val("");
                    this.$emit("input", $(this.$el).val());
                });
            $(this.$el).on("change", () => {
                this.$emit("input", $(this.$el).val());
            });
        }
    }
};
</script>

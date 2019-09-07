<template>
    <modal
        ref="modal"
        :title="
            isEdit
                ? $t('brandname.config.time_warning.edit')
                : $t('brandname.config.time_warning.add')
        "
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <div class="row">
                <sms-group-chosen
                    v-model="form.sms_group"
                    v-validate="'required'"
                    class="col-6"
                    :multiple="false"
                    :error="errors.first('smsGroup')"
                    :has-all-option="true"
                ></sms-group-chosen>

                <sms-type-chosen
                    v-model="form.sms_type_result"
                    v-validate="'required'"
                    class="col-6"
                    :multiple="false"
                    :sms-group-id="
                        form.sms_group && form.sms_group.id
                            ? form.sms_group.id
                            : null
                    "
                    :is-disabled="
                        form.sms_group && form.sms_group.id ? false : true
                    "
                    :error="
                        errors.first('smsType') || form.errors.get('type_id')
                    "
                ></sms-type-chosen>

                <div class="col-12">
                    <the-date-range
                        v-model="form.apply_time"
                        v-validate="'required'"
                        name="apply_time"
                        :inline="false"
                        :data-vv-as="
                            $t('brandname.config.time_warning.apply_time')
                        "
                        :label="$t('brandname.config.time_warning.apply_time')"
                        :error="
                            errors.first('apply_time') ||
                                form.errors.get('apply_time')
                        "
                        :required="true"
                        :name-shortcut="[
                            'this_month',
                            'next_month',
                            'next_30_days',
                            'next_7_days',
                            'tomorrow'
                        ]"
                        :shortcut="true"
                        :type="'datetimerange'"
                        :format="'dd/MM/yyyy HH:mm:ss'"
                        :disabled-date="'lessThanToday'"
                    >
                    </the-date-range>
                </div>

                <div class="col-12" style="margin-top: 15px">
                    <week-day-picker
                        v-model="form.day"
                        v-validate="'required'"
                        :required="true"
                        :label="$t('label.select_day')"
                        name="day"
                        :error="
                            errors.first('day') || form.errors.get('week_day')
                        "
                        :data-vv-as="'Ngày'"
                    ></week-day-picker>
                </div>

                <form-range-control
                    ref="highWarning"
                    v-model="form.high"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl'
                    "
                    :number-format="true"
                    class="col-12"
                    :label="$t('brandname.config.time_warning.high_warning')"
                    :data-vv-as="
                        $t('brandname.config.time_warning.high_warning')
                    "
                    name="high"
                    :inline="true"
                    :error="errors.first('high') || form.errors.get('high')"
                    append="true"
                    :required="true"
                    :placeholder-from="$t('brandname.config.time_warning.from')"
                    :placeholder-to="$t('brandname.config.time_warning.to')"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        ({{ $t("brandname.config.time_warning.minute") }})
                    </div>
                </form-range-control>

                <form-range-control
                    ref="dangerWarning"
                    v-model="form.danger"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl|fromAfterIsGreaterToBeforeFormRangeControl:highWarning'
                    "
                    :number-format="true"
                    class="col-12"
                    :label="$t('brandname.config.time_warning.danger_warning')"
                    :data-vv-as="
                        $t('brandname.config.time_warning.danger_warning')
                    "
                    name="danger"
                    :inline="true"
                    :error="errors.first('danger') || form.errors.get('danger')"
                    append="true"
                    :required="true"
                    :placeholder-from="$t('brandname.config.time_warning.from')"
                    :placeholder-to="$t('brandname.config.time_warning.to')"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        ({{ $t("brandname.config.time_warning.minute") }})
                    </div>
                </form-range-control>

                <form-range-control
                    ref="crisisWarning"
                    v-model="form.crisis"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl|fromAfterIsGreaterToBeforeFormRangeControl:dangerWarning'
                    "
                    :number-format="true"
                    class="col-12"
                    :label="$t('brandname.config.time_warning.crisis_warning')"
                    :data-vv-as="
                        $t('brandname.config.time_warning.high_warning')
                    "
                    name="crisis"
                    :inline="true"
                    :error="errors.first('crisis') || form.errors.get('crisis')"
                    append="true"
                    :required="true"
                    :placeholder-from="$t('brandname.config.time_warning.from')"
                    :placeholder-to="$t('brandname.config.time_warning.from')"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        ({{ $t("brandname.config.time_warning.minute") }})
                    </div>
                </form-range-control>

                <div
                    class="multiple-input-wrap col-12"
                    :class="{ 'error-repeat-form-wrap': noMultiple }"
                >
                    <label
                        class="multiple-input-heading"
                        :class="{ 'error-repeat-form-title': noMultiple }"
                    >
                        <span>{{
                            $t("brandname.config.time_warning.approve_time")
                        }}</span>
                    </label>
                    <div class="m--margin-left-20">
                        <div class="row">
                            <label class="col-12"
                                >{{
                                    $t(
                                        "brandname.config.time_warning.time_range"
                                    )
                                }}<span class="text-danger">(*)</span></label
                            >
                        </div>
                        <div
                            v-for="(input,
                            index) in form.multi_time_frame_details"
                            :key="index"
                            class="form-group m-form__group row"
                        >
                            <the-time-range
                                v-model="input.time"
                                v-validate="'required'"
                                :name="'time_test' + index"
                                :data-vv-as="$t('filter.timerange')"
                                :inline="false"
                                :error="
                                    errors.first('time_test' + index) ||
                                        form.errors.get('time_frame')
                                "
                                :required="true"
                            ></the-time-range>

                            <div
                                class="col-2 form-group m-form__group full-height-col"
                            >
                                <div class="delete-button-wrap">
                                    <a
                                        href="javascript:;"
                                        class="text-danger"
                                        @click="
                                            deleteDetails(
                                                form.multi_time_frame_details,
                                                index
                                            )
                                        "
                                    >
                                        {{ $t("button.delete") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div
                            class="m-form__group form-group m--margin-bottom-10"
                        >
                            <a
                                href="javascript:;"
                                class=""
                                @click="
                                    addDetails(form.multi_time_frame_details, {
                                        time: null
                                    })
                                "
                                >{{ $t("button.add") }}
                            </a>
                        </div>
                    </div>
                    <div
                        v-if="noMultiple"
                        class="form-control-feedback error-repeat-form error-repeat-form-title"
                        style="margin-left:15px;"
                    >
                        {{
                            $t(
                                "brandname.config.time_warning.time_frame_required"
                            )
                        }}
                    </div>
                </div>
            </div>
        </form>

        <template slot="footer">
            <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
            >
                {{ $t("button.cancel") }}
            </button>
            <button type="button" class="btn btn-primary" @click="validateForm">
                {{ isEdit ? $t("button.edit") : $t("button.add") }}
            </button>
        </template>
    </modal>
</template>

<script>
import Form from "vform";
import {
    notify,
    notifyTryAgain,
    notifyAddSuccess,
    notifyUpdateSuccess
} from "~/helpers/bootstrap-notify";
import bootbox from "bootbox";
import WeekDayPicker from "~/components/common/WeekDayPicker";
import FormControl from "~/components/common/FormControl";
import { SUCCESS } from "~/constants/code";
import SmsGroupChosen from "~/components/elements/chosens/SmsGroupChosen";
import SmsTypeChosen from "~/components/elements/chosens/SmsTypeChosen";
import TheTimeRange from "~/components/common/TheTimeRange";
import FormRangeControl from "~/components/common/FormRangeControl";
import { htmlEscapeEntities } from "~/helpers/tableHelper";

const defaultForm = {
    sms_group: null,
    apply_time: null,
    sms_type_result: null,
    day: [],
    multi_time_frame_details: [
        {
            time: null
        }
    ],
    high: null,
    danger: null,
    crisis: null
};
export default {
    name: "TimeWarningConfigModal",
    components: {
        FormRangeControl,
        TheTimeRange,
        SmsTypeChosen,
        SmsGroupChosen,
        FormControl,
        WeekDayPicker
    },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        },
        error: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            form: new Form(defaultForm),
            formLabelWidth: "130px",
            isEdit: true,
            timeOptions: {
                format: "HH:mm"
            }
        };
    },
    computed: {
        smsTypeOptions() {
            return {
                placeholder: this.$t(
                    "brandname.config.duplicate.smsTypePlaceholder"
                ),
                multiple: false,
                idField: "id",
                textField: "name",
                ajax: "/brandname/brandname-segment-config/listing-sms-type"
            };
        },
        noMultiple() {
            return this.form.multi_time_frame_details.length == 0;
        }
        // smsGroup() {
        //     return this.form.sms_group;
        // }
    },
    watch: {
        "form.sms_group": function(newVal, oldVal) {
            if (newVal != oldVal && oldVal != null) {
                this.form.sms_type_result = null;
            }
        }
    },
    methods: {
        async show(item = null) {
            if (item != null) {
                // console.log(item);

                //apply time
                item.apply_time = [item.apply_from, item.apply_to];

                //sms group
                item.sms_group = {
                    id: item.brandname_sms_type.brandname_sms_group.id,
                    name: item.brandname_sms_type.brandname_sms_group.name,
                    text: item.brandname_sms_type.brandname_sms_group.name
                };

                //sms type
                item.sms_type_result = {
                    id: item.brandname_sms_type.id,
                    name: item.brandname_sms_type.name,
                    text: item.brandname_sms_type.name
                };

                //week_day
                var day = JSON.parse(item.week_day).day;
                item.day = day;

                //time_frame
                var time = JSON.parse(item.time_frame).time_frame;
                item.multi_time_frame_details = [];
                var arr = [];
                time.forEach(function(value) {
                    arr.push({
                        time: [value.from, value.to]
                    });
                });
                item.multi_time_frame_details = arr;

                //high
                item.high = {
                    from: item.high_warning_from,
                    to: item.high_warning_to
                };

                //danger
                item.danger = {
                    from: item.danger_warning_from,
                    to: item.danger_warning_to
                };

                //crisis
                item.crisis = {
                    from: item.crisis_warning_from,
                    to: item.crisis_warning_to
                };

                this.form = new Form(item);
                this.isEdit = true;
            } else {
                this.isEdit = false;
                this.form = new Form(defaultForm);
                this.$validator.reset();
            }
            this.$refs.modal.show();
        },
        closeModal() {
            this.$validator.reset();
            this.$refs.modal.hide();
        },
        async add() {
            const _this = this;

            this.setupDataPost();

            bootbox.confirm({
                title: this.$t("alert.notice"),
                message:
                    `${this.$t(
                        "brandname.config.time_warning.alert_add"
                    )} <br/> <span class="text-danger"></span>` +
                    `<ul>` +
                    `<li><b>${this.$t(
                        "label.sms_group"
                    )} </b>:${htmlEscapeEntities(
                        this.form.sms_group.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "label.sms_type"
                    )} </b>:${htmlEscapeEntities(
                        this.form.sms_type_result.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.approve_time"
                    )} </b>:${htmlEscapeEntities(
                        this.handleTimeFrame(this.form.time_frame)
                    )}</li>` +
                    `<li><b>${this.$t(
                        "label.select_day"
                    )} </b>:${htmlEscapeEntities(
                        this.handleWeekDay(this.form.week_day)
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.apply_time"
                    )} </b>:${htmlEscapeEntities(
                        this.form.apply_time[0]
                    )} ${this.$t(
                        "brandname.config.segment_warning.to"
                    )} ${htmlEscapeEntities(this.form.apply_time[1])}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.high_warning"
                    )} </b>:${htmlEscapeEntities(
                        this.form.high_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.high_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.danger_warning"
                    )} </b>:${htmlEscapeEntities(
                        this.form.danger_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.danger_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.crisis_warning"
                    )} </b>:${htmlEscapeEntities(
                        this.form.crisis_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.crisis_warning_to
                    )}</li>` +
                    `</ul>`,
                buttons: {
                    cancel: {
                        label: this.$t("button.cancel")
                    },
                    confirm: {
                        label: this.$t("button.ok")
                    }
                },
                callback: async function(result) {
                    if (result) {
                        try {
                            const res = await _this.form.post(
                                "/brandname/brandname-time-warning-config/add"
                            );
                            const { data } = res;

                            if (data.code === SUCCESS) {
                                notifyAddSuccess();
                                _this.closeModal();
                                _this.onActionSuccess();
                            } else {
                                notifyTryAgain();
                                _this.closeModal();
                            }
                        } catch (e) {
                            console.log(e);
                        }
                    }
                }
            });
        },
        async edit() {
            const _this = this;

            this.setupDataPost();

            bootbox.confirm({
                title: this.$t("alert.notice"),
                message:
                    `${this.$t(
                        "brandname.config.time_warning.alert_edit"
                    )} <br/> <span class="text-danger"></span>` +
                    `<ul>` +
                    `<li><b>${this.$t(
                        "label.sms_group"
                    )} </b>:${htmlEscapeEntities(
                        this.form.sms_group.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "label.sms_type"
                    )} </b>:${htmlEscapeEntities(
                        this.form.sms_type_result.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.approve_time"
                    )} </b>:${htmlEscapeEntities(
                        this.handleTimeFrame(this.form.time_frame)
                    )}</li>` +
                    `<li><b>${this.$t(
                        "label.select_day"
                    )} </b>:${htmlEscapeEntities(
                        this.handleWeekDay(this.form.week_day)
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.apply_time"
                    )} </b>:${htmlEscapeEntities(
                        this.form.apply_time[0]
                    )} ${this.$t(
                        "brandname.config.segment_warning.to"
                    )} ${htmlEscapeEntities(this.form.apply_time[1])}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.high_warning"
                    )} </b>:${htmlEscapeEntities(
                        this.form.high_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.high_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.danger_warning"
                    )} </b>:${htmlEscapeEntities(
                        this.form.danger_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.danger_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.time_warning.crisis_warning"
                    )} </b>:${htmlEscapeEntities(
                        this.form.crisis_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.crisis_warning_to
                    )}</li>` +
                    `</ul>`,
                buttons: {
                    cancel: {
                        label: this.$t("button.cancel")
                    },
                    confirm: {
                        label: this.$t("button.ok")
                    }
                },
                callback: async function(result) {
                    if (result) {
                        try {
                            const res = await _this.form.post(
                                "/brandname/brandname-time-warning-config/edit"
                            );
                            const { data } = res;

                            if (data.code === SUCCESS) {
                                notifyUpdateSuccess();
                                _this.closeModal();
                                _this.onActionSuccess();
                            } else {
                                notifyTryAgain();
                                _this.closeModal();
                            }
                        } catch (e) {
                            console.log(e);
                        }
                    }
                }
            });
        },
        validateForm() {
            if (!this.noMultiple) {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        if (this.isEdit) {
                            this.edit();
                            this.$validator.reset();
                        } else {
                            this.add();
                            this.$validator.reset();
                        }
                    }
                });
            }
        },
        onModalHidden() {
            this.form.apply_time = null;
            this.form.sms_group = null;
            this.form.sms_type_result = null;
            this.form.day = [];
            this.form.multi_time_frame_details = [
                {
                    time: null
                }
            ];
            this.form.high = null;
            this.form.danger = null;
            this.form.crisis = null;
            this.isEdit = true;
            this.$validator.reset();
        },
        setupDataPost() {
            var arr = this.form.multi_time_frame_details;
            var time_frame = [];

            arr.forEach(function(value) {
                time_frame.push({
                    from: value.time[0],
                    to: value.time[1]
                });
            });

            var _this = this;
            _this.form.high_warning_from = _this.form.high.from;
            _this.form.high_warning_to = _this.form.high.to;
            _this.form.danger_warning_from = _this.form.danger.from;
            _this.form.danger_warning_to = _this.form.danger.to;
            _this.form.crisis_warning_from = _this.form.crisis.from;
            _this.form.crisis_warning_to = _this.form.crisis.to;

            _this.form.apply_from = _this.form.apply_time[0];
            _this.form.apply_to = _this.form.apply_time[1];
            _this.form.type_id = _this.form.sms_type_result.id;
            _this.form.week_day = _this.form.day.sort(function(a, b) {
                return a - b;
            });
            _this.form.time_frame = time_frame;
        },
        addDetails(array, item) {
            array.push(item);
        },
        deleteDetails(array, index) {
            array.splice(index, 1);
        },
        handleWeekDay(arr) {
            var day = [];
            arr.forEach(function(e) {
                switch (parseInt(e)) {
                    case 0:
                        day.push("T2");
                        break;
                    case 1:
                        day.push("T3");
                        break;
                    case 2:
                        day.push("T4");
                        break;
                    case 3:
                        day.push("T5");
                        break;
                    case 4:
                        day.push("T6");
                        break;
                    case 5:
                        day.push("T7");
                        break;
                    case 6:
                        day.push("CN");
                        break;
                }
            });
            return day;
        },
        handleTimeFrame(arr) {
            var time = [];
            arr.forEach(function(e) {
                time.push(e.from + "-" + e.to);
            });
            return time;
        }
    }
};
</script>

<style scoped></style>

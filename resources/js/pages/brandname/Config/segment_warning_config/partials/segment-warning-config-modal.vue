<template>
    <modal
        ref="modal"
        :title="
            isEdit
                ? $t('brandname.config.segment_warning.modal_edit_title')
                : $t('brandname.config.segment_warning.modal_add_title')
        "
        :on-hidden="onModalHidden"
        class="modal-md-xl"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <form-control
                v-model="form.nameResult"
                v-validate="'required'"
                :data-vv-as="$t('brandname.config.segment_warning.segment')"
                :label="$t('brandname.config.segment_warning.segment')"
                ::required="true"
                :name="'name'"
                type="select"
                :select-options="segmentOptions"
                :error="
                    errors.first('name') ||
                        form.errors.get('name') ||
                        form.errors.get('apply_time')
                "
            />

            <sms-group-chosen
                v-model="form.sms_group_result"
                v-validate="'required'"
                :multiple="false"
                :error="errors.first('smsGroup')"
                :has-all-option="true"
            ></sms-group-chosen>

            <sms-type-chosen
                v-model="form.sms_type_result"
                v-validate="'required'"
                :multiple="false"
                :sms-group-id="
                    form.sms_group_result && form.sms_group_result.id
                        ? form.sms_group_result.id
                        : null
                "
                :is-disabled="
                    form.sms_group_result && form.sms_group_result.id
                        ? false
                        : true
                "
                :error="errors.first('smsType') || form.errors.get('type_id')"
            ></sms-type-chosen>

            <div class="row">
                <form-range-control
                    ref="highWarning"
                    v-model="form.highWarning"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl'
                    "
                    :number-format="true"
                    :placeholder-from="
                        $t('brandname.config.segment_warning.high_warning_from')
                    "
                    :placeholder-to="
                        $t('brandname.config.segment_warning.high_warning_to')
                    "
                    class="col-6"
                    :label="$t('brandname.config.segment_warning.high_warning')"
                    :data-vv-as="
                        $t('brandname.config.segment_warning.high_warning')
                    "
                    name="highWarning"
                    :inline="true"
                    :error="
                        errors.first('highWarning') ||
                            form.errors.get('high_warning_from') ||
                            form.errors.get('high_warning_to')
                    "
                    :required="true"
                    append="true"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        / {{ $t("brandname.config.segment_warning.day") }}
                    </div>
                </form-range-control>

                <form-range-control
                    ref="highWarningMonth"
                    v-model="form.highWarningMonth"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl'
                    "
                    :number-format="true"
                    :placeholder-from="
                        $t('brandname.config.segment_warning.high_warning_from')
                    "
                    :placeholder-to="
                        $t('brandname.config.segment_warning.high_warning_to')
                    "
                    class="col-6"
                    :label="
                        $t(
                            'brandname.config.segment_warning.high_warning_month'
                        )
                    "
                    :data-vv-as="
                        $t(
                            'brandname.config.segment_warning.high_warning_month'
                        )
                    "
                    name="highWarningMonth"
                    :inline="true"
                    :error="
                        errors.first('highWarningMonth') ||
                            form.errors.get('high_warning_from_m') ||
                            form.errors.get('high_warning_to_m')
                    "
                    :required="true"
                    append="true"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        / {{ $t("brandname.config.segment_warning.month") }}
                    </div>
                </form-range-control>
            </div>

            <div class="row">
                <form-range-control
                    ref="dangerWarning"
                    v-model="form.dangerWarning"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl|fromAfterIsGreaterToBeforeFormRangeControl:highWarning'
                    "
                    :number-format="true"
                    :placeholder-from="
                        $t(
                            'brandname.config.segment_warning.danger_warning_from'
                        )
                    "
                    :placeholder-to="
                        $t('brandname.config.segment_warning.danger_warning_to')
                    "
                    class="col-6"
                    :label="
                        $t('brandname.config.segment_warning.danger_warning')
                    "
                    :data-vv-as="
                        $t('brandname.config.segment_warning.danger_warning')
                    "
                    name="dangerWarning"
                    :inline="true"
                    :error="
                        errors.first('dangerWarning') ||
                            form.errors.get('danger_warning_from') ||
                            form.errors.get('danger_warning_to')
                    "
                    :required="true"
                    append="true"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        / {{ $t("brandname.config.segment_warning.day") }}
                    </div>
                </form-range-control>

                <form-range-control
                    ref="dangerWarningMonth"
                    v-model="form.dangerWarningMonth"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl|fromAfterIsGreaterToBeforeFormRangeControl:highWarningMonth'
                    "
                    :number-format="true"
                    :placeholder-from="
                        $t(
                            'brandname.config.segment_warning.danger_warning_from'
                        )
                    "
                    :placeholder-to="
                        $t('brandname.config.segment_warning.danger_warning_to')
                    "
                    class="col-6"
                    :label="
                        $t(
                            'brandname.config.segment_warning.danger_warning_month'
                        )
                    "
                    :data-vv-as="
                        $t(
                            'brandname.config.segment_warning.danger_warning_month'
                        )
                    "
                    name="dangerWarningMonth"
                    :inline="true"
                    :error="
                        errors.first('dangerWarningMonth') ||
                            form.errors.get('danger_warning_from_m') ||
                            form.errors.get('danger_warning_to_m')
                    "
                    :required="true"
                    append="true"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        / {{ $t("brandname.config.segment_warning.month") }}
                    </div>
                </form-range-control>
            </div>
            <div class="row">
                <form-range-control
                    ref="crisisWarning"
                    v-model="form.crisisWarning"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl|fromAfterIsGreaterToBeforeFormRangeControl:dangerWarning'
                    "
                    :number-format="true"
                    :placeholder-from="
                        $t(
                            'brandname.config.segment_warning.crisis_warning_from'
                        )
                    "
                    :placeholder-to="
                        $t('brandname.config.segment_warning.crisis_warning_to')
                    "
                    class="col-6"
                    :label="
                        $t('brandname.config.segment_warning.crisis_warning')
                    "
                    :data-vv-as="
                        $t('brandname.config.segment_warning.crisis_warning')
                    "
                    name="crisisWarning"
                    :inline="true"
                    :error="
                        errors.first('crisisWarning') ||
                            form.errors.get('crisis_warning_from') ||
                            form.errors.get('crisis_warning_to')
                    "
                    :required="true"
                    append="true"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        / {{ $t("brandname.config.segment_warning.day") }}
                    </div>
                </form-range-control>

                <form-range-control
                    ref="crisisWarningMonth"
                    v-model="form.crisisWarningMonth"
                    v-validate="
                        'required|requiredFormRangeControl|toGreaterOrEqualFromFormRangeControl|fromAfterIsGreaterToBeforeFormRangeControl:dangerWarningMonth'
                    "
                    :number-format="true"
                    :placeholder-from="
                        $t(
                            'brandname.config.segment_warning.crisis_warning_from'
                        )
                    "
                    :placeholder-to="
                        $t('brandname.config.segment_warning.crisis_warning_to')
                    "
                    class="col-6"
                    :label="
                        $t(
                            'brandname.config.segment_warning.crisis_warning_month'
                        )
                    "
                    :data-vv-as="
                        $t(
                            'brandname.config.segment_warning.crisis_warning_month'
                        )
                    "
                    name="crisisWarningMonth"
                    :inline="true"
                    :error="
                        errors.first('crisisWarningMonth') ||
                            form.errors.get('crisis_warning_from_m') ||
                            form.errors.get('crisis_warning_to_m')
                    "
                    :required="true"
                    append="true"
                >
                    <div
                        slot="input-group-append"
                        class="input-group-append-style"
                    >
                        / {{ $t("brandname.config.segment_warning.month") }}
                    </div>
                </form-range-control>
            </div>

            <div>
                <the-date-range
                    v-model="form.apply_time"
                    v-validate="'required'"
                    name="apply_time"
                    :inline="false"
                    :data-vv-as="$t('brandname.config.segment.apply_time')"
                    :label="$t('brandname.config.segment.apply_time')"
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

<style scoped>
.input-group-append-style {
    padding-top: 0.525rem;
    font-size: 14px;
}
</style>

<script>
import Form from "vform";
import { notify, notifyTryAgain } from "~/helpers/bootstrap-notify";
import { SUCCESS } from "~/constants/code";
import bootbox from "bootbox";
import SmsGroupChosen from "../../../../../components/elements/chosens/SmsGroupChosen";
import SmsTypeChosen from "../../../../../components/elements/chosens/SmsTypeChosen";
import { htmlEscapeEntities } from "~/helpers/tableHelper";

const defaultForm = {
    highWarning: null,
    dangerWarning: null,
    crisisWarning: null,
    highWarningMonth: null,
    dangerWarningMonth: null,
    crisisWarningMonth: null,
    apply_time: null,
    sms_type: null,
    sms_type_result: null,
    sms_group: null,
    sms_group_result: null,
    nameResult: null
};

export default {
    name: "SegmentWarningConfigModal",
    components: {
        SmsTypeChosen,
        SmsGroupChosen
    },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            form: new Form(defaultForm),
            isEdit: true
        };
    },
    computed: {
        segmentOptions() {
            return {
                placeholder: this.$t(
                    "brandname.config.segment_warning.segmentPlaceholder"
                ),
                multiple: false,
                searchable: false,
                options: [
                    {
                        id: "All",
                        text: "Tất cả"
                    },
                    {
                        id: "DaiTra",
                        text: "Đại trà"
                    },
                    {
                        id: "TiemNang",
                        text: "Tiềm năng"
                    },
                    {
                        id: "Vip",
                        text: "Vip"
                    }
                ]
            };
        }
    },
    watch: {
        "form.sms_group_result": function(newVal, oldVal) {
            if (newVal != oldVal && oldVal != null) {
                this.form.sms_type_result = null;
                this.$validator.reset();
            }
        }
    },
    mounted() {},
    methods: {
        show(item = null) {
            if (item != null) {
                item.sms_type_result = {
                    id: item.brandname_sms_type.id,
                    text: item.brandname_sms_type.name,
                    name: item.brandname_sms_type.name
                };
                item.sms_group_result = {
                    id: item.brandname_sms_type.brandname_sms_group.id,
                    text: item.brandname_sms_type.brandname_sms_group.name,
                    name: item.brandname_sms_type.brandname_sms_group.name
                };
                item.highWarning = {
                    from: item.high_warning_from,
                    to: item.high_warning_to
                };
                item.dangerWarning = {
                    from: item.danger_warning_from,
                    to: item.danger_warning_to
                };
                item.crisisWarning = {
                    from: item.crisis_warning_from,
                    to: item.crisis_warning_to
                };

                item.highWarningMonth = {
                    from: item.high_warning_from_m,
                    to: item.high_warning_to_m
                };
                item.dangerWarningMonth = {
                    from: item.danger_warning_from_m,
                    to: item.danger_warning_to_m
                };
                item.crisisWarningMonth = {
                    from: item.crisis_warning_from_m,
                    to: item.crisis_warning_to_m
                };

                if (item.name == "All") {
                    item.nameResult = {
                        id: item.name,
                        text: "Tất cả",
                        name: "Tất cả"
                    };
                } else if (item.name == "TiemNang") {
                    item.nameResult = {
                        id: item.name,
                        text: "Tiềm năng",
                        name: "Tiềm năng"
                    };
                } else if (item.name == "DaiTra") {
                    item.nameResult = {
                        id: item.name,
                        text: "Đại trà",
                        name: "Đại trà"
                    };
                } else {
                    item.nameResult = {
                        id: item.name,
                        text: item.name,
                        name: item.name
                    };
                }

                item.apply_time = [item.apply_from, item.apply_to];
                this.form = new Form(item);
                this.isEdit = true;
            } else {
                this.isEdit = false;
            }
            this.$refs.modal.show();
        },
        closeModal() {
            this.$refs.modal.hide();
        },
        async add() {
            const _this = this;
            this.setupDataPost();
            bootbox.confirm({
                title: this.$t("alert.notice"),
                size: "large",
                message:
                    `${this.$t(
                        "brandname.config.segment_warning.alert_add_confirm"
                    )}? <br/> <span class="text-danger"></span>` +
                    `<ul>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.segment"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.nameResult.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.sms_group"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.sms_group_result.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.sms_type"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.sms_type_result.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.high_warning"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.high_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.high_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.danger_warning"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.danger_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.danger_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.crisis_warning"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.crisis_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.crisis_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.high_warning_month"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.high_warning_from_m
                    )} - ${htmlEscapeEntities(
                        this.form.high_warning_to_m
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.danger_warning_month"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.danger_warning_from_m
                    )} - ${htmlEscapeEntities(
                        this.form.danger_warning_to_m
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.crisis_warning_month"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.crisis_warning_from_m
                    )} - ${htmlEscapeEntities(
                        this.form.crisis_warning_to_m
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.apply_time"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.apply_time[0]
                    )} ${this.$t(
                        "brandname.config.segment_warning.to"
                    )} ${htmlEscapeEntities(this.form.apply_time[1])}</li>` +
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
                                "/brandname/brandname-segment-warning-config/add"
                            );
                            const { data } = res;

                            if (data.code === SUCCESS) {
                                notify(
                                    _this.$t("label.notification"),
                                    _this.$t(
                                        "brandname.config.segment_warning.alert_add_successfully"
                                    ),
                                    "success",
                                    "icon la la-check"
                                );
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
                size: "large",
                message:
                    `${this.$t(
                        "brandname.config.segment_warning.alert_edit_confirm"
                    )}? <br/> <span class="text-danger"></span>` +
                    `<ul>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.segment"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.nameResult.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.sms_group"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.sms_group_result.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.sms_type"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.sms_type_result.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.high_warning"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.high_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.high_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.danger_warning"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.danger_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.danger_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.crisis_warning"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.crisis_warning_from
                    )} - ${htmlEscapeEntities(
                        this.form.crisis_warning_to
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.high_warning_month"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.high_warning_from_m
                    )} - ${htmlEscapeEntities(
                        this.form.high_warning_to_m
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.danger_warning_month"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.danger_warning_from_m
                    )} - ${htmlEscapeEntities(
                        this.form.danger_warning_to_m
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.crisis_warning_month"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.crisis_warning_from_m
                    )} - ${htmlEscapeEntities(
                        this.form.crisis_warning_to_m
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment_warning.apply_time"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.apply_time[0]
                    )} ${this.$t(
                        "brandname.config.segment_warning.to"
                    )} ${htmlEscapeEntities(this.form.apply_time[1])}</li>` +
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
                            _this.setupDataPost();

                            const res = await _this.form.post(
                                "/brandname/brandname-segment-warning-config/edit"
                            );
                            const { data } = res;

                            if (data.code === SUCCESS) {
                                notify(
                                    _this.$t("label.notification"),
                                    _this.$t(
                                        "brandname.config.segment_warning.alert_edit_successfully"
                                    ),
                                    "success",
                                    "icon la la-check"
                                );
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
            this.$validator.validateAll().then(result => {
                if (result) {
                    if (this.isEdit) {
                        this.edit();
                    } else {
                        this.add();
                    }
                }
            });
        },
        setupDataPost() {
            this.form.apply_from = this.form.apply_time[0];
            this.form.apply_to = this.form.apply_time[1];
            this.form.type_id = this.form.sms_type_result.id;
            this.form.high_warning_from = this.form.highWarning.from;
            this.form.high_warning_to = this.form.highWarning.to;
            this.form.danger_warning_from = this.form.dangerWarning.from;
            this.form.danger_warning_to = this.form.dangerWarning.to;
            this.form.crisis_warning_from = this.form.crisisWarning.from;
            this.form.crisis_warning_to = this.form.crisisWarning.to;
            this.form.high_warning_from_m = this.form.highWarningMonth.from;
            this.form.high_warning_to_m = this.form.highWarningMonth.to;
            this.form.danger_warning_from_m = this.form.dangerWarningMonth.from;
            this.form.danger_warning_to_m = this.form.dangerWarningMonth.to;
            this.form.crisis_warning_from_m = this.form.crisisWarningMonth.from;
            this.form.crisis_warning_to_m = this.form.crisisWarningMonth.to;
            this.form.name = this.form.nameResult.id;
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = true;
            this.$validator.reset();
        }
    }
};
</script>

<style></style>

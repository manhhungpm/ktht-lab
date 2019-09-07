<template>
    <modal
        ref="modal"
        :title="
            isEdit
                ? $t('brandname.config.segment.modal_edit_title')
                : $t('brandname.config.segment.modal_add_title')
        "
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <div class="row">
                <form-control
                    v-model="form.nameResult"
                    v-validate="'required'"
                    class="col-12"
                    :data-vv-as="$t('brandname.config.segment.segment')"
                    :label="$t('brandname.config.segment.segment')"
                    :required="true"
                    :name="'name'"
                    type="select"
                    :select-options="segmentOptions"
                    :error="
                        errors.first('name') ||
                            form.errors.get('name') ||
                            form.errors.get('apply_time')
                    "
                />

                <form-control
                    v-model="form.per_day"
                    v-validate="'required|numeric|max:12'"
                    class="col-12"
                    :data-vv-as="$t('brandname.config.segment.per_day')"
                    :label="$t('brandname.config.segment.per_day')"
                    :required="true"
                    :name="'per_day'"
                    :error="
                        errors.first('per_day') || form.errors.get('per_day')
                    "
                />

                <form-control
                    v-model="form.per_month"
                    v-validate="'required|numeric|max:12'"
                    class="col-12"
                    :data-vv-as="$t('brandname.config.segment.per_month')"
                    :label="$t('brandname.config.segment.per_month')"
                    :required="true"
                    :name="'per_month'"
                    :error="
                        errors.first('per_month') ||
                            form.errors.get('per_month')
                    "
                />

                <sms-group-chosen
                    v-model="form.sms_group"
                    v-validate="'required'"
                    class="col-6"
                    :multiple="false"
                    :has-all-option="true"
                    :error="errors.first('smsGroup')"
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
                        :inline="false"
                        name="apply_time"
                        :data-vv-as="$t('brandname.config.segment.apply_time')"
                        :label="$t('brandname.config.segment.apply_time')"
                        :error="
                            errors.first('apply_time') ||
                                form.errors.get('apply_time')
                        "
                        :required="true"
                        :type="'datetimerange'"
                        :format="'yyyy-MM-dd HH:mm:ss'"
                        :disabled-date="'lessThanToday'"
                        :name-shortcut="[
                            'this_month',
                            'next_month',
                            'next_30_days',
                            'next_7_days',
                            'tomorrow'
                        ]"
                        :shortcut="true"
                    >
                    </the-date-range>
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
import { notify, notifyTryAgain } from "~/helpers/bootstrap-notify";
import bootbox from "bootbox";
import SmsGroupChosen from "../../../../../components/elements/chosens/SmsGroupChosen";
import SmsTypeChosen from "../../../../../components/elements/chosens/SmsTypeChosen";
import { htmlEscapeEntities } from "~/helpers/tableHelper";
import { SUCCESS } from "~/constants/code";

const defaultForm = {
    name: null,
    nameResult: null,
    per_day: null,
    per_month: null,
    apply_time: null,
    sms_type: null,
    sms_type_result: null,
    sms_group: null
};

export default {
    name: "SegmentConfigModal",
    components: { SmsTypeChosen, SmsGroupChosen },
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
                    "brandname.config.segment.segmentPlaceholder"
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
        "form.sms_group": function(newVal, oldVal) {
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

                //sms group
                item.sms_group = {
                    id: item.brandname_sms_type.brandname_sms_group.id,
                    name: item.brandname_sms_type.brandname_sms_group.name,
                    text: item.brandname_sms_type.brandname_sms_group.name
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
            bootbox.confirm({
                title: this.$t("alert.notice"),
                size: "large",
                message:
                    `${this.$t(
                        "brandname.config.segment.alert_add_confirm"
                    )} <br/> <span class="text-danger"></span>` +
                    `<ul>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.segment"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.nameResult.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.sms_group"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.sms_group.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.sms_type"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.sms_type_result.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.per_day"
                    )}</b>: ${htmlEscapeEntities(this.form.per_day)}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.per_month"
                    )}</b>: ${htmlEscapeEntities(this.form.per_month)}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.apply_time"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.apply_time[0]
                    )} ${this.$t(
                        "brandname.config.segment.to"
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
                            _this.form.apply_from = _this.form.apply_time[0];
                            _this.form.apply_to = _this.form.apply_time[1];
                            _this.form.type_id = _this.form.sms_type_result.id;
                            _this.form.name = _this.form.nameResult.id;

                            const res = await _this.form.post(
                                "/brandname/brandname-segment-config/add"
                            );
                            const { data } = res;

                            if (data.code === SUCCESS) {
                                notify(
                                    _this.$t("label.notification"),
                                    _this.$t(
                                        "brandname.config.segment.alert_add_successfully"
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
            bootbox.confirm({
                title: this.$t("alert.notice"),
                size: "large",
                message:
                    `${this.$t(
                        "brandname.config.segment.alert_edit_confirm"
                    )} <br/> <span class="text-danger"></span>` +
                    `<ul>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.segment"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.nameResult.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.sms_group"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.sms_group.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.sms_type"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.sms_type_result.text
                    )}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.per_day"
                    )}</b>: ${htmlEscapeEntities(this.form.per_day)}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.per_month"
                    )}</b>: ${htmlEscapeEntities(this.form.per_month)}</li>` +
                    `<li><b>${this.$t(
                        "brandname.config.segment.apply_time"
                    )}</b>: ${htmlEscapeEntities(
                        this.form.apply_time[0]
                    )} ${this.$t(
                        "brandname.config.segment.to"
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
                            _this.form.apply_from = _this.form.apply_time[0];
                            _this.form.apply_to = _this.form.apply_time[1];
                            _this.form.type_id = _this.form.sms_type_result.id;
                            _this.form.name = _this.form.nameResult.id;

                            const res = await _this.form.post(
                                "/brandname/brandname-segment-config/edit"
                            );
                            const { data } = res;

                            if (data.code === SUCCESS) {
                                notify(
                                    _this.$t("label.notification"),
                                    _this.$t(
                                        "brandname.config.segment.alert_edit_successfully"
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
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = true;
            this.$validator.reset();
        }
    }
};
</script>

<style></style>

<template>
    <div>
        <div class="m-portlet__body">
            <div
                id="m_accordion_5"
                class="m-accordion m-accordion--default m-accordion--toggle-arrow"
                role="tablist"
            >
                <div class="m-accordion__item m-accordion__item--brand">
                    <div
                        id="m_accordion_5_item_3_head"
                        class="m-accordion__item-head collapsed"
                        role="tab"
                        data-toggle="collapse"
                        href="#m_accordion_5_item_3_body"
                        aria-expanded="true"
                    >
                        <span class="m-accordion__item-title">
                            Tìm kiếm nâng cao</span
                        >
                        <span class="m-accordion__item-mode"></span>
                    </div>
                    <div
                        id="m_accordion_5_item_3_body"
                        class="m-accordion__item-body collapse"
                        role="tabpanel"
                        aria-labelledby="m_accordion_5_item_3_head"
                        data-parent="#m_accordion_5"
                    >
                        <div class="m-accordion__item-content">
                            <div class="row">
                                <form-control
                                    v-model="form.content"
                                    v-validate=""
                                    class="col-4"
                                    :data-vv-as="
                                        $t('spam.spam_patterns.content')
                                    "
                                    :label="$t('spam.spam_patterns.content')"
                                    :name="'content'"
                                    :error="
                                        errors.first('content') ||
                                            form.errors.get('content')
                                    "
                                >
                                </form-control>

                                <form-control
                                    v-model="form.sourceResult"
                                    class="col-4"
                                    :data-vv-as="
                                        $t(
                                            'spam.spam_patterns.labeled.spam_label'
                                        )
                                    "
                                    :label="
                                        $t(
                                            'spam.spam_patterns.labeled.select_spam_application'
                                        )
                                    "
                                    :type="'select'"
                                    :select-options="sourceOption"
                                    :required="false"
                                    :name="'label'"
                                    :error="
                                        errors.first('label') ||
                                            form.errors.get('label')
                                    "
                                >
                                </form-control>

                                <spam-label-chosen
                                    v-model="form.labelResult"
                                    class="col-4"
                                    :multiple="true"
                                    :required="false"
                                    :has-parent-item="true"
                                    :error="
                                        errors.first('label') ||
                                            form.errors.get('label')
                                    "
                                ></spam-label-chosen>

                                <div class="col-4">
                                    <label class="">
                                        {{
                                            $t(
                                                "spam.spam_patterns.labeled.who_updated"
                                            )
                                        }}
                                        <span class="text-danger"
                                            >(*)</span
                                        ></label
                                    >
                                    <user-chosen
                                        v-model="form.username"
                                        :multiple="true"
                                    >
                                    </user-chosen>
                                </div>

                                <div class="col-4">
                                    <label class="">
                                        {{
                                            $t(
                                                "spam.spam_patterns.labeled.when_updated"
                                            )
                                        }}
                                        <span class="text-danger"
                                            >(*)</span
                                        ></label
                                    >
                                    <div>
                                        <el-date-picker
                                            v-model="form.when_updated"
                                            class="date-range-picker-full-width"
                                            type="daterange"
                                            :range-separator="
                                                $t(
                                                    'component.date_picker.range_separator'
                                                )
                                            "
                                            :start-placeholder="
                                                $t(
                                                    'component.date_picker.start_placeholder'
                                                )
                                            "
                                            :end-placeholder="
                                                $t(
                                                    'component.date_picker.end_placeholder'
                                                )
                                            "
                                            value-format="dd-MM-yyyy"
                                        >
                                        </el-date-picker>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <v-button
                                    color="accent"
                                    style-type="air"
                                    class="m-btn m-btn--icon m--margin-right-10 btn btn-primary "
                                    @click.native="search"
                                >
                                    <i class="la la-search"></i>
                                    {{ $t("button.search") }}
                                </v-button>

                                <v-button
                                    color="accent"
                                    style-type="air"
                                    class="m-btn m-btn--icon m--margin-right-10 btn btn-info"
                                    :loading="isExporting"
                                    @click.native="exportFile"
                                >
                                    <i class="la la-cloud-download"></i>
                                    {{ $t("button.export") }}
                                </v-button>

                                <v-button
                                    color="accent"
                                    style-type="air"
                                    class="m-btn m-btn--icon m--margin-right-10 btn btn-metal "
                                    @click.native="reset"
                                    ><i class="la la-rotate-right"></i> Reset
                                </v-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import i18n from "~/plugins/i18n";

import { notify } from "~/helpers/bootstrap-notify";
import { downloadFile } from "~/helpers/downloadFile";
import SpamLabelChosen from "../../../../../components/elements/chosens/SpamLabelChosen";

const defaultLabelFiler = {
    content: null,
    spam_application: null,
    when_updated: null,
    label: null,
    username: null,
    labelResult: null,
    sourceResult: null
};

export default {
    name: "LabeledFilter",
    components: { SpamLabelChosen },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        },
        isRequiredToExport: {
            type: Boolean,
            default: false
        },
        partnerId: {
            type: Number,
            default: null
        },
        partnerName: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            isEdit: false,
            service: null,
            form: new Form(defaultLabelFiler),
            source: [],
            isExporting: false
        };
    },
    computed: {
        sourceOption() {
            return {
                placeholder: "Chọn nhãn",
                idField: "name",
                textField: "display_name",
                multiple: true,
                searchable: false,
                options: this.source
            };
        }
    },
    watch: {
        async isRequiredToExport() {
            if (this.isRequiredToExport == true) {
                await this.exportFile();
                this.$emit("isExportFileSuccessfully");
            }
        }
    },
    mounted() {
        if (this.partnerId != null) {
            this.form.partner = {
                id: this.partnerId,
                name: this.partnerName
            };
        }
    },
    async beforeCreate() {
        try {
            const sourcesResponse = await axios.post(
                "/spam/spam-patterns-labeled/list-all-source"
            );
            this.source = sourcesResponse.data.results;
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        validateForm() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.search();
                }
            });
        },
        search() {
            let searchParams = this.filter();
            this.$emit("search", searchParams);
        },
        filter() {
            let searchParams = {};
            if (this.form.content) {
                searchParams.content = this.form.content;
            }
            if (this.form.labelResult) {
                searchParams.label = this.form.labelResult.map(e => {
                    return e.name;
                });
            }
            if (this.form.sourceResult) {
                searchParams.spam_application = this.form.sourceResult.map(
                    e => {
                        return e.name;
                    }
                );
            }
            if (this.form.username) {
                searchParams.username = this.form.username.map(e => {
                    return e.name;
                });
            }
            if (this.form.when_updated) {
                searchParams.when_updated = this.form.when_updated;
            }
            return searchParams;
        },
        reset() {
            this.form = new Form(defaultLabelFiler);
            let searchParams = this.filter();
            this.$emit("search", searchParams);
        },
        async exportFile() {
            this.isExporting = true;
            const searchParams = this.filter();

            // const { data } = await axios.post(
            //     "/spam/spam-patterns-labeled/listing",
            //     searchParams
            // );
            //
            // if (data.recordsTotal) {
            try {
                const res = await axios.post(
                    "/spam/spam-patterns-labeled/export",
                    searchParams,
                    {
                        responseType: "blob"
                    }
                );
                downloadFile(res);
                this.isExporting = false;
            } catch (e) {
                notify(
                    i18n.t("label.notification"),
                    i18n.t("spam.spam_patterns.noti.export_error"),
                    "warning",
                    "icon la la-exclamation-triangle"
                );
                this.isExporting = false;
            }
            // } else {
            //     notify(
            //         i18n.t("label.notification"),
            //         i18n.t("spam.spam_patterns.noti.export_error"),
            //         "warning",
            //         "icon la la-exclamation-triangle"
            //     );
            //     this.isExporting = false;
            // }
        }
    }
};
</script>

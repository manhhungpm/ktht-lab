<template>
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
                    <span class="m-accordion__item-title"> {{ title }}</span>
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
                        <div class="row portlet-first-item">
                            <div class="offset-3 col-3">
                                <div
                                    class="form-group m-form__group row"
                                    :class="{ 'has-danger': error }"
                                >
                                    <label
                                        v-if="label"
                                        class="col-4 pt-md-2 inline-label"
                                        >{{ label }}
                                        <span
                                            v-if="required"
                                            class="text-danger"
                                            >(*)</span
                                        ></label
                                    >
                                    <div class="col-8">
                                        <el-date-picker
                                            v-model="tableFilter"
                                            :type="type"
                                            :placeholder="placeholder"
                                            :value-format="valueFormat"
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
                            </div>

                            <div
                                class="col-4 d-flex justify-content-center"
                                style="height: 40px"
                            >
                                <v-button
                                    v-if="!autoFilter"
                                    color="primary"
                                    style-type="air"
                                    class="m-btn--custom m-btn--icon m--margin-right-10"
                                    @click.native="validateForm"
                                >
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>{{ $t("button.search") }}</span>
                                    </span>
                                </v-button>

                                <download-button
                                    v-if="exportable"
                                    :url="exportUrl"
                                    :post-data="postData"
                                ></download-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import i18n from "~/plugins/i18n";
import moment from "moment";

export default {
    name: "DateFilterMonth",
    props: {
        title: {
            type: String,
            default: null
        },
        defaultTime: {
            type: String,
            default: () => null
        },
        autoFilter: {
            type: Boolean,
            default: false
        },
        exportable: {
            type: Boolean,
            default: false
        },
        exportUrl: {
            type: String,
            default: ""
        },
        rulesValidate: {
            type: String,
            default: ""
        },
        label: {
            type: String,
            default: i18n.t("label.select_month")
        },
        error: {
            type: String,
            default: null
        },
        required: {
            type: Boolean,
            default: false
        },
        valueFormat: {
            type: String,
            default: "yyyy-MM"
        },
        type: {
            type: String,
            default: "day"
        },
        placeholder: {
            type: String,
            default: i18n.t("label.select_month_placeholder")
        }
    },
    data() {
        return {
            tableFilter: null
        };
    },
    computed: {
        postData() {
            if (this.tableFilter) {
                let date = this.tableFilter;
                let time = {
                    time: [
                        moment(date, "YYYY-MM")
                            .startOf("month")
                            .format("YYYY-MM-DD 00:00:00"),
                        moment(date, "YYYY-MM")
                            .endOf("month")
                            .format("YYYY-MM-DD 23:59:59")
                    ]
                };
                return time;
            } else {
                return null;
            }
        }
    },
    watch: {
        tableFilter: {
            async handler() {
                if (this.autoFilter) {
                    this.$validator.reset();
                    await this.$nextTick();
                    this.validateForm();
                }
            },
            deep: true
        }
    },
    mounted() {
        this.tableFilter = this.defaultTime;
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
            this.$emit("search", this.postData);
        }
    }
};
</script>

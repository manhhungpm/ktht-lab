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
                            <div class="offset-2 col-5">
                                <the-date-range
                                    v-model="tableFilter.time"
                                    v-validate="rulesValidate"
                                    name="timerangeFilter"
                                    :data-vv-as="$t('filter.timerange')"
                                    :label="$t('component.date_picker.label')"
                                    :error="errors.first('timerangeFilter')"
                                    :shortcut="true"
                                    :name-shortcut="nameShortcut"
                                    :disabled-date="disabledDate"
                                >
                                </the-date-range>
                            </div>
                            <!--                        </div>-->
                            <!--                        <div class="text-right row">-->
                            <div
                                class="col-3 d-flex justify-content-center text-right"
                                style="height: 40px;"
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
import moment from "moment";
export default {
    name: "TimeRangeFilter",
    props: {
        title: {
            type: String,
            default: null
        },
        defaultFilter: {
            type: Object,
            default: () => {}
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
        //hung
        nameShortcut: {
            type: Array,
            default: () => []
        },
        disabledDate: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            tableFilter: this.defaultFilter
        };
    },
    computed: {
        postData() {
            if (this.tableFilter.time) {
                return {
                    from: moment(
                        this.tableFilter.time[0],
                        "YYYY-MM-DD HH:mm:ss"
                    ).format("YYYY-MM-DD 00:00:00"),
                    to: moment(
                        this.tableFilter.time[1],
                        "YYYY-MM-DD HH:mm:ss"
                    ).format("YYYY-MM-DD 23:59:59")
                };
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
    mounted() {},
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

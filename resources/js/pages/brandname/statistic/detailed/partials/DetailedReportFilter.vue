<template>
    <div>
        <div class="row">
            <the-date-range
                v-model="tableFilter.created_at"
                class="col-6"
                name="created_at"
                :data-vv-as="$t('filter.timerange')"
                :inline="false"
                :label="$t('datatable.column.when_created')"
                :error="errors.first('created_at')"
                :shortcut="false"
            >
            </the-date-range>
            <the-date-range
                v-model="tableFilter.from"
                class="col-6"
                name="from"
                :data-vv-as="$t('filter.timerange')"
                :inline="false"
                :label="$t('brandname.report.detailed.datatable.column.from')"
                :error="errors.first('from')"
                :shortcut="false"
            >
            </the-date-range>
            <the-date-range
                v-model="tableFilter.to"
                class="col-6"
                name="to"
                :data-vv-as="$t('filter.timerange')"
                :inline="false"
                :label="$t('brandname.report.detailed.datatable.column.to')"
                :error="errors.first('to')"
                :shortcut="false"
                :format="'dd/MM/yyyy'"
            >
            </the-date-range>
            <form-control
                v-model="tableFilter.status"
                class="col-6"
                :type="'select'"
                :select-options="statusOptions"
                :label="this.$t('brandname.list.active')"
            >
            </form-control>

            <div class="col-md-12 d-flex justify-content-center">
                <v-button
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon m--margin-right-10"
                    @click.native="validateForm"
                >
                    <span>
                        <i class="la la-search"></i>
                        <span>{{ $t("button.search") }}</span>
                    </span>
                </v-button>
                <v-button
                    color="warning"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="reset"
                >
                    <span>
                        <i class="la la-refresh"></i>
                        <span>Reset</span>
                    </span>
                </v-button>
            </div>
        </div>
    </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import i18n from "~/plugins/i18n";

const defaultFilter = {
    created_at: null,
    status: null,
    from: null,
    to: null
};

export default {
    name: "DetailedReportFilter",
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        },
        isRequiredToExport: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            statusOptions: {
                placeholder: i18n.t("brandname.list.placeholder.active"),
                multiple: true,
                searchable: true,
                options: [
                    {
                        id: 0,
                        text: this.$t(
                            "brandname.report.detailed.status.pending"
                        )
                    },
                    {
                        id: 1,
                        text: this.$t(
                            "brandname.report.detailed.status.processing"
                        )
                    },
                    {
                        id: 2,
                        text: this.$t("brandname.report.detailed.status.done")
                    },
                    {
                        id: 3,
                        text: this.$t("brandname.report.detailed.status.error")
                    }
                ]
            },
            tableFilter: defaultFilter
        };
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
            // let searchParams = this.tableFilter;
            let searchParams = this.filter();
            this.$emit("search", searchParams);
        },

        reset() {
            this.tableFilter.status = null;
            this.tableFilter.created_at = null;
            this.tableFilter.to = null;
            this.tableFilter.from = null;
        },

        filter() {
            let searchParams = {};

            if (this.tableFilter.status) {
                searchParams.status = this.tableFilter.status.map(e => {
                    return e.id;
                });
            }

            if (this.tableFilter.created_at) {
                searchParams.created_at = this.tableFilter.created_at;
            }

            if (this.tableFilter.from) {
                searchParams.from = this.tableFilter.from;
            }

            if (this.tableFilter.to) {
                searchParams.to = this.tableFilter.to;
            }

            console.log(searchParams);
            return searchParams;
        }
    }
};
</script>

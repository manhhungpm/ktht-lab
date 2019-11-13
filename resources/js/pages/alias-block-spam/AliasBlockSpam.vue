<template>
    <div>
        <div class="row">
            <div class="col-12">
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
                                    {{ $t("label.search_information") }}</span
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
                                    <alias-block-spam-filter
                                        :is-required-to-export="
                                            isRequiredToExport
                                        "
                                        @search="search"
                                        @isExportFileSuccessfully="
                                            isExportFileSuccessfully
                                        "
                                    ></alias-block-spam-filter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <portlet :title="$t('aliasblockspam.title')">
                    <data-table
                        ref="table"
                        :columns="columns"
                        url="/alias-block-spam/listing"
                        :order-column-index="4"
                        :order-type="'desc'"
                        :fixed-columns-left="1"
                        :fixed-columns-right="1"
                        :post-data="tableFilter"
                        :search-placeholder="
                            $t('aliasblockspam.search_placeholder')
                        "
                    ></data-table
                ></portlet>
            </div>
        </div>
    </div>
</template>

<script>
import Portlet from "~/components/common/Portlet";
import DataTable from "~/components/common/DataTable";
import AliasBlockSpamFilter from "./partials/AliasBlockSpamFilter";
import { NO, YES, NO_RESPONSE } from "../../constants/constant";
export default {
    name: "AliasBlockSpam",
    middleware: "auth",
    components: {
        AliasBlockSpamFilter,
        DataTable,
        Portlet
    },
    data() {
        return {
            tableFilter: {
                content_feedback: null,
                time_feedback: null
            },
            exportLoading: false,
            isRequiredToExport: false
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "survey_phone",
                    title: this.$t("aliasblockspam.alias")
                },
                {
                    data: "content_survey",
                    title: this.$t("aliasblockspam.content_survey"),
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            return data;
                        } else return "-";
                    }
                },
                {
                    data: "time_survey",
                    title: this.$t("aliasblockspam.time_survey")
                },
                {
                    data: "spam_alias",
                    title: this.$t("aliasblockspam.spam_alias")
                },
                {
                    data: "content_feedback",
                    title: this.$t("aliasblockspam.content_feedback"),
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            var content;
                            switch (data) {
                                case NO:
                                    content =
                                        '<span class="text-danger">Không</span>';
                                    break;
                                case YES:
                                    content =
                                        '<span class="text-success">Có</span>';
                                    break;
                                case NO_RESPONSE:
                                    content =
                                        '<span class="text-warning">Không phản hồi</span>';
                                    break;
                            }
                            return content;
                        } else return "-";
                    }
                },
                {
                    data: "time_feedback",
                    title: this.$t("aliasblockspam.time_feedback")
                }
            ];
        }
    },
    methods: {
        async search(value) {
            this.tableFilter = value;
            await this.$nextTick();
            this.$refs.table.reload();
        },
        async exportFile() {
            this.exportLoading = true;
            this.isRequiredToExport = true;
        },
        isExportFileSuccessfully() {
            this.exportLoading = false;
            this.isRequiredToExport = false;
        }
    }
};
</script>

<style scoped></style>

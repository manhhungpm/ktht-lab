<template>
    <div>
        <div class="row">
            <div class="col-6">
                <form-control
                    v-model="form.content_feedback"
                    :type="'select'"
                    :select-options="contentFeedbackOptions"
                    :label="$t('aliasblockspam.content_feedback')"
                >
                </form-control>
            </div>

            <div class="col-6">
                <the-date-range
                    v-model="form.time_feedback"
                    :inline="false"
                    name="updated_at"
                    :label="$t('aliasblockspam.time_feedback')"
                    :type="'datetimerange'"
                    :format="'yyyy-MM-dd HH:mm:ss'"
                    :disabled-date="'greaterThanToday'"
                    :name-shortcut="['last_7_days', 'last_30_days']"
                    :shortcut="true"
                    :default-time="[Date(), '23:59:59']"
                >
                </the-date-range>
            </div>

            <div class="col-md-12 d-flex justify-content-center">
                <v-button
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    style="margin-right: 5px"
                    @click.native="validateForm"
                >
                    <span>
                        <i class="la la-search"></i>
                        <span>{{ $t("button.search") }}</span>
                    </span>
                </v-button>
                <v-button
                    color="accent"
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
import { NO, YES, NO_RESPONSE } from "~/constants/constant";
import TheDateRange from "~/components/common/TheDateRange";

const defaultForm = {
    content_feedback: null,
    time_feedback: null
};
export default {
    name: "AliasBlockSpamFilter",
    components: { TheDateRange },
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
            form: new Form(defaultForm),
            contentFeedbackOptions: {
                placeholder: this.$t(
                    "aliasblockspam.placeholder.content_feedback"
                ),
                multiple: true,
                searchable: false,
                options: [
                    {
                        id: NO,
                        text: "No"
                    },
                    {
                        id: YES,
                        text: "Yes"
                    },
                    {
                        id: NO_RESPONSE,
                        text: "Không phản hồi"
                    }
                ]
            }
        };
    },
    watch: {
        async isRequiredToExport() {
            if (this.isRequiredToExport == true) {
                await this.exportFile();
                this.$emit("isExportFileSuccessfully");
            }
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
            let searchParams = this.filter();
            this.$emit("search", searchParams);
        },
        reset() {
            this.form = new Form(defaultForm);
        },
        filter() {
            let searchParams = {};

            if (this.form.time_feedback) {
                searchParams.time_feedback = this.form.time_feedback;
            }

            if (this.form.content_feedback) {
                searchParams.content_feedback = this.form.content_feedback.map(
                    e => {
                        return e.id;
                    }
                );
            }

            return searchParams;
        }
    }
};
</script>

<style scoped></style>

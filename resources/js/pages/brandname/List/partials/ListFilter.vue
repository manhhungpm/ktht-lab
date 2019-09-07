<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <label>{{ this.$t("brandname.list.brandname") }}</label>
                <input
                    v-model="form.brandname"
                    class="form-control"
                    :placeholder="$t('brandname.list.placeholder.brandname')"
                />
            </div>

            <div class="col-md-4">
                <form-control
                    v-model="form.br_type"
                    :type="'select'"
                    :select-options="brTypeOptions"
                    :label="this.$t('brandname.list.br_type')"
                >
                </form-control>
            </div>

            <div class="col-md-4">
                <form-control
                    v-model="form.status"
                    :type="'select'"
                    :select-options="statusOptions"
                    :label="this.$t('brandname.list.active')"
                >
                </form-control>
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
                    color="success"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    style="margin-right: 5px"
                    @click.native="exportFile"
                >
                    <span>
                        <i class="la la-cloud-download"></i>
                        <span>{{ $t("button.export") }}</span>
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
import { downloadFile } from "~/helpers/downloadFile";
import { notify, notifyTryAgain } from "~/helpers/bootstrap-notify";
import i18n from "~/plugins/i18n";
import { OTHER_BROADCAST, V_BROADCAST } from "~/constants/constant";

const defaultForm = {
    brandname: null,
    br_type: null,
    status: null
};

export default {
    name: "ListFilter",
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
            statusOptions: {
                placeholder: this.$t("brandname.list.placeholder.active"),
                multiple: true,
                searchable: true,
                options: [
                    {
                        id: 1,
                        text: this.$t("label.active")
                    },
                    {
                        id: 0,
                        text: this.$t("label.disable")
                    }
                ]
            },
            brTypeOptions: {
                placeholder: this.$t("brandname.list.placeholder.br_type"),
                multiple: true,
                searchable: true,
                options: [
                    {
                        id: V_BROADCAST,
                        text: "Đầu số Viettel quản lý"
                    },
                    {
                        id: OTHER_BROADCAST,
                        text: "Đầu số đối tác quản lý"
                    }
                ]
            }
        };
    },
    computed: {},
    watch: {
        async isRequiredToExport() {
            if (this.isRequiredToExport == true) {
                await this.exportFile();
                this.$emit("isExportFileSuccessfully");
            }
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
        reset() {
            this.form = new Form(defaultForm);
        },
        filter() {
            let searchParams = {};

            if (this.form.brandname) {
                searchParams.brandname = this.form.brandname;
            }

            if (this.form.br_type) {
                searchParams.br_type = this.form.br_type.map(e => {
                    return e.id;
                });
            }

            if (this.form.status) {
                searchParams.status = this.form.status.map(e => {
                    return e.id;
                });
            }
            console.log(searchParams);
            return searchParams;
        },
        async exportFile() {
            let searchParams = this.filter();

            let { data } = await axios.post(
                "/brandname/list/listing",
                searchParams
            );
            if (data != 0) {
                try {
                    let res = await axios.post(
                        "/brandname/list/export",
                        searchParams,
                        {
                            responseType: "blob"
                        }
                    );

                    downloadFile(res);
                } catch (e) {
                    notify(this.$t("brandname.list.notification.export_error"));
                }
            } else {
                notifyTryAgain();
            }
        }
    }
};
</script>

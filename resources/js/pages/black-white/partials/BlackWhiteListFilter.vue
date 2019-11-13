<template>
    <div>
        <div class="row" @keydown.enter.prevent="search">
            <div class="col-md-4">
                <form-control
                    v-model="form.type"
                    :type="'select'"
                    :select-options="typeOptions"
                    :label="$t('blackwhite.list.type')"
                >
                </form-control>
            </div>

            <div class="col-md-4">
                <form-control
                    v-model="form.provider"
                    :type="'select'"
                    :select-options="providerOptions"
                    :label="$t('blackwhite.list.provider')"
                >
                </form-control>
            </div>

            <div class="col-md-4">
                <manager-chosen
                    v-model="form.manager"
                    :required="false"
                ></manager-chosen>
            </div>

            <div class="col-md-4">
                <label>{{ $t("common.who_created") }}</label>
                <user-chosen
                    v-model="form.who_created"
                    :multiple="true"
                ></user-chosen>
            </div>

            <div class="col-md-4">
                <label>{{ $t("common.who_updated") }}</label>
                <user-chosen
                    v-model="form.who_updated"
                    :multiple="true"
                ></user-chosen>
            </div>

            <div class="col-md-4">
                <the-date-range
                    v-model="form.created_at"
                    :inline="false"
                    name="created_at"
                    :label="$t('common.when_created')"
                    :type="'datetimerange'"
                    :format="'yyyy-MM-dd HH:mm:ss'"
                    :disabled-date="'greaterThanToday'"
                    :name-shortcut="['last_7_days', 'last_30_days']"
                    :shortcut="true"
                    :default-time="[Date(), '23:59:59']"
                >
                </the-date-range>
            </div>

            <div class="col-md-4">
                <the-date-range
                    v-model="form.updated_at"
                    :inline="false"
                    name="updated_at"
                    :label="$t('common.when_updated')"
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
                    color="info"
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
import ManagerChosen from "~/components/elements/chosens/ManagerChosen";
import Form from "vform";
import axios from "axios";
import { downloadFile } from "~/helpers/downloadFile";
import { notify, notifyTryAgain } from "~/helpers/bootstrap-notify";
import {
    PROVIDER_VIETTEL,
    PROVIDER_VINAPHONE,
    PROVIDER_MOBILEPHONE,
    PROVIDER_VIETNAMMOBILE,
    PROVIDER_GMOBILE,
    PROVIDER_ALL,
    PROVIDER_OTHER,
    TYPE_BLACKLIST,
    TYPE_WHITELIST
} from "~/constants/constant";
import TheDateRange from "~/components/common/TheDateRange";
import UserChosen from "../../../components/elements/chosens/UserChosen";

const defaultForm = {
    who_created: null,
    who_updated: null,
    created_at: null,
    updated_at: null,
    provider: null,
    type: null,
    manager: null
};

export default {
    name: "BlackWhiteListFilter",
    components: { UserChosen, ManagerChosen, TheDateRange },
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
            typeOptions: {
                placeholder: this.$t("blackwhite.list.placeholder.type"),
                multiple: true,
                searchable: false,
                options: [
                    {
                        id: -1,
                        text: "Tất cả"
                    },
                    {
                        id: TYPE_WHITELIST,
                        text: "Whitelist"
                    },
                    {
                        id: TYPE_BLACKLIST,
                        text: "Blacklist"
                    }
                ]
            },
            providerOptions: {
                placeholder: this.$t("blackwhite.list.placeholder.provider"),
                multiple: true,
                searchable: false,
                options: [
                    {
                        id: PROVIDER_VIETTEL,
                        text: "Viettel"
                    },
                    {
                        id: PROVIDER_VINAPHONE,
                        text: "Vinaphone"
                    },
                    {
                        id: PROVIDER_MOBILEPHONE,
                        text: "Mobilephone"
                    },
                    {
                        id: PROVIDER_VIETNAMMOBILE,
                        text: "Vietnammobile"
                    },
                    {
                        id: PROVIDER_GMOBILE,
                        text: "Gmobile"
                    },
                    {
                        id: PROVIDER_OTHER,
                        text: "Nhà mạng khác"
                    }
                ]
            },
            userOptions: {
                placeholder: this.$t("blackwhite.list.placeholder.user"),
                multiple: true,
                searchable: true,
                options: [],
                textField: "name",
                idField: "id"
            },
            userList: []
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
    mounted() {
        this.loadingData();
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

            if (this.form.who_created) {
                searchParams.who_created = this.form.who_created.map(e => {
                    return e.text;
                });
            }

            if (this.form.created_at) {
                searchParams.created_at = this.form.created_at;
            }

            if (this.form.who_updated) {
                searchParams.who_updated = this.form.who_updated.map(e => {
                    return e.text;
                });
            }

            if (this.form.updated_at) {
                searchParams.updated_at = this.form.updated_at;
            }

            if (this.form.type) {
                searchParams.type = this.form.type.map(e => {
                    return e.id;
                });
            }

            if (this.form.provider) {
                searchParams.provider = this.form.provider.map(e => {
                    return e.id;
                });
            }

            if (this.form.manager) {
                searchParams.manager = this.form.manager.map(e => {
                    return e.id;
                });
            }

            if (this.form.manager) {
                searchParams.managerName = this.form.manager.map(e => {
                    return e.name;
                });
            }

            return searchParams;
        },
        async exportFile() {
            let searchParams = this.filter();

            let { data } = await axios.post(
                "/blackwhite/list/listing",
                searchParams
            );
            if (data != 0) {
                try {
                    let res = await axios.post(
                        "/blackwhite/list/export",
                        searchParams,
                        {
                            responseType: "blob"
                        }
                    );

                    downloadFile(res);
                } catch (e) {
                    notify(this.$t("notification.export_error"));
                }
            } else {
                notifyTryAgain();
            }
        },
        async getUser() {
            try {
                const res = await axios.post("/admin/user/listing");
                const { data } = res;

                this.userList = data.data;
            } catch (e) {
                console.log(e);
            }
        },
        async loadingData() {
            await this.getUser();

            for (var i = 0; i < this.userList.length; i++) {
                this.userOptions.options.push({
                    id: this.userList[i].id,
                    text: this.userList[i].name
                });
            }
        }
    }
};
</script>

<style scoped></style>

<template>
    <div>
        <div class="row" @keydown.enter.prevent="search">
            <div class="col-md-6">
                <device-type-chosen
                    v-model="form.device_type"
                    :multiple="true"
                    :required="false"
                ></device-type-chosen>
            </div>

            <div class="col-md-6">
                <form-control
                        v-model="form.status"
                        :type="'select'"
                        :select-options="statusOptions"
                        :label="this.$t('label.status')"
                >
                </form-control>
            </div>

            <div class="col-md-6">
                <the-date-range
                    v-model="form.start_date"
                    :inline="false"
                    :label="'Thời gian mượn'"
                    :type="'datetimerange'"
                    :format="'dd/MM/yyyy HH:mm:ss'"
                    :name-shortcut="['last_7_days', 'last_30_days']"
                    :shortcut="true"
                    :default-time="['00:00:00', '23:59:59']"
                ></the-date-range>
            </div>

            <div class="col-md-6">
                <the-date-range
                        v-model="form.due_date"
                        :inline="false"
                        :label="'Thời gian trả'"
                        :type="'datetimerange'"
                        :format="'dd/MM/yyyy HH:mm:ss'"
                        :name-shortcut="['last_7_days', 'last_30_days']"
                        :shortcut="true"
                        :default-time="['00:00:00', '23:59:59']"
                ></the-date-range>
            </div>

            <div class="col-md-6">
                <project-chosen
                    v-model="form.project"
                    :multiple="true"
                    :required="false"
                ></project-chosen>
            </div>

            <div class="col-md-6">
                <leader-chosen
                    v-model="form.leader"
                    :multiple="true"
                    :required="false"
                ></leader-chosen>
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
    </div></template
>

<script>
import Form from "vform";
import axios from "axios";
import { downloadFile } from "~/helpers/downloadFile";
import { notify, notifyTryAgain } from "~/helpers/bootstrap-notify";
import TheDateRange from "~/components/common/TheDateRange";
import DeviceTypeChosen from "../../../components/elements/chosens/DeviceTypeChosen";
import ProjectChosen from "../../../components/elements/chosens/ProjectChosen";
import LeaderChosen from "../../../components/elements/chosens/LeaderChosen";
const defaultForm = {
    start_date: null,
    due_date: null,
    device_type: null,
    status: null,
    project: null,
    leader: null
};
export default {
    name: "RentFilter",
    components: {LeaderChosen, ProjectChosen, DeviceTypeChosen, TheDateRange },
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
                placeholder: this.$t("admin.users.placeholder.select_status"),
                multiple: true,
                searchable: true,
                options: [
                    {
                        id: 1,
                        text: "Đang mượn"
                    },
                    {
                        id: 0,
                        text: "Đã trả"
                    },
                    {
                        id: 2,
                        text: "Chờ phê duyệt"
                    },
                    {
                        id: 3,
                        text: "Bị từ chối"
                    },
                    {
                        id: 4,
                        text: "Đã phê duyệt"
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
            if (this.form.status) {
                searchParams.status = this.form.status.map(e => {
                    return e.id;
                });
            }
            if (this.form.device_type) {
                searchParams.device_type = this.form.device_type.map(e => {
                    return e.id;
                });
            }

            if (this.form.project) {
                searchParams.project = this.form.project.map(e => {
                    return e.id;
                });
            }

            if (this.form.leader) {
                searchParams.leader = this.form.leader.map(e => {
                    return e.id;
                });
            }

            if (this.form.start_date) {
                searchParams.start_date = this.form.start_date;
            }

            if (this.form.due_date) {
                searchParams.due_date = this.form.due_date;
            }

            if (this.form.device_type) {
                searchParams.device_type_name = this.form.device_type.map(e => {
                    return e.name;
                });
            }

            return searchParams;
        },
        async exportFile() {
            let searchParams = this.filter();
            let { data } = await axios.post("/rent/listing", searchParams);
            if (data != 0) {
                try {
                    let res = await axios.post("/rent/export", searchParams, {
                        responseType: "blob"
                    });
                    downloadFile(res);
                } catch (e) {
                    notify(this.$t("notification.export_error"));
                }
            } else {
                notifyTryAgain();
            }
        }
    }
};
</script>

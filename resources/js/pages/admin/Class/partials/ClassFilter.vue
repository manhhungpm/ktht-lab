<template>
    <div>
        <div class="row" @keydown.enter.prevent="search">
            <div class="col-md-6">
                <faculty-chosen v-model="form.faculty" :multiple="true" :required="false"></faculty-chosen>
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
                <!--<v-button-->
                        <!--color="info"-->
                        <!--style-type="air"-->
                        <!--class="m-btn m-btn&#45;&#45;icon"-->
                        <!--style="margin-right: 5px"-->
                        <!--@click.native="exportFile"-->
                <!--&gt;-->
                    <!--<span>-->
                        <!--<i class="la la-cloud-download"></i>-->
                        <!--<span>{{ $t("button.export") }}</span>-->
                    <!--</span>-->
                <!--</v-button>-->
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
    import axios from "axios";
    import {downloadFile} from "~/helpers/downloadFile";
    import {notify, notifyTryAgain} from "~/helpers/bootstrap-notify";
    import TheDateRange from "~/components/common/TheDateRange";
    import FacultyChosen from "../../../../components/elements/chosens/FacultyChosen";

    const defaultForm = {
        faculty: null,
        status: null
    };

    export default {
        name: "ClassFilter",
        components: {FacultyChosen, TheDateRange},
        props: {
            onActionSuccess: {
                type: Function,
                default: () => {
                }
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
                            text: this.$t("label.active")
                        },
                        {
                            id: 0,
                            text: this.$t("label.disable")
                        }
                    ]
                },
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

                if (this.form.status) {
                    searchParams.status = this.form.status.map(e => {
                        return e.id;
                    });
                }

                if (this.form.faculty) {
                    searchParams.faculty = this.form.faculty.map(e => {
                        return e.id;
                    });
                }

                return searchParams;
            },
            async exportFile() {
                let searchParams = this.filter();

                let {data} = await axios.post(
                    "/device/device-group/listing",
                    searchParams
                );
                if (data != 0) {
                    try {
                        let res = await axios.post(
                            "/device/device-group/export",
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
        }
    }
</script>

<style scoped>

</style>
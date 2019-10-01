<template>
    <modal
        id="modal"
        ref="modalImport"
        :title="$t('blackwhite.list.import_title')"
        data-backdrop="static"
        data-keyboard="false"
    >
        <div
            v-if="alertMessage"
            id="alert"
            class="alert alert-danger"
            role="alert"
        >
            <strong>{{ $t("blackwhite.list.import_modal.error") }}:</strong>
            <div class="view">
                <div class="wrapper">
                    <p class="text" style="max-height: 42px ; overflow: hidden">
                        <strong>
                            <li v-for="alertMessages in alertMessage">
                                <strong>{{ alertMessages[0] }}</strong>
                            </li>
                        </strong>
                    </p>
                    <p
                        v-if="this.alertMessage.length > 2"
                        id="btnRm"
                        class="button"
                        @click="readMore"
                    >
                        <a href="#" style="color: white">{{
                            $t("blackwhite.list.import_modal.readmore")
                        }}</a>
                    </p>
                </div>
            </div>
        </div>

        <div
            class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-brand alert-dismissible fade show"
            role="alert"
        >
            <div class="m-alert__icon">
                <i class="flaticon-exclamation-1"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <strong
                    >{{ $t("blackwhite.list.import_modal.report") }}:</strong
                >
                {{ $t("blackwhite.list.import_modal.import") }}<br />
                <strong
                    >{{
                        $t("blackwhite.list.import_modal.example_file")
                    }}:</strong
                >
                <a
                    href="/public/example/blackwhite_import_example.xlsx"
                    download="File_import_mau.xlsx"
                    ><i
                        ><u>{{ $t("blackwhite.list.import_modal.file") }}</u></i
                    ></a
                ><br />
                <strong class="m--font-danger"
                    >{{ $t("blackwhite.list.import_modal.note") }}:</strong
                >
                <a class="m--font-danger">{{
                    $t("blackwhite.list.import_modal.wait_import")
                }}</a>
            </div>
        </div>

        <div>
            <label>Upload file:</label>
            <form ref="importForm" method="post" enctype="multipart/form-data">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                        <div></div>
                        <div class="custom-file">
                            <input
                                id="file"
                                type="file"
                                name="file"
                                class="custom-file-input"
                                accept=".xlsx, .xls, .csv"
                            />
                            <label class="custom-file-label" for="file">{{
                                $t("blackwhite.list.import_modal.select_file")
                            }}</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <template slot="footer">
            <button
                id="cancel"
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
            >
                {{ $t("button.cancel") }}
            </button>
            <v-button
                color="primary"
                class="btn btn-primary"
                :loading="loadingImport"
                type="submit"
                @click.native="importFile"
                >Import
            </v-button>
        </template>
    </modal>
</template>

<script>
import axios from "axios";
import { notifyImportSuccess } from "~/helpers/bootstrap-notify";
export default {
    name: "ListImportModal",
    components: {},
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            file: "",
            alertMessage: null,
            loadingImport: false,
            recordSuccessful: 0
        };
    },
    mounted() {
        this.handleEvents();
    },
    methods: {
        readMore() {
            let defaultHeight = 42;
            let text = $(".text");
            let textHeight = text[0].scrollHeight;

            let newHeight = 0;
            if (text.hasClass("active")) {
                newHeight = defaultHeight;
                text.removeClass("active");
                document.getElementById("btnRm").innerHTML =
                    '<a href="#" style="color: white">Xem thêm</a>';
            } else {
                newHeight = textHeight;
                text.addClass("active");
                document.getElementById("btnRm").innerHTML =
                    '<a href="#" style="color: white">Ẩn đi</a>';
            }
            text.animate(
                {
                    "max-height": newHeight
                },
                500
            );
            console.log(newHeight);
        },
        handleEvents() {
            let $this = this;
            // let fieldVal;
            $(this.$el).on("hide.bs.modal", function() {
                $this.alertMessage = null;
                $("input[type=file]").val("");
            });

            //Input file
            $("input[type=file]").change(function() {
                let fieldVal = $(this).val();
                fieldVal = fieldVal.replace("C:\\fakepath\\", "");
                if (fieldVal != undefined || fieldVal != "") {
                    $(this)
                        .next(".custom-file-label")
                        .attr("data-content", fieldVal);
                    $(this)
                        .next(".custom-file-label")
                        .text(fieldVal);
                }
            });

            //Disable icon close
            $("#modal .close").css("display", "none");
        },
        show() {
            this.$refs.modalImport.show();
        },
        importFile() {
            let vm = this;
            let data = new FormData(vm.$refs.importForm);
            $("#cancel").prop("disabled", true);
            if (!vm.loadingImport) {
                vm.loadingImport = true;
                axios
                    .post("/blackwhite/list/import", data, {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    })
                    .then(response => {
                        notifyImportSuccess(this.$t(""));
                        this.$refs.modalImport.hide();
                        this.onActionSuccess();
                        vm.loadingImport = false;
                        $("#cancel").prop("disabled", false);
                    })
                    .catch(function(error) {
                        // notifyTryAgain();
                        // vm.alertMessage = error.response.data.errors.row;
                        vm.alertMessage = error.response.data.errors;
                        vm.loadingImport = false;
                        $("#cancel").prop("disabled", false);
                    });
            }
        }
    }
};
</script>

<style scoped></style>

<template>
    <modal
        ref="modal"
        :title="isEdit ? $t('blackwhite.list.edit') : $t('blackwhite.list.add')"
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <form-control
                v-model="form.alias"
                v-validate="'required|max:15'"
                name="alias"
                :label="$t('blackwhite.list.alias')"
                :placeholder="$t('blackwhite.list.placeholder.alias')"
                :error="errors.first('alias') || form.errors.get('alias')"
                :required="true"
                :data-vv-as="$t('blackwhite.list.placeholder.alias')"
            ></form-control>
            <form-control
                v-model="form.typeId"
                v-validate="'required'"
                :label="$t('blackwhite.list.type')"
                :data-vv-as="$t('blackwhite.list.placeholder.type')"
                :required="true"
                name="typeId"
                :type="'select'"
                :select-options="typeOptions"
                :error="errors.first('typeId') || form.errors.get('typeId')"
            ></form-control>
            <form-control
                v-model="form.providerId"
                v-validate="'required'"
                :label="$t('blackwhite.list.provider')"
                :data-vv-as="$t('blackwhite.list.placeholder.provider')"
                :required="true"
                name="providerId"
                :type="'select'"
                :select-options="providerOptions"
                :error="
                    errors.first('providerId') || form.errors.get('providerId')
                "
            ></form-control>
            <manager-chosen
                v-model="form.manager_result"
                v-validate="'required'"
                :multiple="false"
                name="manager"
                :error="errors.first('manager')"
                :has-all-option="false"
            ></manager-chosen>
            <form-control
                v-model="form.description"
                v-validate="'max:255'"
                :type="'area'"
                :label="$t('label.description')"
                :placeholder="$t('blackwhite.list.placeholder.description')"
                name="description"
            ></form-control>
            <div
                v-if="!hidden"
                class="form-group m-form__group"
                :class="{
                    'has-danger':
                        errors.first('file') || form.errors.get('file')
                }"
            >
                <label>{{ $t("blackwhite.list.file") }}</label>
                <div v-if="isEdit" class="file-list">
                    <div
                        v-for="(file, index) in form.oldFile"
                        :key="'file' + index"
                        class="row col"
                    >
                        {{ index + 1 }}.
                        <a :href="`/storage/${file.path}`" target="_blank">{{
                            file.name
                        }}</a>
                        <a
                            href="javascript:;"
                            style="margin: -6px 5px;"
                            class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill table-action"
                            title=" Xóa "
                            @click="deleteFile(file, index)"
                        >
                            <i class="la la-trash"></i>
                        </a>
                    </div>
                </div>
                <file-upload
                    v-model="form.newFile"
                    :upload-multiple="false"
                    :title="$t('component.dropzone.title')"
                    :desc="$t('component.dropzone.desc')"
                    :max-filesize="5"
                    accepted-files=".pdf"
                >
                </file-upload>
                <div
                    v-if="errors.first('file') || form.errors.get('file')"
                    class="form-control-feedback"
                >
                    {{ errors.first("file") }}
                    <br />
                    {{ form.errors.get("file") }}
                </div>
            </div>
        </form>
        <template slot="footer">
            <button class="btn btn-info" @click="closeModal">
                {{ $t("button.cancel") }}
            </button>
            <button class="btn btn-primary" @click="validateForm('form')">
                {{ isEdit ? $t("button.edit") : $t("button.add") }}
            </button>
        </template>
    </modal>
</template>

<script>
import Modal from "~/components/common/Modal";
import FormControl from "~/components/common/FormControl";
import ManagerChosen from "~/components/elements/chosens/ManagerChosen";
import Form from "vform";
import {
    FORM_LABEL_WIDTH_LARGE,
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
import { SUCCESS } from "~/constants/code";
import {
    notifyTryAgain,
    notifyUpdateSuccess,
    notifyAddSuccess
} from "~/helpers/bootstrap-notify";

const defaultForm = {
    id: null,
    alias: null,
    typeId: {
        id: TYPE_WHITELIST
    },
    type: null,
    providerId: {
        id: PROVIDER_ALL
    },
    provider: null,
    manager_result: null,
    manager: null,
    description: null,
    newFile: [],
    oldFile: []
};

export default {
    name: "ListModal",
    components: { FormControl, Modal, ManagerChosen },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            form: new Form(defaultForm),
            formLabelWidth: FORM_LABEL_WIDTH_LARGE,
            isEdit: false,
            hidden: false,
            typeOptions: {
                placeholder: this.$t("blackwhite.list.placeholder.type"),
                multiple: false,
                searchable: false,
                options: [
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
                multiple: false,
                searchable: false,
                options: [
                    {
                        id: PROVIDER_ALL,
                        text: "Tất cả"
                    },
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
            }
        };
    },
    methods: {
        show(item = null) {
            if (item != null) {
                item.typeId = {
                    id: item.type
                };
                item.providerId = {
                    id: item.provider
                };
                item.manager_result = {
                    id: item.manager.id,
                    text: item.manager.name,
                    name: item.manager.name
                };
                this.form = new Form(item);
                this.form.newFile = [];
                this.$set(this.form, "oldFile", JSON.parse(item.file));
                // this.form.oldFile = JSON.parse(item.file);
                this.isEdit = true;
            }
            this.hidden = false;
            this.$refs.modal.show();
        },
        closeModal() {
            this.$refs.modal.hide();
        },
        validateForm() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    if (this.isEdit) {
                        this.editAlias();
                    } else {
                        this.addAlias();
                    }
                }
            });
        },
        async editAlias() {
            try {
                this.form.type = this.form.typeId.id;
                this.form.provider = this.form.providerId.id;
                this.form.manager = this.form.manager_result.id;
                this.form.file = JSON.stringify(this.form.newFile);

                const res = await this.form.post("/blackwhite/list/edit");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyUpdateSuccess();
                    this.closeModal();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                const { status } = e.response;

                if (status != 403) {
                    notifyTryAgain();
                }
            }
        },
        async addAlias() {
            try {
                this.form.type = this.form.typeId.id;
                this.form.provider = this.form.providerId.id;
                this.form.manager = this.form.manager_result.id;
                this.form.file = JSON.stringify(this.form.newFile);

                const res = await this.form.post("/blackwhite/list/add");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyAddSuccess();
                    this.closeModal();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                const { status } = e.response;

                if (status != 403) {
                    notifyTryAgain();
                }
            }
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = false;
            this.hidden = true;
            this.$validator.reset();
        },

        deleteFile(file, index) {
            this.form.oldFile.splice(index, 1);
        }
    }
};
</script>

<style scoped></style>

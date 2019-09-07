<template>
    <modal
        ref="modal"
        :title="isEdit ? $t('admin.api.edit_api') : $t('admin.api.add_api')"
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm('form')"
        >
            <form-control
                v-if="isEdit !== true"
                v-model="form.name"
                v-validate="'required'"
                :label="$t('admin.api.name')"
                name="name"
                autocomplete="off"
                :placeholder="$t('admin.api.placeholder.name')"
                :error="errors.first('name') || form.errors.get('name')"
                :required="true"
                :data-vv-as="$t('admin.api.placeholder.name')"
            >
            </form-control>
            <form-control
                v-model="form.display_name"
                v-validate="'required'"
                :label="$t('admin.api.display_name')"
                name="display_name"
                autocomplete="off"
                :placeholder="$t('admin.api.placeholder.display_name')"
                :error="
                    errors.first('display_name') ||
                        form.errors.get('display_name')
                "
                :required="true"
                :data-vv-as="$t('admin.api.placeholder.display_name')"
            >
            </form-control>
            <form-control
                v-model="form.description"
                :label="$t('admin.api.description')"
                :label-width="formLabelWidth"
                name="description"
                autocomplete="off"
                :placeholder="$t('admin.api.placeholder.description')"
                :type="'area'"
            >
            </form-control>
        </form>
        <template slot="footer">
            <button class="btn btn-info" @click="closeModal">
                {{ $t("button.cancel") }}
            </button>
            <button class="btn btn-primary" @click="validateForm('form')">
                {{ isEdit ? $t("button.edit") : $t("button.add") }}
            </button>
        </template>
    </modal></template
>

<script>
import Form from "vform";

import { FORM_LABEL_WIDTH } from "~/constants/constant";
import FormControl from "~/components/common/FormControl";
import { SUCCESS } from "~/constants/code";
import {
    notifyTryAgain,
    notifyUpdateSuccess,
    notifyAddSuccess,
    notifyNoPermission
} from "~/helpers/bootstrap-notify";

const defaultForm = {
    id: "",
    name: "",
    display_name: "",
    description: ""
};
export default {
    name: "ManageApiModal",
    components: { FormControl },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            form: new Form(defaultForm),
            formLabelWidth: FORM_LABEL_WIDTH,
            isEdit: false
        };
    },
    computed: {},
    methods: {
        show(item = null) {
            if (item != null) {
                this.isEdit = true;
                this.form = new Form(item);
            }
            this.$refs.modal.show();
        },
        closeModal() {
            this.$refs.modal.hide();
        },
        validateForm() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    if (this.isEdit) {
                        this.editApi();
                    } else {
                        this.addApi();
                    }
                }
            });
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = false;
            this.$validator.reset();
        },
        async addApi() {
            try {
                const res = await this.form.post("/admin/manage-api/add-api");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyAddSuccess(
                        this.$t("admin.api.notification.add_success")
                    );
                    this.closeModal();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                const { status } = e.response;

                if (status != 403) {
                    notifyNoPermission();
                }
            }
        },
        async editApi() {
            try {
                const res = await this.form.post("/admin/manage-api/edit-api");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyUpdateSuccess(
                        this.$t("admin.api.notification.edit_success")
                    );
                    this.closeModal();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                const { status } = e.response;

                if (status != 403) {
                    notifyNoPermission();
                }
            }
        }
    }
};
</script>

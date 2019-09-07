<template>
    <modal
        ref="modal"
        :title="isEdit ? $t('admin.configs.edit') : $t('admin.configs.add')"
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm('form')"
        >
            <form-control
                v-model="form.group_name"
                v-validate="'required'"
                :label="$t('admin.configs.group_name')"
                name="group_name"
                autocomplete="off"
                :placeholder="$t('admin.configs.placeholder.group_name')"
                :error="
                    errors.first('group_name') || form.errors.get('group_name')
                "
                :required="true"
                :data-vv-as="$t('admin.configs.placeholder.group_name')"
            >
            </form-control>
            <form-control
                v-model="form.name"
                v-validate="'required'"
                :label="$t('admin.configs.name')"
                name="name"
                autocomplete="off"
                :placeholder="$t('admin.configs.placeholder.name')"
                :error="errors.first('name') || form.errors.get('name')"
                :required="true"
                :data-vv-as="$t('admin.configs.placeholder.name')"
            >
            </form-control>
            <form-control
                v-model="form.value"
                v-validate="'required'"
                :label="$t('admin.configs.value')"
                name="value"
                autocomplete="off"
                :placeholder="$t('admin.configs.placeholder.value')"
                :error="errors.first('value') || form.errors.get('value')"
                :required="true"
                :data-vv-as="$t('admin.configs.placeholder.value')"
            >
            </form-control>
            <form-control
                v-model="form.description"
                :label="$t('admin.configs.description')"
                name="description"
                autocomplete="off"
                :placeholder="$t('admin.configs.placeholder.description')"
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
    group_name: "",
    description: "",
    value: ""
};

export default {
    name: "ConfigModal",
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
                        this.editConfig();
                    } else {
                        this.addConfig();
                    }
                }
            });
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = false;
            this.$validator.reset();
        },
        async addConfig() {
            try {
                const res = await this.form.post("/admin/configs/add");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyAddSuccess(
                        this.$t("admin.configs.notification.add_success")
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
        async editConfig() {
            try {
                const res = await this.form.post("/admin/configs/edit");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyUpdateSuccess(
                        this.$t("admin.configs.notification.edit_success")
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

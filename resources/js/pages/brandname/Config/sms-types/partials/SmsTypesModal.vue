<template>
    <modal
        ref="modal"
        :title="
            isEdit
                ? $t('brandname.sms_types.edit')
                : $t('brandname.sms_types.add')
        "
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <form-control
                v-model="form.name"
                v-validate="'required|max:255'"
                :label="$t('brandname.sms_types.name')"
                name="name"
                autocomplete="off"
                :placeholder="$t('brandname.sms_types.placeholder.name')"
                :error="errors.first('name') || form.errors.get('name')"
                :required="true"
                :data-vv-as="$t('brandname.sms_types.placeholder.name')"
            >
            </form-control>
            <form-control
                v-model="form.group"
                v-validate="'required'"
                :label="$t('brandname.sms_types.group')"
                name="group"
                :placeholder="$t('brandname.sms_types.placeholder.group')"
                :type="'select'"
                :select-options="groupOptions"
                :error="errors.first('group') || form.errors.get('group')"
                :required="true"
                :data-vv-as="$t('brandname.sms_types.placeholder.group')"
            >
            </form-control>
            <form-control
                v-model="form.priority"
                v-validate="'required|numeric'"
                :label="$t('brandname.sms_types.priority')"
                name="priority"
                autocomplete="off"
                :placeholder="$t('brandname.sms_types.placeholder.priority')"
                :error="errors.first('priority') || form.errors.get('priority')"
                :required="true"
                :data-vv-as="$t('brandname.sms_types.placeholder.priority')"
            >
            </form-control>
            <form-control
                v-model="form.alias"
                v-validate="'max:100'"
                :label="$t('brandname.sms_types.alias')"
                name="alias"
                autocomplete="off"
                :placeholder="$t('brandname.sms_types.placeholder.alias')"
                :error="errors.first('alias') || form.errors.get('alias')"
                :data-vv-as="$t('brandname.sms_types.placeholder.alias')"
            >
            </form-control>
            <form-control
                v-model="form.prefix"
                v-validate="'max:100'"
                :label="$t('brandname.sms_types.prefix')"
                name="prefix"
                autocomplete="off"
                :placeholder="$t('brandname.sms_types.placeholder.prefix')"
                :error="errors.first('prefix') || form.errors.get('prefix')"
                :data-vv-as="$t('brandname.sms_types.placeholder.prefix')"
            >
            </form-control>
            <form-control
                v-model="form.description"
                v-validate="'required|max:255'"
                :label="$t('brandname.sms_types.description')"
                name="description"
                autocomplete="off"
                :placeholder="$t('brandname.sms_types.placeholder.description')"
                :type="'area'"
                :error="
                    errors.first('description') ||
                        form.errors.get('description')
                "
                :required="true"
                :data-vv-as="$t('brandname.sms_types.placeholder.description')"
            >
            </form-control>
        </form>
        <template slot="footer">
            <button class="btn btn-info" @click="closeModal">
                {{ $t("button.cancel") }}
            </button>
            <button class="btn btn-primary" @click="validateForm">
                {{ isEdit ? $t("button.edit") : $t("button.add") }}
            </button>
        </template>
    </modal>
</template>

<script>
import Form from "vform";
import axios from "axios";
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
    description: "",
    alias: "",
    prefix: "",
    priority: "",
    group: null
};
export default {
    name: "SmsTypesModal",
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
            isEdit: false,
            groupOptions: {
                placeholder: this.$t("brandname.sms_types.placeholder.group"),
                multiple: false,
                searchable: false,
                ajax: "/brandname/sms-groups/listing-all",
                textField: "name",
                idField: "id"
            },
            groupList: []
        };
    },
    computed: {},
    mounted() {},
    methods: {
        show(item = null) {
            if (item != null) {
                this.isEdit = true;
                item.group = item.brandname_sms_group;
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
                        this.editSmsTypes();
                    } else {
                        this.addSmsTypes();
                    }
                }
            });
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = false;
            this.$validator.reset();
        },
        async addSmsTypes() {
            var id = this.form.group.id;
            this.form.group = id;

            try {
                const res = await this.form.post("/brandname/sms-types/add");
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

                if (status !== 403 || status !== 422) {
                    notifyNoPermission();
                }
            }
        },
        async editSmsTypes() {
            var id = this.form.group.id;
            this.form.group = id;

            try {
                const res = await this.form.post("/brandname/sms-types/edit");
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

                if (status !== 403 && status !== 422) {
                    notifyNoPermission();
                }
            }
        }
    }
};
</script>

<style scoped></style>

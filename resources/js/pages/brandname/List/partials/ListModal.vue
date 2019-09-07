<template>
    <modal
        ref="modal"
        :title="
            isEdit ? $t('admin.users.edit_user') : $t('admin.users.add_user')
        "
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm('form')"
        >
            <form-control
                v-model="form.brandname"
                v-validate="'required'"
                name="brandname"
                :label="$t('brandname.list.brandname')"
                :placeholder="$t('brandname.list.placeholder.brandname')"
                :error="
                    errors.first('brandname') || form.errors.get('brandname')
                "
                :required="true"
                :data-vv-as="$t('brandname.list.placeholder.brandname')"
            >
            </form-control>
            <form-control
                v-model="form.brType"
                v-validate="'required'"
                label="Chọn loại"
                :data-vv-as="$t('brandname.list.placeholder.br_type')"
                :required="true"
                name="brType"
                :type="'select'"
                :select-options="brTypeOptions"
                :error="errors.first('brType') || form.errors.get('brType')"
            />
            <form-control
                v-model="form.reason"
                v-validate="'required'"
                :label="$t('brandname.list.reason')"
                :placeholder="$t('brandname.list.placeholder.reason')"
                :error="errors.first('reason') || form.errors.get('reason')"
                :required="true"
                :data-vv-as="$t('brandname.list.placeholder.reason')"
                name="reason"
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

import {
    OTHER_BROADCAST,
    V_BROADCAST,
    FORM_LABEL_WIDTH_LARGE
} from "~/constants/constant";
import FormControl from "~/components/common/FormControl";
import { SUCCESS } from "~/constants/code";
import {
    notifyTryAgain,
    notifyUpdateSuccess,
    notifyAddSuccess
} from "~/helpers/bootstrap-notify";

const defaultForm = {
    id: "",
    brandname: "",
    reason: "",
    brType: {
        id: V_BROADCAST
    },
    br_type: ""
};
export default {
    name: "ListModal",
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
            formLabelWidth: FORM_LABEL_WIDTH_LARGE,
            isEdit: false,
            brTypeOptions: {
                placeholder: "Chọn loại đối tượng",
                multiple: false,
                searchable: false,
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
    computed: {
        brTypeOptions1() {
            return [
                {
                    value: OTHER_BROADCAST,
                    label: this.$t("label.other_broadcast")
                },
                {
                    value: V_BROADCAST,
                    label: this.$t("label.v_broadcast")
                }
            ];
        }
    },
    methods: {
        show(item = null) {
            if (item != null) {
                item.brType = {
                    id: item.br_type
                };
                this.form = new Form(item);
                this.isEdit = true;
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
                        this.editBrandname();
                    } else {
                        this.addBrandname();
                    }
                }
            });
        },
        async editBrandname() {
            try {
                const res = await this.form.post("/brandname/list/edit");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyUpdateSuccess(
                        this.$t("brandname.list.notification.edit_success")
                    );
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
        async addBrandname() {
            try {
                this.form.br_type = this.form.brType.id;

                const res = await this.form.post("/brandname/list/add");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyAddSuccess(
                        this.$t("brandname.list.notification.add_success")
                    );
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
            this.$validator.reset();
        }
    }
};
</script>

<style scoped></style>

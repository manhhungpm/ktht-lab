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
                v-model="form.date_threshold"
                v-validate="'required'"
                name="date_threshold"
                :label="$t('brandname.config.date_threshold')"
                :placeholder="$t('brandname.config.placeholder.date_threshold')"
                :error="
                    errors.first('date_threshold') ||
                        form.errors.get('date_threshold')
                "
                :required="true"
                :data-vv-as="$t('brandname.config.placeholder.date_threshold')"
            >
            </form-control>
            <form-control
                v-model="form.month_threshold"
                v-validate="'required'"
                name="month_threshold"
                :label="$t('brandname.config.month_threshold')"
                autocomplete="off"
                :placeholder="
                    $t('brandname.config.placeholder.month_threshold')
                "
                :error="
                    errors.first('month_threshold') ||
                        form.errors.get('month_threshold')
                "
                :required="true"
                :data-vv-as="$t('brandname.config.placeholder.month_threshold')"
            >
            </form-control>
            <form-control
                v-model="form.brType"
                v-validate="'required'"
                label="Chọn loại"
                :data-vv-as="$t('brandname.config.placeholder.br_type')"
                :required="true"
                name="brType"
                :type="'select'"
                :select-options="brTypeOptions"
                :error="errors.first('brType') || form.errors.get('brType')"
            />
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
    notifyAddSuccess,
    notifyNoPermission
} from "~/helpers/bootstrap-notify";

const defaultForm = {
    id: "",
    brType: {
        id: V_BROADCAST
    },
    br_type: "",
    month_threshold: "",
    date_threshold: ""
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
    mounted() {},
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
                        this.editConfig();
                    } else {
                        this.addConfig();
                    }
                }
            });
        },
        async addConfig() {
            try {
                this.form.br_type = this.form.brType.id;
                const res = await this.form.post("/brandname/config/add");
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
                    notifyNoPermission();
                }
            }
        },
        async editConfig() {
            try {
                this.form.br_type = this.form.brType.id;

                const res = await this.form.post("/brandname/config/edit");
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
                    notifyNoPermission();
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

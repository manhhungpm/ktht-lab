<template>
    <modal
        ref="modal"
        :title="isEdit ? $t('admin.manager.edit') : $t('admin.manager.add')"
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm('form')"
        >
            <form-control
                v-model="form.name"
                v-validate="'required|max:100'"
                :label="$t('admin.manager.name')"
                name="name"
                autocomplete="off"
                :placeholder="$t('admin.manager.placeholder.name')"
                :error="errors.first('name') || form.errors.get('name')"
                :required="true"
                :data-vv-as="$t('admin.manager.placeholder.name')"
            >
            </form-control>
            <form-control
                v-model="form.description"
                v-validate="'required|max:500'"
                :label="$t('admin.manager.description')"
                name="description"
                autocomplete="off"
                :placeholder="$t('admin.manager.placeholder.description')"
                :type="'area'"
                data-vv-as="$t('admin.manager.placeholder.description')"
                :error="
                    errors.first('description') ||
                        form.errors.get('description')
                "
                :required="true"
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
import { FORM_LABEL_WIDTH } from "~/constants/constant";
import FormControl from "~/components/common/FormControl";
import { SUCCESS } from "~/constants/code";
import {
    notifyTryAgain,
    notifyUpdateSuccess,
    notifyAddSuccess
} from "~/helpers/bootstrap-notify";

const defaultForm = {
    id: "",
    name: "",
    description: ""
};

export default {
    name: "ManagerModal",
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
                        this.editManager();
                    } else {
                        this.addManager();
                    }
                }
            });
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = false;
            this.$validator.reset();
        },
        async addManager() {
            try {
                const res = await this.form.post("/admin/manager/add");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyAddSuccess();
                    this.closeModal();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                console.log(e);
            }
        },
        async editManager() {
            try {
                const res = await this.form.post("/admin/manager/edit");
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyUpdateSuccess();
                    this.closeModal();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                console.log(e);
            }
        }
    }
};
</script>

<style scoped></style>

<template>
    <modal
            ref="modal"
            :title="isEdit ? $t('admin.class.edit') : $t('admin.class.add')"
            :on-hidden="onModalHidden"
    >
        <form
                class="m-form m-form--state m-form--label-align-right"
                @submit.prevent="validateForm('form')"
        >
            <form-control
                    v-model="form.name"
                    v-validate="'required|max:100'"
                    :label="$t('admin.class.name')"
                    name="name"
                    autocomplete="off"
                    :placeholder="$t('admin.class.placeholder.name')"
                    :error="errors.first('name') || form.errors.get('name')"
                    :required="true"
                    :data-vv-as="$t('admin.class.placeholder.name')"
            >
            </form-control>
            <faculty-chosen :multiple="false"
                            v-model="form.faculty_result"
                            :required="true">
            </faculty-chosen>
            <form-control
                    v-model="form.description"
                    v-validate="'required|max:500'"
                    :label="$t('admin.class.description')"
                    name="description"
                    autocomplete="off"
                    :placeholder="$t('admin.class.placeholder.description')"
                    :type="'area'"
                    :data-vv-as="$t('admin.class.placeholder.description')"
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
import FacultyChosen from "../../../../components/elements/chosens/FacultyChosen";

const defaultForm = {
    id: "",
    name: "",
    description: "",
    faculty: null,
    faculty_result: null,
};
export default {
    name: "ClassModal",
    components: {FacultyChosen, FormControl },
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
                item.faculty_result = {
                    id: item.faculty.id,
                    text: item.faculty.name,
                    name: item.faculty.name
                };
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
                        this.editClass();
                    } else {
                        this.addClass();
                    }
                }
            });
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = false;
            this.$validator.reset();
        },
        async addClass() {
            this.form.faculty_id = this.form.faculty_result.id;
            try {
                const res = await this.form.post("/admin/class/add");
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
        async editClass() {
            this.form.faculty_id = this.form.faculty_result.id;
            try {
                const res = await this.form.post("/admin/class/edit");
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
}
</script>

<style scoped>

</style>
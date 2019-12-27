<template>
    <modal
            ref="modal"
            :title="isEdit ? $t('project.edit') : $t('project.add')"
            :on-hidden="onModalHidden"
    >
        <form
                class="m-form m-form--state m-form--label-align-right"
                @submit.prevent="validateForm('form')"
        >
            <form-control
                    v-model="form.name"
                    v-validate="'required|max:100'"
                    :label="$t('project.name')"
                    name="name"
                    autocomplete="off"
                    :placeholder="$t('project.placeholder.name')"
                    :error="errors.first('name') || form.errors.get('name')"
                    :required="true"
                    :data-vv-as="$t('project.placeholder.name')"
            >
            </form-control>
            <label>{{$t('project.user')}}<span class="text-danger"> (*)</span></label>
            <user-chosen :multiple="true"
                         v-model="form.user_result"
                         :required="true"
                         name="user"
                         :data-vv-as="$t('project.user')"
                         v-validate="'required'"
                         :error="errors.first('user') || form.errors.get('user')"
            ></user-chosen>
            <device-type-chosen :multiple="true"
                                v-model="form.device_type_result"
                                :required="true"
                                name="device_type"
                                v-validate="'required'"
                                :error="errors.first('device_type') || form.errors.get('device_type')"
            ></device-type-chosen>
            <form-control
                    v-model="form.description"
                    v-validate="'required|max:500'"
                    :label="$t('project.description')"
                    name="description"
                    autocomplete="off"
                    :placeholder="$t('project.placeholder.description')"
                    :type="'area'"
                    :data-vv-as="$t('project.placeholder.description')"
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
    import {FORM_LABEL_WIDTH} from "~/constants/constant";
    import FormControl from "~/components/common/FormControl";
    import {SUCCESS} from "~/constants/code";
    import {
        notifyTryAgain,
        notifyUpdateSuccess,
        notifyAddSuccess
    } from "~/helpers/bootstrap-notify";
    import UserChosen from "../../../components/elements/chosens/UserChosen";
    import DeviceTypeChosen from "../../../components/elements/chosens/DeviceTypeChosen";

    const defaultForm = {
        id: "",
        name: "",
        description: "",
        user_result: null,
        device_type_result: null
    };
    export default {
        name: "ProjectModal",
        components: {DeviceTypeChosen, UserChosen, FormControl},
        props: {
            onActionSuccess: {
                type: Function,
                default: () => {
                }
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
                    item.device_type_result = item.device_type;

                    item.user_result = item.user;

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
                            this.editProject();
                        } else {
                            this.addProject();
                        }
                    }
                });
            },
            onModalHidden() {
                this.form = new Form(defaultForm);
                this.isEdit = false;
                this.$validator.reset();
            },
            async addProject() {

                this.form.device_type_id = this.form.device_type_result.map(function (e) {
                    return e['id'];
                })

                this.form.user_id = this.form.user_result.map(function (e) {
                    return e['id'];
                })

                try {
                    const res = await this.form.post("/project/add");
                    const {data} = res;

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
            async editProject() {
                this.form.device_type_id = this.form.device_type_result.map(function (e) {
                    return e['id'];
                })

                this.form.user_id = this.form.user_result.map(function (e) {
                    return e['id'];
                })

                try {
                    const res = await this.form.post("/project/edit");
                    const {data} = res;

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

<template>
    <modal
        ref="modal"
        :title="
            isEdit ? $t('admin.users.edit') : $t('admin.users.add')
        "
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm('form')"
        >
            <form-control
                v-if="isEdit !== true"
                v-model="form.name"
                v-validate="'required|max:100'"
                name="name"
                :label="$t('admin.users.user_name')"
                autocomplete="off"
                :placeholder="$t('admin.users.placeholder.name')"
                :error="errors.first('name') || form.errors.get('name')"
                :required="true"
                :data-vv-as="$t('admin.users.placeholder.name')"
            >
            </form-control>
            <form-control
                v-model="form.display_name"
                v-validate="'required'"
                :label="$t('admin.users.full_name')"
                name="display_name"
                autocomplete="off"
                :placeholder="$t('admin.users.placeholder.display_name')"
                :error="
                    errors.first('display_name') ||
                        form.errors.get('display_name')
                "
                :required="true"
                :data-vv-as="$t('admin.users.placeholder.display_name')"
            >
            </form-control>
            <class-chosen :multiple="false"
                          v-model="form.classes_result"
                          :required="false"
                          name="class"
            ></class-chosen>
            <form-control
                v-model="form.email"
                v-validate="'required'"
                :label="$t('admin.users.email')"
                name="email"
                autocomplete="off"
                :placeholder="$t('admin.users.placeholder.email')"
                :error="errors.first('email') || form.errors.get('email')"
                :required="true"
                :data-vv-as="$t('admin.users.placeholder.email')"
            >
            </form-control>
            <form-control
                v-model="form.mobile_phone"
                v-validate="'required'"
                :label="$t('admin.users.phone')"
                name="mobile_phone"
                :placeholder="$t('admin.users.placeholder.phone')"
                :error="
                    errors.first('mobile_phone') ||
                        form.errors.get('mobile_phone')
                "
                :required="true"
                :data-vv-as="$t('admin.users.placeholder.phone')"
            >
            </form-control>
            <form-control
                v-if="!isEdit"
                v-model="form.password"
                v-validate="'required|isPassword:true'"
                type="password"
                :label="$t('admin.users.password')"
                name="password"
                :placeholder="$t('admin.users.placeholder.password')"
                :error="errors.first('password') || form.errors.get('password')"
                :required="true"
            ></form-control>
            <role-chosen
                v-model="form.role"
                :required="true"
                :multiple="true"
                name="role"
                v-validate="'required'"
                :error="errors.first('role') || form.errors.get('role')"
            ></role-chosen>
            <div
                class="form-group m-form__group"
                :class="{
                    'has-danger':
                        errors.first('expired_at') ||
                        form.errors.get('expired_at')
                }"
            >
                <label
                >{{ $t("admin.users.expired_at") }}
                    <span class="text-danger">(*)</span></label
                >
                <el-date-picker
                    v-model="form.expired_at"
                    v-validate="'required'"
                    name="expired_at"
                    style="margin-right: 100px ; width: 100%"
                    :placeholder="$t('admin.users.placeholder.datetime')"
                    type="datetime"
                    value-format="yyyy-MM-dd HH:mm:ss"
                    :data-vv-as="$t('admin.users.placeholder.datetime')"
                    format="dd/MM/yyyy HH:mm:ss"
                ></el-date-picker>
                <div
                    v-if="
                        errors.first('expired_at') ||
                            form.errors.get('expired_at')
                    "
                    class="form-control-feedback"
                >
                    {{ errors.first("expired_at") }}
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
    import Form from "vform";
    import axios from "axios";

    import {FORM_LABEL_WIDTH} from "~/constants/constant";
    import FormControl from "~/components/common/FormControl";
    import {SUCCESS} from "~/constants/code";
    import {
        notifyTryAgain,
        notifyUpdateSuccess,
        notifyAddSuccess,
        notifyNoPermission
    } from "~/helpers/bootstrap-notify";
    import ClassChosen from "../../../../components/elements/chosens/ClassChosen";
    import RoleChosen from "../../../../components/elements/chosens/RoleChosen";

    const defaultForm = {
        id: "",
        name: "",
        display_name: "",
        email: "",
        mobile_phone: "",
        role: null,
        expired_at: "",
        who_created: "",
        who_updated: "",
        password: null,
        classes_result: null,
        classes: null
    };

    export default {
        name: "UserModal",
        components: {RoleChosen, ClassChosen, FormControl},
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
                isEdit: false,
                roleOptions: {
                    placeholder: "Chọn role",
                    multiple: true,
                    searchable: true,
                    options: [],
                    textField: "display_name",
                    idField: "id"
                },
                roleList: []
            };
        },
        computed: {},
        mounted() {
            this.loadingData();
        },
        methods: {
            show(item = null) {
                if (item != null) {
                    item.role = item.roles;

                    item.classes_result = {
                        id: item.classes.id,
                        text: item.classes.name,
                        name: item.classes.name
                    };

                    this.form = new Form(item);
                    this.isEdit = true;
                }
                this.$refs.modal.show();
            },
            closeModal() {
                this.$refs.modal.hide();
            },
            async addUser() {
                this.form.role = this.form.role.map(e => {
                    return e.id;
                });
                this.form.class_id = this.form.classes_result.id;

                try {
                    const res = await this.form.post("/admin/user/add");
                    const {data} = res;

                    if (data.code === SUCCESS) {
                        notifyAddSuccess();
                        this.closeModal();
                        this.onActionSuccess();
                    } else {
                        notifyTryAgain();
                    }
                } catch (e) {
                    const {status} = e.response;

                    if (status != 403) {
                        notifyNoPermission();
                    }
                }
            },
            async editUser() {
                this.form.role = this.form.role.map(e => {
                    return e.id;
                });
                this.form.class_id = this.form.classes_result.id;

                try {
                    const res = await this.form.post("/admin/user/edit");
                    const {data} = res;

                    if (data.code === SUCCESS) {
                        notifyUpdateSuccess();
                        this.closeModal();
                        this.onActionSuccess();
                    } else {
                        notifyTryAgain();
                    }
                } catch (e) {
                    const {status} = e.response;

                    if (status != 403) {
                        notifyNoPermission();
                    }
                }
            },
            validateForm() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        if (this.isEdit) {
                            this.editUser();
                        } else {
                            this.addUser();
                        }
                    }
                });
            },
            onModalHidden() {
                this.form = new Form(defaultForm);
                this.isEdit = false;
                this.$validator.reset();
            },
            async getRole() {
                try {
                    const res = await axios.post("/admin/role/listing");
                    const {data} = res;

                    this.roleList = data.data;
                } catch (e) {
                    console.log(e);
                }
            },
            async loadingData() {
                await this.getRole();

                for (var i = 0; i < this.roleList.length; i++) {
                    this.roleOptions.options.push({
                        id: this.roleList[i].id,
                        text: this.roleList[i].name,
                        // text: this.roleList[i].display_name,
                        display_name: this.roleList[i].display_name
                    });
                }
            }
        }
    };
</script>

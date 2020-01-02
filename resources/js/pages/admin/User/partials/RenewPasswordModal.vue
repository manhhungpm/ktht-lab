<template>
    <modal
        ref="renewPasswordModal"
        :title="$t('admin.users.renew_password')"
        :on-hidden="onModalHidden"
    >
        <form
            ref="form"
            class="m-form m-form--fit m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <form-control
                v-model="form.name"
                v-validate="'required|max:255'"
                :label="$t('admin.users.user_name')"
                :data-vv-as="$t('admin.users.user_name')"
                :is-disabled="true"
                name="display_name"
                :required="true"
                :error="
                    errors.first('display_name') ||
                        form.errors.get('display_name')
                "
            />

            <form-control
                ref="password"
                v-model="form.password"
                v-validate="'required|max:128|isPassword:true'"
                :label="$t('admin.users.password')"
                :data-vv-as="$t('admin.users.password')"
                name="password"
                type="password"
                :required="true"
                :error="errors.first('password') || form.errors.get('password')"
            />

            <form-control
                v-model="form.password_confirmation"
                v-validate="'required|confirmed:password'"
                :label="$t('admin.users.repassword')"
                :data-vv-as="$t('admin.users.repassword')"
                name="password_confirmation"
                type="password"
                :required="true"
                :error="
                    errors.first('password_confirmation') ||
                        form.errors.get('password_confirmation')
                "
            />
        </form>

        <template slot="footer">
            <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
            >
                {{ $t("button.cancel") }}
            </button>
            <button type="button" class="btn btn-primary" @click="validateForm">
                {{ $t("button.update") }}
            </button>
        </template>
    </modal>
</template>

<script>
import Form from "vform";
import { SUCCESS } from "~/constants/code";
import {
    notifyTryAgain,
    notifyUpdateSuccess
} from "~/helpers/bootstrap-notify";
import FormControl from "../../../../components/common/FormControl";

const defaultUser = {
    display_name: "",
    password: "",
    password_confirmation: ""
};
export default {
    name: "RenewPasswordModal",
    components: { FormControl },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            form: new Form(defaultUser)
        };
    },
    computed: {},
    mounted() {},
    methods: {
        validateForm() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.saveItem();
                }
            });
        },
        show(item = null) {
            if (item != null) {
                this.form = new Form(item);
            }
            this.$validator.reset();
            this.$refs.renewPasswordModal.show();
        },
        onModalHidden() {
            this.form = new Form(defaultUser);
            this.$validator.reset();
        },
        async saveItem() {
            try {
                const { data } = await this.form.post(
                    "/admin/user/update-password"
                );

                if (data.code == SUCCESS) {
                    notifyUpdateSuccess(this.$t("form.user.update_password"));
                    this.$refs.renewPasswordModal.hide();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                const { status } = e.response;

                if (status != 422) {
                    notifyTryAgain();
                }
            }
        }
    }
};
</script>

<style scoped></style>

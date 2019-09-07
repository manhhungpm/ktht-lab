<template>
    <modal
        ref="modal"
        :title="
            isEdit
                ? $t('admin.api.account_api.edit')
                : $t('admin.api.account_api.add')
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
                v-validate="'required'"
                :label="$t('admin.api.account_api.name')"
                name="name"
                autocomplete="off"
                :placeholder="$t('admin.api.account_api.placeholder.name')"
                :data-vv-as="$t('admin.api.account_api.placeholder.name')"
                :error="errors.first('name') || form.errors.get('name')"
                :required="true"
            >
            </form-control>
            <form-control
                v-model="form.api"
                v-validate="'required'"
                :label="$t('admin.api.account_api.api')"
                :data-vv-as="$t('admin.api.account_api.placeholder.api')"
                :required="true"
                name="api"
                :type="'select'"
                :select-options="apiOptions"
                :error="errors.first('api') || form.errors.get('api')"
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
import axios from "axios";

import { FORM_LABEL_WIDTH } from "~/constants/constant";
import FormControl from "~/components/common/FormControl";
import {
    notifyTryAgain,
    notifyUpdateSuccess,
    notifyAddSuccess,
    notifyNoPermission
} from "~/helpers/bootstrap-notify";
import { SUCCESS } from "~/constants/code";

const defaultForm = {
    id: "",
    name: "",
    api: []
};

export default {
    name: "ManageAccountApiModal",
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
            apiOptions: {
                placeholder: "Chọn loại api",
                multiple: true,
                searchable: true,
                options: [],
                textField: "name",
                idField: "id"
            },
            apiList: []
        };
    },
    computed: {},
    mounted() {
        this.loadingData();
    },
    methods: {
        show(item = null) {
            if (item != null) {
                this.isEdit = true;
                var api_id = [];
                for (var i = 0; i < item.api_account_apis.length; i++) {
                    api_id.push(item.api_account_apis[i].api_id);
                }
                item.api = api_id.map(x => {
                    return {
                        id: x
                    };
                });
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
                        this.editAccountApi();
                    } else {
                        this.addAccountApi();
                    }
                }
            });
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isEdit = false;
            this.$validator.reset();
        },
        async loadingData() {
            await this.getApi();
            // this.apiOptions.options = this.apiList;

            for (var i = 0; i < this.apiList.length; i++) {
                this.apiOptions.options.push({
                    id: this.apiList[i].id,
                    text: this.apiList[i].name
                });
            }
        },
        async getApi() {
            try {
                const res = await axios.post("/admin/manage-api/listing-api");
                const { data } = res;
                this.apiList = data.data;
            } catch (e) {
                console.log(e);
            }
        },
        async addAccountApi() {
            var api_id = [];
            for (var i = 0; i < this.form.api.length; i++) {
                api_id.push(this.form.api[i].id);
            }
            this.form.api = api_id;
            try {
                const res = await this.form.post(
                    "/admin/manage-api/add-account-api"
                );
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
        async editAccountApi() {
            var api_id = [];
            for (var i = 0; i < this.form.api.length; i++) {
                api_id.push(this.form.api[i].id);
            }
            this.form.api = api_id;
            try {
                const res = await this.form.post(
                    "/admin/manage-api/edit-account-api"
                );
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
        }
    }
};
</script>

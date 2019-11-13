<template>
    <modal
            ref="modal"
            :title="isEdit ? $t('device.provider.edit') : $t('device.provider.add')"
            :on-hidden="onModalHidden"
    >
        <form
                class="m-form m-form--state m-form--label-align-right"
                @submit.prevent="validateForm('form')"
        >
            <form-control
                    v-model="form.name"
                    v-validate="'required|max:100'"
                    :label="$t('device.provider.name')"
                    name="name"
                    autocomplete="off"
                    :placeholder="$t('device.provider.placeholder.name')"
                    :error="errors.first('name') || form.errors.get('name')"
                    :required="true"
                    :data-vv-as="$t('device.provider.placeholder.name')"
            >
            </form-control>
            <form-control
                    v-model="form.address"
                    v-validate="'required|max:255'"
                    :label="$t('device.provider.address')"
                    name="address"
                    autocomplete="off"
                    :placeholder="$t('device.provider.placeholder.address')"
                    :error="errors.first('address') || form.errors.get('address')"
                    :required="true"
                    :data-vv-as="$t('device.provider.placeholder.address')"
            >
            </form-control>
            <form-control
                    v-model="form.description"
                    v-validate="'required|max:500'"
                    :label="$t('device.provider.description')"
                    name="description"
                    autocomplete="off"
                    :placeholder="$t('device.provider.placeholder.description')"
                    :type="'area'"
                    :data-vv-as="$t('device.provider.placeholder.description')"
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

    const defaultForm = {
        id: "",
        name: "",
        address: null,
        description: ""
    };
    export default {
        name: "ProviderModal",
        components: {FormControl},
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
                            this.editProvider();
                        } else {
                            this.addProvider();
                        }
                    }
                });
            },
            onModalHidden() {
                this.form = new Form(defaultForm);
                this.isEdit = false;
                this.$validator.reset();
            },
            async addProvider() {
                try {
                    const res = await this.form.post("/device/provider/add");
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
            async editProvider() {
                try {
                    const res = await this.form.post("/device/provider/edit");
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

<style scoped>

</style>
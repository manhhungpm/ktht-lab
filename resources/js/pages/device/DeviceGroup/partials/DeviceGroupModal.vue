<template>
    <modal
            ref="modal"
            :title="isEdit ? $t('device.device_group.edit') : $t('device.device_group.add')"
            :on-hidden="onModalHidden"
    >
        <form
                class="m-form m-form--state m-form--label-align-right"
                @submit.prevent="validateForm('form')"
        >
            <form-control
                    v-model="form.name"
                    v-validate="'required|max:100'"
                    :label="$t('device.device_group.name')"
                    name="name"
                    autocomplete="off"
                    :placeholder="$t('device.device_group.placeholder.name')"
                    :error="errors.first('name') || form.errors.get('name')"
                    :required="true"
                    :data-vv-as="$t('device.device_group.placeholder.name')"
            >
            </form-control>
            <form-control
                    v-model="form.display_name"
                    v-validate="'required|max:100'"
                    :label="$t('device.device_group.display_name')"
                    name="display_name"
                    autocomplete="off"
                    :placeholder="$t('device.device_group.placeholder.display_name')"
                    :error="errors.first('display_name') || form.errors.get('display_name')"
                    :required="true"
                    :data-vv-as="$t('device.device_group.placeholder.display_name')"
            >
            </form-control>
            <provider-chosen :multiple="false" v-model="form.provider_result"
                             :required="true"></provider-chosen>
            <form-control
                    v-model="form.description"
                    v-validate="'required|max:500'"
                    :label="$t('device.device_group.description')"
                    name="description"
                    autocomplete="off"
                    :placeholder="$t('device.device_group.placeholder.description')"
                    :type="'area'"
                    :data-vv-as="$t('device.device_group.placeholder.description')"
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
    import ProviderChosen from "../../../../components/elements/chosens/ProviderChosen";

    const defaultForm = {
        id: "",
        name: "",
        display_name: null,
        description: "",
        provider_result: null
    };
    export default {
        name: "DeviceGroupModal",
        components: {ProviderChosen, FormControl},
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
                    item.provider_result = {
                        id: item.provider.id,
                        text: item.provider.name,
                        name: item.provider.name
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
                            this.editDeviceGroup();
                        } else {
                            this.addDeviceGroup();
                        }
                    }
                });
            },
            onModalHidden() {
                this.form = new Form(defaultForm);
                this.isEdit = false;
                this.$validator.reset();
            },
            async addDeviceGroup() {
                this.form.provider_id = this.form.provider_result.id;

                try {
                    const res = await this.form.post("/device/device-group/add");
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
            async editDeviceGroup() {
                this.form.provider_id = this.form.provider_result.id;

                try {
                    const res = await this.form.post("/device/device-group/edit");
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
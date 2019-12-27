<template>
    <modal
            ref="modal"
            :title="isEdit ? $t('device.device_type.edit') : $t('device.device_type.add')"
            :on-hidden="onModalHidden"
    >
        <form
                class="m-form m-form--state m-form--label-align-right"
                @submit.prevent="validateForm('form')"
        >
            <form-control
                    v-model="form.name"
                    v-validate="'required|max:100'"
                    :label="$t('device.device_type.name')"
                    name="name"
                    autocomplete="off"
                    :placeholder="$t('device.device_type.placeholder.name')"
                    :error="errors.first('name') || form.errors.get('name')"
                    :required="true"
                    :data-vv-as="$t('device.device_type.placeholder.name')"
            >
            </form-control>
            <form-control
                    v-model="form.display_name"
                    v-validate="'required|max:100'"
                    :label="$t('device.device_type.display_name')"
                    name="display_name"
                    autocomplete="off"
                    :placeholder="$t('device.device_type.placeholder.display_name')"
                    :error="errors.first('display_name') || form.errors.get('display_name')"
                    :required="true"
                    :data-vv-as="$t('device.device_type.placeholder.display_name')"
            >
            </form-control>
            <form-control
                    v-model="form.amount"
                    v-validate="'required|max:100|numeric'"
                    :label="$t('device.device_type.amount')"
                    name="amount"
                    autocomplete="off"
                    :placeholder="$t('device.device_type.placeholder.amount')"
                    :error="errors.first('amount') || form.errors.get('amount')"
                    :required="true"
                    :data-vv-as="$t('device.device_type.placeholder.amount')"
            >
            </form-control>
            <device-group-chosen :multiple="false"
                                 :required="true"
                                 v-model="form.device_group_result"
                                 name="device_group"
                                 v-validate="'required'"
                                 :error="errors.first('device_group') || form.errors.get('device_group')"
            ></device-group-chosen>
            <store-chosen :multiple="false"
                          :required="true"
                          v-model="form.store_result"
                          name="store"
                          v-validate="'required'"
                          :error="errors.first('store') || form.errors.get('store')"></store-chosen>
            <form-control
                    v-model="form.description"
                    v-validate="'required|max:500'"
                    :label="$t('device.device_type.description')"
                    name="description"
                    autocomplete="off"
                    :placeholder="$t('device.device_type.placeholder.description')"
                    :type="'area'"
                    :data-vv-as="$t('device.device_type.placeholder.description')"
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
    import DeviceGroupChosen from "../../../../components/elements/chosens/DeviceGroupChosen";
    import StoreChosen from "../../../../components/elements/chosens/StoreChosen";

    const defaultForm = {
        id: "",
        name: "",
        amount: null,
        device_group_result: null,
        store_result: null,
        display_name: null,
        description: ""
    };

    export default {
        name: "DeviceTypeModal",
        components: {StoreChosen, DeviceGroupChosen, FormControl},
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
                    item.store_result = {
                        id: item.store.id,
                        text: item.store.name,
                        name: item.store.name
                    };
                    item.device_group_result = {
                        id: item.device_group.id,
                        text: item.device_group.name,
                        name: item.device_group.name
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
                            this.editDeviceType();
                        } else {
                            this.addDeviceType();
                        }
                    }
                });
            },
            onModalHidden() {
                this.form = new Form(defaultForm);
                this.isEdit = false;
                this.$validator.reset();
            },
            async addDeviceType() {
                this.form.store_id = this.form.store_result.id;
                this.form.device_group_id = this.form.device_group_result.id;

                try {
                    const res = await this.form.post("/device/device-type/add");
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
            async editDeviceType() {
                this.form.store_id = this.form.store_result.id;
                this.form.device_group_id = this.form.device_group_result.id;

                try {
                    const res = await this.form.post("/device/device-type/edit");
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

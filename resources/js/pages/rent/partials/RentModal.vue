<template>
    <modal
            ref="modal"
            :title="isEdit ? $t('rent.edit') : $t('rent.add')"
            :on-hidden="onModalHidden"
    >
        <form
                class="m-form m-form--state m-form--label-align-right"
                @submit.prevent="validateForm('form')"
        >
            <label>{{$t('rent.user')}}<span class="text-danger"> (*)</span></label>
            <user-chosen :multiple="false" v-model="form.user"
                         :required="true"></user-chosen>

            <device-type-chosen :multiple="true" v-model="form.device_type"
                                :required="true"></device-type-chosen>

            <the-date-range
                    :inline="false"
                    :label="$t('rent.date_range')"
                    v-model="form.date_range"
                    :format="'dd/MM/yyyy'"
            ></the-date-range>

            <form-control
                    v-model="form.description"
                    v-validate="'required|max:500'"
                    :label="$t('common.description')"
                    name="description"
                    autocomplete="off"
                    :placeholder="$t('rent.placeholder.description')"
                    :type="'area'"
                    :data-vv-as="$t('rent.placeholder.description')"
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
    import TheDateRange from "../../../components/common/TheDateRange";

    const defaultForm = {
        id:  null,
        user: null,
        description: null,
        date_range: null,
        device_type: null
    };
    export default {
        name: "RentModal",
        components: {TheDateRange, DeviceTypeChosen, UserChosen, FormControl},
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
                    console.log(item)
                    item.date_range = [item.start_date,item.due_date];
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
                            this.editRent();
                        } else {
                            this.addRent();
                        }
                    }
                });
            },
            onModalHidden() {
                this.form = new Form(defaultForm);
                this.isEdit = false;
                this.$validator.reset();
            },
            async addRent() {

                this.form.device_type_id = this.form.device_type.map(function (e) {
                    return e['id'];
                })
                //
                // this.form.user_id = this.form.user_result.map(function (e) {
                //     return e['id'];
                // })

                try {
                    const res = await this.form.post("/rent/add");
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
            async editRent() {
                this.form.device_type_id = this.form.device_type.map(function (e) {
                    return e['id'];
                })
                //
                // this.form.user_id = this.form.user_result.map(function (e) {
                //     return e['id'];
                // })

                try {
                    const res = await this.form.post("/rent/edit");
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
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
            <!--            <device-type-chosen :multiple="true"-->
            <!--                                v-model="form.device_type_result"-->
            <!--                                :required="true"-->
            <!--                                name="device_type"-->
            <!--                                v-validate="'required'"-->
            <!--                                :error="errors.first('device_type') || form.errors.get('device_type')"-->
            <!--            ></device-type-chosen>-->

            <div
                class="multiple-input-wrap col-12"
                :class="{ 'error-repeat-form-wrap': noMultiple }"
            >
                <label
                    class="multiple-input-heading"
                    :class="{ 'error-repeat-form-title': noMultiple }"
                >
                    <span>Chọn loại và số lượng thiết bị mượn</span>
                </label>
                <div class="m--margin-left-20">
                    <div
                        v-for="(input,
                            index) in form.multi_device_details"
                        :key="index"
                        class="form-group m-form__group row"
                    >
                        <div class="col-md-4">
                            <device-type-chosen :multiple="false"
                                                v-model="input.device_type"
                                                :required="true"
                                                v-validate="'required'"
                                                :name="'multi_device_details'+index"
                                                :error="errors.first('multi_device_details'+index) ||
                                                form.errors.get('multi_device_details.'+index)"></device-type-chosen>
                        </div>

                        <div class="col-md-4">
                            <label>Số lượng <span class="text-danger">(*)</span></label>
                            <el-input-number v-model="input.amount"></el-input-number>
                        </div>

                        <div
                            class="col-2 form-group m-form__group full-height-col" style="margin-top: 28px"
                        >
                            <div class="delete-button-wrap">
                                <a
                                    href="javascript:;"
                                    class="text-danger"
                                    @click="
                                            deleteDetails(
                                                form.multi_device_details,
                                                index
                                            )
                                        "
                                >
                                    {{ $t("button.delete") }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div
                        class="m-form__group form-group m--margin-bottom-10"
                    >
                        <a
                            href="javascript:;"
                            class=""
                            @click="
                                    addDetails(form.multi_device_details, {
                                        device_type: null,
                                        amount: null
                                    })
                                "
                        >{{ $t("button.add") }}
                        </a>
                    </div>
                </div>
                <div
                    v-if="noMultiple"
                    class="form-control-feedback error-repeat-form error-repeat-form-title"
                    style="margin-left:15px;"
                >
                    Loại và số lượng thiết bị không được để trống
                </div>
            </div>

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
        device_type_result: null,
        multi_device_details: [
            {
                device_type: null,
                amount: null
            }
        ],
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
        computed: {
            noMultiple() {
                return this.form.multi_device_details.length == 0;
            }
        },
        methods: {
            addDetails(array, item) {
                array.push(item);
            },
            deleteDetails(array, index) {
                this.$validator.reset();
                array.splice(index, 1);
            },
            async show1(item = null) {
                if (item != null) {
                    // item.device_type_result = item.device_type;

                    console.log(item)
                    //
                    item.multi_device_details = [];
                    var arr = [];
                    item.device_type.forEach(function (value) {
                        arr.push({
                            device_type: {
                                name: value.name,
                                text: value.name,
                                id: value.id,
                            },
                            amount: value.pivot.amount
                        });
                    });
                    await this.$nextTick();
                    item.multi_device_details = arr;
                    //


                    item.user_result = item.user;
                    this.isEdit = true;
                    this.form = new Form(item);
                }
                this.$refs.modal.show();
            },
            async show(item = null) {
                if (item != null) {
                    this.isEdit = true;

                    //Load user
                    item.user_result = item.user;

                    //Load thiết bị và số lượng
                    let original = _.cloneDeep(item)
                    if (original.device_type.length > 0) {
                        original.multi_device_details = original.device_type.map((e) => {
                            e.device_type = null
                            e.amount = null
                            return e
                        })
                    } else {
                        original.multi_device_details = [
                            {
                                device_type: null,
                                amount: null,
                            }
                        ]
                    }
                    this.form = new Form(_.cloneDeep(original))
                    await this.$nextTick()
                    item.device_type.forEach((e, index) => {
                        this.form.multi_device_details[index].device_type = {
                            id: e.id,
                            name: e.name
                        }
                        this.form.multi_device_details[index].amount = e.pivot.amount
                    })
                    await this.$nextTick()
                    item.device_type.forEach((e, index) => {
                        this.form.multi_device_details[index].device_type = {
                            id: e.id,
                            name: e.name
                        }
                    })
                    await this.$nextTick()
                    //

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
            setupDataPost() {
                this.form.device_type_id = this.form.multi_device_details.map(function (e) {
                    return e['device_type']['id'];
                })
                this.form.amount = this.form.multi_device_details.map(function (e) {
                    return e.amount
                })
            },
            async addProject() {
                this.setupDataPost();

                // this.form.device_type_id = this.form.device_type_result.map(function (e) {
                //     return e['id'];
                // })

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
                this.setupDataPost();

                // this.form.device_type_id = this.form.device_type_result.map(function (e) {
                //     return e['id'];
                // })

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

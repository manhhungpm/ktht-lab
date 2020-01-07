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
                         :required="true"
                         name="user"
                         :data-vv-as="$t('rent.user')"
                         v-validate="'required'"
                         :error="errors.first('user') || form.errors.get('user')"
            ></user-chosen>

            <project-chosen v-model="form.project_result" :multiple="false"></project-chosen>

            <leader-chosen v-model="form.leader_result" :multiple="false"></leader-chosen>

            <form-control
                v-model="form.priorityId"
                v-validate="'required'"
                :label="'Độ ưu tiên'"
                :data-vv-as="'Độ ưu tiên'"
                :required="true"
                name="priority"
                :type="'select'"
                :select-options="priorityOptions"
                :error="
                    errors.first('priority') || form.errors.get('priority')
                "
            ></form-control>

            <!--<device-type-chosen :multiple="true" v-model="form.device_type"-->
            <!--:required="true"></device-type-chosen>-->


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


            <the-date-range
                style="margin-top: 15px"
                :inline="false"
                :label="$t('rent.date_range')"
                v-model="form.date_range"
                :format="'dd/MM/yyyy'"
                :required="true"
                v-validate="'required'"
                :data-vv-as="$t('rent.date_range')"
                name="date_range"
                :error="errors.first('date_range') ||
                        form.errors.get('date_range')"
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
    import ProjectChosen from "../../../components/elements/chosens/ProjectChosen";
    import LeaderChosen from "../../../components/elements/chosens/LeaderChosen";

    const defaultForm = {
        id: null,
        user: null,
        description: null,
        date_range: null,
        device_type: null,
        project_result: null,
        leader_result: null,
        priorityId: null,
        multi_device_details: [
            {
                device_type: null,
                amount: null
            }
        ],
    };
    export default {
        name: "RentModal",
        components: {
            LeaderChosen, ProjectChosen, TheDateRange, DeviceTypeChosen, UserChosen, FormControl
        },
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
                priorityOptions: {
                    placeholder: 'Chọn mức độ ưu tiên',
                    multiple: false,
                    searchable: false,
                    options: [
                        {
                            id: 1,
                            text: "Cao"
                        },
                        {
                            id: 2,
                            text: "Trung bình"
                        },
                        {
                            id: 3,
                            text: "Thấp"
                        },
                    ]
                }
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
                array.splice(index, 1);
            },
            async show1(item = null) {
                if (item != null) {
                    // console.log(item)

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
                    // console.log(arr);
                    item.multi_device_details = arr;
                    // console.log(item.multi_device_details)
                    // console.log(arr)
                    // arr.forEach(function (value, index, array) {
                    //       item.multi_device_details[index].device_type = value.device_type
                    // })
                    //

                    item.date_range = [item.start_date, item.due_date];
                    this.isEdit = true;
                    this.form = new Form(item);
                }
                this.$refs.modal.show();
            },
            async show(item = null) {
                if (item != null) {
                    this.isEdit = true;
                    item.date_range = [item.start_date, item.due_date];

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
            setupDataPost() {
                this.form.device_type_id = this.form.multi_device_details.map(function (e) {
                    return e['device_type']['id'];
                })
                this.form.amount = this.form.multi_device_details.map(function (e) {
                    return e.amount
                })
            },
            async addRent() {
                this.setupDataPost();
                this.form.project_id = this.form.project_result.id;
                this.form.leader_id = this.form.leader_result.id;
                this.form.priority = this.form.priorityId.id;
                // this.form.device_type_id = this.form.device_type.map(function (e) {
                //     return e['id'];
                // })
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
                this.setupDataPost();
                this.form.project_id = this.form.project_result.id;
                this.form.leader_id = this.form.leader_result.id;
                this.form.priority = this.form.priorityId.id;
                // this.form.device_type_id = this.form.device_type.map(function (e) {
                //     return e['id'];
                // })
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

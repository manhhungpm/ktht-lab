<template>
    <modal
        ref="modal"
        size="lg"
        hide-footer
        :title="$t('warning.title_tag_modal')"
        @hidden="onModalHidden"
    >
        <div class="d-block text-center">
            <el-form
                ref="form"
                :model="form"
                :rules="rules"
                @submit.prevent="validateForm('form')"
            >
                <el-form-item
                    class="label-is-selected"
                    :label="$t('spam.spam_patterns.content')"
                    :label-width="FORM_LABEL_WIDTH"
                    prop="content"
                >
                    <div
                        v-for="(item, index) in form.contents"
                        :key="item.id"
                        class="content_spam_patterns_warning_modal text-left"
                        style="padding-left: 5px;padding-right: 5px"
                    >
                        {{ index + 1 }}. {{ item.content }}
                    </div>
                </el-form-item>

                <el-form-item
                    :label-width="FORM_LABEL_WIDTH"
                    :label="$t('spam.spam_patterns.label')"
                    prop="label"
                >
                    <el-select
                        v-model="form.label"
                        placeholder="Select"
                        filterable
                        :filter-method="filterOptions"
                    >
                        <el-option-group
                            v-for="group in spamLabelsFilter"
                            :key="group.label"
                            :label="group.label"
                        >
                            <el-option
                                v-for="item in group.options"
                                :key="item.name"
                                :label="item.display_name"
                                :value="item.name"
                            >
                            </el-option>
                        </el-option-group>
                    </el-select>
                </el-form-item>
            </el-form>
            <el-button class="btn btn-info" @click="closeModal"
                >{{ $t("button.close") }}
            </el-button>
            <el-button class="btn btn-primary" @click="validateForm('form')">
                {{ $t("button.tag") }}
            </el-button>
        </div>
    </modal>
</template>

<script>
import $ from "jquery";
import { deleteVietnameseAccent } from "../../../../../helpers/deleteVietnameseAccent";
export default {
    name: "WarningModal",
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            form: {
                label: null,
                contents: [{ content: null, id: null }]
            },

            formLabelWidth: "130px",
            isEdit: false,

            spamLabels: [],
            spamLabelsFilter: []
        };
    },
    computed: {
        rules() {
            return {
                label: [
                    {
                        required: true,
                        message: this.$t("warning.label_required"),
                        trigger: "blur"
                    }
                ]
            };
        }
    },
    mounted() {
        this.getLabel();
    },
    methods: {
        show(item = null) {
            if (item != null) {
                this.form.contents = item;
            }
            this.$refs.modal.show();
        },
        closeModal() {
            this.$refs.modal.hide();
        },
        async tag() {
            const ids = Array.from(this.form.contents, e => e.id);
            const res = await this.$axios.post(
                "/spam/spam-patterns-warning/tag",
                {
                    label: this.form.label,
                    ids: ids
                }
            );
            const { data } = res;

            if (data.code === 0) {
                this.$bootstrapNotify({
                    title: this.$t("label.notification"),
                    message: this.$t("warning.notification_tag_success"),
                    type: "success"
                });
                this.closeModal();
                this.onActionSuccess();
            } else {
                this.$bootstrapNotify({
                    title: this.$t("label.notification"),
                    message: this.$t("warning.notification_tag_error"),
                    type: "danger"
                });
                this.closeModal();
            }
        },
        validateForm(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) {
                    this.tag();
                }
            });
        },
        onModalHidden() {
            this.form = {
                label: null,
                contents: [{ content: null, id: null }]
            };
            $(".table-action").tooltip("hide");
            $(".table-action").trigger("blur");
            $(".el-button--button").trigger("blur");
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async getLabel() {
            const labelResponse = await this.$axios.post(
                this.$axios.defaults.baseURL +
                    "/spam/spam-patterns-warning/list-all"
            );
            const optionList = [];

            labelResponse.data.results.groupLabel.forEach(element => {
                let optionListchil = null;
                optionListchil = [];

                optionListchil.label = element.display_name;
                optionListchil.options = [];
                labelResponse.data.results.finalResult.forEach(e => {
                    if (e.parent === element.name) {
                        optionListchil.options.push(e);
                    }
                });
                optionList.push(optionListchil);
            });
            this.spamLabels = optionList;
            this.spamLabelsFilter = optionList;
        },
        filterOptions(inputValue) {
            let filterResult = null;
            filterResult = [];

            this.spamLabels.forEach(e => {
                let filterResultChild = null;
                filterResultChild = [];
                filterResultChild.label = e.label;

                filterResultChild.options = e.options.filter(option => {
                    return (
                        deleteVietnameseAccent(
                            option.display_name.toLocaleLowerCase()
                        ).indexOf(
                            deleteVietnameseAccent(
                                inputValue.toLocaleLowerCase()
                            )
                        ) > -1
                    );
                });
                if (filterResultChild.options.length > 0) {
                    filterResult.push(filterResultChild);
                }
            });

            this.spamLabelsFilter = filterResult;
            return true;
        }
    }
};
</script>

<style>
.content_spam_patterns_warning_modal {
    background-color: aliceblue;
}

.label-is-selected .el-form-item__content {
    max-height: 60vh;
    overflow-y: scroll;
}
</style>

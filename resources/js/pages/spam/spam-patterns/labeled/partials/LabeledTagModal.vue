<template>
    <modal
        ref="modal"
        :title="
            isTag
                ? $t('spam.spam_patterns.title_tag_modal')
                : $t('spam.spam_patterns.title_add_modal')
        "
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <div class="row">
                <form-control
                    v-if="!isTag"
                    v-model="form.content"
                    v-validate="'required|min:10'"
                    class="col-12"
                    :data-vv-as="$t('spam.spam_patterns.content')"
                    :label="$t('spam.spam_patterns.content')"
                    :required="true"
                    :name="'content'"
                    :error="
                        errors.first('content') || form.errors.get('content')
                    "
                >
                </form-control>

                <label
                    v-if="isTag"
                    class="label_content_spam_patterns_labeled_modal"
                >
                    {{ $t("spam.spam_patterns.content") }}
                    <span class="text-danger">(*)</span></label
                >
                <div
                    v-if="isTag"
                    class=" col-12 form-group m-form__group label-is-selected"
                >
                    <div
                        v-for="(item, index) in form.contents"
                        :key="item.id"
                        class="content_spam_patterns_labeled_modal text-left"
                    >
                        {{ index + 1 }}. {{ item.content }}
                    </div>
                </div>

                <spam-label-chosen
                    v-model="form.labelResult"
                    v-validate="'required'"
                    class="col-12"
                    :multiple="false"
                    :error="errors.first('label') || form.errors.get('label')"
                ></spam-label-chosen>
            </div>
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
                {{ isTag ? $t("button.tag") : $t("button.add") }}
            </button>
        </template>
    </modal>
</template>

<script>
import axios from "axios";
import Form from "vform";
import { deleteVietnameseAccent } from "../../../../../helpers/deleteVietnameseAccent";
import SpamLabelChosen from "../../../../../components/elements/chosens/SpamLabelChosen";
import { SUCCESS } from "~/constants/code";
import {
    notifyTagSuccess,
    notify,
    notifyTryAgain
} from "~/helpers/bootstrap-notify";

const defaultForm = {
    labelResult: null,
    contents: [{ content: null, id: null }],
    content: null
};

export default {
    name: "LabeledModal",
    components: { SpamLabelChosen },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            form: new Form(defaultForm),

            formLabelWidth: "130px",
            isTag: true,

            spamLabels: [],
            spamLabelsFilter: []
        };
    },
    computed: {},
    mounted() {
        this.getLabel();
    },
    methods: {
        show(item = null) {
            if (item != null) {
                this.form.contents = item;
                this.form.content = "";
                this.form.contents.forEach((e, index) => {
                    this.form.content += index + 1 + ". " + e.content + "\n";
                });
                this.isTag = true;
            } else {
                this.isTag = false;
            }
            this.$refs.modal.show();
        },
        closeModal() {
            this.$refs.modal.hide();
        },
        async tag() {
            const ids = Array.from(this.form.contents, e => e.id);
            this.form.ids = ids;
            this.form.label = this.form.labelResult.name;
            try {
                const res = await this.form.post(
                    "/spam/spam-patterns-labeled/tag"
                );
                const { data } = res;
                if (data.code == SUCCESS) {
                    notifyTagSuccess(
                        this.$t("warning.notification_tag_success")
                    );
                    this.$refs.modal.hide();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                console.log(e);
            }
        },
        async add() {
            try {
                this.form.label = this.form.labelResult.name;
                const res = await this.form.post(
                    "/spam/spam-patterns-labeled/add"
                );
                const { data } = res;

                if (data.code === 0) {
                    notify(
                        this.$t("label.notification"),
                        this.$t(
                            "spam.spam_patterns.labeled.notification_add_success"
                        ),
                        "success",
                        "icon la la-check"
                    );
                    this.closeModal();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                    this.closeModal();
                }
            } catch (e) {
                console.log(e);
            }
        },
        validateForm() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    if (this.isTag) {
                        this.tag();
                    } else {
                        this.add();
                    }
                }
            });
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.isTag = true;
            this.$validator.reset();
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },
        async getLabel() {
            const LabelModalResponse = await axios.post(
                "/spam/spam-patterns-labeled/list-all"
            );

            const optionList = [];

            LabelModalResponse.data.results.groupLabel.forEach(element => {
                let optionListchil = null;
                optionListchil = [];
                optionListchil.text = element.display_name;
                optionListchil.children = [];
                LabelModalResponse.data.results.finalResult.forEach(e => {
                    if (e.parent === element.name) {
                        optionListchil.children.push(e);
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
.content_spam_patterns_labeled_modal {
    background-color: aliceblue;
    padding: 11.9px 16.1px;
}

.label_content_spam_patterns_labeled_modal {
    padding-left: 16.1px;
}

.label-is-selected {
    padding: 15px 15px;
    max-height: 60vh;
    overflow-y: scroll;
}
</style>

<template>
    <form-control
            v-model="labelResult"
            :data-vv-as="$t('spam.spam_patterns.labeled.spam_label')"
            :label="$t('spam.spam_patterns.labeled.spam_label')"
            :type="'select'"
            :is-group="true"
            :select-options="spamLabelsOption"
            :required="required"
            :name="'label'"
            :error="error"
    >
    </form-control>
</template>

<script>
    import axios from "axios"

    export default {
        name: "SpamLabelChosen",
        props: {
            error: {
                type: String,
                default: ''
            },
            required: {
                default: true
            },
            value: {},
            hasParentItem: {
                type: Boolean,
                default: false
            },
            multiple: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                labelResult: null,
                spamLabels: []
            }
        },
        mounted() {
            this.getLabel()
        },
        computed: {
            spamLabelsOption() {
                return {
                    placeholder: this.$t('chosen.spamLabel.placeholder'),
                    multiple: this.multiple,
                    idField: 'name',
                    textField: 'display_name',
                    searchable: false,
                    options: this.spamLabels
                }
            }
        },
        methods: {
            async getLabel() {
                const LabelModalResponse = await axios.post(
                    "/spam/spam-patterns-labeled/list-all"
                );

                const optionList = [];

                LabelModalResponse.data.results.groupLabel.forEach(element => {
                    if (this.hasParentItem) {
                            let optionListchil = null;
                            optionListchil = [];
                            optionListchil.text = element.display_name;
                            optionListchil.children = [];
                            LabelModalResponse.data.results.finalResult.forEach(e => {
                                if (e.parent === element.name) {
                                    optionListchil.children.push(e);
                                }
                            });
                            if (this.hasParentItem) {
                                optionListchil.children.unshift(element)
                            }
                            optionList.push(optionListchil);
                    } else {
                        if (element.name != 'ignore' && element.name != 'unlabel') {
                            let optionListchil = null;
                            optionListchil = [];
                            optionListchil.text = element.display_name;
                            optionListchil.children = [];
                            LabelModalResponse.data.results.finalResult.forEach(e => {
                                if (e.parent === element.name) {
                                    optionListchil.children.push(e);
                                }
                            });
                            if (this.hasParentItem) {
                                optionListchil.children.unshift(element)
                            }
                            optionList.push(optionListchil);
                        }
                    }

                });
                this.spamLabels = optionList;
            },
        },
        watch: {
            labelResult(val) {
                this.$emit('input', val)
            },
            value(newVal, oldVal) {
                if (newVal) {
                    if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                        this.labelResult = newVal
                    }
                } else {
                    this.labelResult = newVal
                }
            }
        },
        $_veeValidate: {
            value() {
                return this.labelResult
            },
            name() {
                return 'label'
            }
        },
    }
</script>

<template>
    <form-control
        v-model="project"
        :label="$t('label.project')"
        :data-vv-as="$t('label.project')"
        name="project"
        :required="required"
        type="select"
        :select-options="projectOptions"
        :error="error"
    />
</template>

<script>
    export default {
        name: "ProjectChosen",
        props: {
            error: {
                type: String,
                default: ""
            },
            value: {},
            multiple: {
                default: true
            },
            hasAllOption: {
                type: Boolean,
                default: false
            },
            required: {
                default: true
            }
        },
        data() {
            return {
                project: null,
                projectOptions: {
                    placeholder: "Chọn dự án ...",
                    multiple: this.multiple,
                    idField: "id",
                    textField: "name",
                    ajax: "/api/project/listing-all",
                    hasAllOption: this.hasAllOption
                }
            };
        },
        watch: {
            project(val) {
                this.$emit("input", val);
            },
            value(newVal, oldVal) {
                if (newVal) {
                    if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                        this.project = newVal;
                    }
                } else {
                    this.project = newVal;
                }
            },
            hasAllOption() {
                this.projectOptions.hasAllOption = this.hasAllOption;
            }
        },
        $_veeValidate: {
            value() {
                return this.project;
            },
            name() {
                return "project";
            }
        }
    }
</script>

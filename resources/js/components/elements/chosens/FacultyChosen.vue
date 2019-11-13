<template>
    <form-control
            v-model="faculty"
            :label="'Chọn khoa'"
            :data-vv-as="$t('label.sms_group')"
            name="faculty"
            :required="required"
            type="select"
            :select-options="facultyOptions"
            :error="error"
    />
</template>

<script>
    export default {
        name: "FacultyChosen",
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
                faculty: null,
                facultyOptions: {
                    placeholder: "Chọn khoa...",
                    multiple: this.multiple,
                    idField: "id",
                    textField: "name",
                    ajax: "/api/admin/faculty/listing-all",
                    hasAllOption: this.hasAllOption
                }
            };
        },
        watch: {
            faculty(val) {
                this.$emit("input", val);
            },
            value(newVal, oldVal) {
                if (newVal) {
                    if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                        this.faculty = newVal;
                    }
                } else {
                    this.faculty = newVal;
                }
            },
            hasAllOption() {
                this.facultyOptions.hasAllOption = this.hasAllOption;
            }
        },
        $_veeValidate: {
            value() {
                return this.faculty;
            },
            name() {
                return "faculty";
            }
        }
    }
</script>

<style scoped>

</style>
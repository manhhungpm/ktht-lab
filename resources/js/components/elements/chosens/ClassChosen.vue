<template>
    <form-control
            v-model="classes"
            :label="'Chọn lớp'"
            :data-vv-as="$t('label.sms_group')"
            name="classes"
            :required="required"
            type="select"
            :select-options="classesOptions"
            :error="error"
    />
</template>

<script>
export default {
    name: "ClassChosen",
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
            classes: null,
            classesOptions: {
                placeholder: "Chọn lớp...",
                multiple: this.multiple,
                idField: "id",
                textField: "name",
                ajax: "/api/admin/class/listing-all",
                hasAllOption: this.hasAllOption
            }
        };
    },
    watch: {
        classes(val) {
            this.$emit("input", val);
        },
        value(newVal, oldVal) {
            if (newVal) {
                if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                    this.classes = newVal;
                }
            } else {
                this.classes = newVal;
            }
        },
        hasAllOption() {
            this.classesOptions.hasAllOption = this.hasAllOption;
        }
    },
    $_veeValidate: {
        value() {
            return this.classes;
        },
        name() {
            return "classes";
        }
    }
}
</script>

<style scoped>

</style>
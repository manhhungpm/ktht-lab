<template>
    <form-control
        v-model="smsGroup"
        :label="$t('label.sms_group')"
        :data-vv-as="$t('label.sms_group')"
        name="smsGroup"
        :required="required"
        type="select"
        :select-options="smsGroupOptions"
        :error="error"
    />
</template>

<script>
export default {
    name: "SmsGroupChosen",
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
            smsGroup: null,
            smsGroupOptions: {
                placeholder: "Chọn nhóm tin...",
                multiple: this.multiple,
                idField: "id",
                textField: "name",
                ajax: "/brandname/sms-groups/listing-all",
                hasAllOption: this.hasAllOption
            }
        };
    },
    watch: {
        smsGroup(val) {
            this.$emit("input", val);
        },
        value(newVal, oldVal) {
            if (newVal) {
                if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                    this.smsGroup = newVal;
                }
            } else {
                this.smsGroup = newVal;
            }
        },
        hasAllOption() {
            this.smsGroupOptions.hasAllOption = this.hasAllOption;
        }
    },
    $_veeValidate: {
        value() {
            return this.smsGroup;
        },
        name() {
            return "smsGroup";
        }
    }
};
</script>

<style scoped></style>

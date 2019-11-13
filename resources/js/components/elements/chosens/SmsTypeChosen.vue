<template>
    <form-control
        v-model="smsType"
        :label="$t('label.sms_type')"
        :data-vv-as="$t('label.sms_type')"
        name="smsType"
        :required="required"
        type="select"
        :select-options="smsTypeOptions"
        :error="error"
        :is-disabled="isDisabled"
/></template>

<script>
export default {
    name: "SmsTypeChosen",
    props: {
        error: {
            type: String,
            default: ""
        },
        required: {
            default: true
        },
        hasAllOption: {
            type: Boolean,
            default: false
        },
        value: {},
        smsGroupId: {
            default: null
        },
        partnerId: {
            default: null
        },
        status: {
            default: null
        },
        multiple: {
            type: Boolean,
            default: false
        },
        isDisabled: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            smsType: null,
            smsTypeOptions: {
                placeholder: "Chọn 1 loại tin...",
                multiple: this.multiple,
                idField: "id",
                textField: "name",
                ajax: "/brandname/sms-types/listing-all",
                postData: {
                    sms_group_id: this.smsGroupId
                },
                hasAllOption: this.hasAllOption
            }
        };
    },
    watch: {
        smsGroupId(val) {
            this.smsTypeOptions.postData.sms_group_id = val;
        },
        smsType(val) {
            this.$emit("input", val);
        },
        value(newVal, oldVal) {
            if (newVal) {
                if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                    this.smsType = newVal;
                }
            } else {
                this.smsType = newVal;
            }
        }
    },
    $_veeValidate: {
        value() {
            return this.smsType;
        },
        name() {
            return "smsType";
        }
    }
};
</script>

<style scoped></style>

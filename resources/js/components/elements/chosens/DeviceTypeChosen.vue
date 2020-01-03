<template>
    <form-control
            v-model="deviceType"
            :label="$t('label.device_type')"
            :data-vv-as="$t('label.device_type')"
            name="deviceType"
            :required="required"
            type="select"
            :select-options="deviceTypeOptions"
            :error="error"
    />
</template>

<script>
    export default {
        name: "DeviceTypeChosen",
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
                deviceType: null,
                deviceTypeOptions: {
                    placeholder: "Chọn nhóm thiết bị...",
                    multiple: this.multiple,
                    idField: "id",
                    textField: "name",
                    ajax: "/api/device/device-type/listing-all",
                    hasAllOption: this.hasAllOption
                }
            };
        },
        watch: {
            deviceType(val) {
                this.$emit("input", val);
            },
            value(newVal, oldVal) {
                if (newVal) {
                    if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                        this.deviceType = newVal;
                    }
                } else {
                    this.deviceType = newVal;
                }
            },
            hasAllOption() {
                this.deviceTypeOptions.hasAllOption = this.hasAllOption;
            }
        },
        $_veeValidate: {
            value() {
                return this.deviceType;
            },
            name() {
                return "deviceType";
            }
        }
    }
</script>

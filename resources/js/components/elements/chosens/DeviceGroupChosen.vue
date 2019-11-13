<template>
    <form-control
            v-model="deviceGroup"
            :label="$t('label.device_group')"
            :data-vv-as="$t('label.device_group')"
            name="deviceGroup"
            :required="required"
            type="select"
            :select-options="deviceGroupOptions"
            :error="error"
    />
</template>

<script>
    export default {
        name: "DeviceGroupChosen",
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
                deviceGroup: null,
                deviceGroupOptions: {
                    placeholder: "Chọn nhóm thiết bị...",
                    multiple: this.multiple,
                    idField: "id",
                    textField: "name",
                    ajax: "/api/device/device-group/listing-all",
                    hasAllOption: this.hasAllOption
                }
            };
        },
        watch: {
            deviceGroup(val) {
                this.$emit("input", val);
            },
            value(newVal, oldVal) {
                if (newVal) {
                    if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                        this.deviceGroup = newVal;
                    }
                } else {
                    this.deviceGroup = newVal;
                }
            },
            hasAllOption() {
                this.deviceGroupOptions.hasAllOption = this.hasAllOption;
            }
        },
        $_veeValidate: {
            value() {
                return this.deviceGroup;
            },
            name() {
                return "deviceGroup";
            }
        }
    }
</script>

<style scoped>

</style>
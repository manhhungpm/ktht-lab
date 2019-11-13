<template>
    <form-control
            v-model="provider"
            :label="$t('label.provider')"
            :data-vv-as="$t('label.provider')"
            name="provider"
            :required="required"
            type="select"
            :select-options="providerOptions"
            :error="error"
    />
</template>

<script>
    export default {
        name: "ProviderChosen",
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
                provider: null,
                providerOptions: {
                    placeholder: "Chọn nhà cung cấp...",
                    multiple: this.multiple,
                    idField: "id",
                    textField: "name",
                    ajax: "/api/device/provider/listing-all",
                    hasAllOption: this.hasAllOption
                }
            };
        },
        watch: {
            provider(val) {
                this.$emit("input", val);
            },
            value(newVal, oldVal) {
                if (newVal) {
                    if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                        this.provider = newVal;
                    }
                } else {
                    this.provider = newVal;
                }
            },
            hasAllOption() {
                this.providerOptions.hasAllOption = this.hasAllOption;
            }
        },
        $_veeValidate: {
            value() {
                return this.provider;
            },
            name() {
                return "provider";
            }
        }
    }
</script>

<style scoped>

</style>
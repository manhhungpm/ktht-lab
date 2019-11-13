<template>
    <form-control
            v-model="store"
            :label="$t('label.store')"
            :data-vv-as="$t('label.store')"
            name="store"
            :required="required"
            type="select"
            :select-options="storeOptions"
            :error="error"
    />
</template>

<script>
    export default {
        name: "StoreChosen",
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
                store: null,
                storeOptions: {
                    placeholder: "Chọn nơi lưu trữ...",
                    multiple: this.multiple,
                    idField: "id",
                    textField: "name",
                    ajax: "/api/device/store/listing-all",
                    hasAllOption: this.hasAllOption
                }
            };
        },
        watch: {
            store(val) {
                this.$emit("input", val);
            },
            value(newVal, oldVal) {
                if (newVal) {
                    if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                        this.store = newVal;
                    }
                } else {
                    this.store = newVal;
                }
            },
            hasAllOption() {
                this.storeOptions.hasAllOption = this.hasAllOption;
            }
        },
        $_veeValidate: {
            value() {
                return this.store;
            },
            name() {
                return "store";
            }
        }
    }
</script>

<style scoped>

</style>
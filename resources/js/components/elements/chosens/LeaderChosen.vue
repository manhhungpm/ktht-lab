<template>
    <form-control
        v-model="user"
        :label="$t('label.leader')"
        :data-vv-as="$t('label.leader')"
        name="user"
        :required="required"
        type="select"
        :select-options="userOptions"
        :error="error"
    />
</template>

<script>
    export default {
        name: "LeaderChosen",
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
                user: null,
                userOptions: {
                    placeholder: "Chọn người phê duyệt ...",
                    multiple: this.multiple,
                    idField: "id",
                    textField: "name",
                    ajax: "/api/admin/user/listing-leader-all",
                    hasAllOption: this.hasAllOption
                }
            };
        },
        watch: {
            user(val) {
                this.$emit("input", val);
            },
            value(newVal, oldVal) {
                if (newVal) {
                    if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                        this.user = newVal;
                    }
                } else {
                    this.user = newVal;
                }
            },
            hasAllOption() {
                this.userOptions.hasAllOption = this.hasAllOption;
            }
        },
        $_veeValidate: {
            value() {
                return this.user;
            },
            name() {
                return "user";
            }
        }
    }
</script>

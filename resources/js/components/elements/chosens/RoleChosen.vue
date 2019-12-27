<template>
    <form-control
        v-model="role"
        :label="$t('label.role')"
        :data-vv-as="$t('label.role')"
        name="role"
        :required="required"
        type="select"
        :select-options="roleOptions"
        :error="error"
    />
</template>

<script>
export default {
    name: "RoleChosen",
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
            role: null,
            roleOptions: {
                placeholder: "Chọn vai trò ...",
                multiple: this.multiple,
                idField: "id",
                textField: "name",
                ajax: "/api/admin/role/listing-all",
                hasAllOption: this.hasAllOption
            }
        };
    },
    watch: {
        role(val) {
            this.$emit("input", val);
        },
        value(newVal, oldVal) {
            if (newVal) {
                if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                    this.role = newVal;
                }
            } else {
                this.role = newVal;
            }
        },
        hasAllOption() {
            this.roleOptions.hasAllOption = this.hasAllOption;
        }
    },
    $_veeValidate: {
        value() {
            return this.role;
        },
        name() {
            return "role";
        }
    }
};
</script>

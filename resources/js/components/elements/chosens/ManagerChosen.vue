<template>
    <form-control
        v-model="manager"
        :label="$t('label.manager')"
        :data-vv-as="$t('label.manager')"
        name="manager"
        :required="required"
        type="select"
        :select-options="managerOptions"
        :error="error"
    />
</template>

<script>
export default {
    name: "ManagerChosen",
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
            manager: null,
            managerOptions: {
                placeholder: "Chọn đơn vị quản lý ...",
                multiple: this.multiple,
                idField: "id",
                textField: "name",
                ajax: "/admin/manager/listing-all",
                hasAllOption: this.hasAllOption
            }
        };
    },
    watch: {
        manager(val) {
            this.$emit("input", val);
        },
        value(newVal, oldVal) {
            if (newVal) {
                if (!oldVal || (oldVal && newVal.id != oldVal.id)) {
                    this.manager = newVal;
                }
            } else {
                this.manager = newVal;
            }
        },
        hasAllOption() {
            this.managerOptions.hasAllOption = this.hasAllOption;
        }
    },
    $_veeValidate: {
        value() {
            return this.manager;
        },
        name() {
            return "manager";
        }
    }
};
</script>

<style scoped></style>

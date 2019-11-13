<template>
    <div class="form-group m-form__group">
        <select2
            ref="input"
            v-model="user"
            class="form-control m-form__control"
            :options="userOptions.options ? userOptions.options : null"
            :placeholder="
                userOptions.placeholder ? userOptions.placeholder : ''
            "
            :multiple="multiple ? multiple : false"
            :searchable="
                userOptions.searchable ? userOptions.searchable : false
            "
            :allow-clear="
                userOptions.allowClear ? userOptions.allowClear : false
            "
            :text-field="userOptions.textField ? userOptions.textField : 'text'"
            :id-field="userOptions.idField ? userOptions.idField : 'id'"
            :ajax="userOptions.ajax ? userOptions.ajax : null"
            :post-data="userOptions.postData ? userOptions.postData : {}"
            :has-all-option="
                userOptions.hasAllOption ? userOptions.hasAllOption : false
            "
            :text-format="
                userOptions.textFormat ? userOptions.textFormat : null
            "
            :disabled="isDisabled"
        ></select2>
    </div>
</template>

<script>
export default {
    name: "UserChosen",
    props: {
        error: {
            type: String,
            default: ""
        },
        value: {
            type: [Object, Array],
            default: () => {}
        },
        multiple: {
            type: Boolean,
            default: false
        },
        hasAllOption: {
            type: Boolean,
            default: false
        },
        required: {
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
            user: null
        };
    },
    computed: {
        userOptions() {
            return {
                placeholder: this.$t("chosen.user.placeholder"),
                idField: "id",
                textField: "name",
                ajax: "/api/admin/user/listing-all",
                hasAllOption: this.hasAllOption
            };
        }
    },
    watch: {
        user(val) {
            this.$emit("input", val);
        },
        value(newVal, oldVal) {
            if (newVal) {
                if (!oldVal || (oldVal && newVal.id !== oldVal.id)) {
                    this.user = newVal;
                }
            } else {
                this.user = newVal;
            }
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
};
</script>

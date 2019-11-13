<template>
    <div class="form-group m-form__group" :class="{ 'has-danger': error }">
        <label v-if="label && type !== 'checkbox'"
            >{{ label }}
            <span v-if="required" class="text-danger">(*)</span></label
        >

        <template
            v-if="type == 'text' || type == 'password' || type == 'number'"
        >
            <input
                v-if="!isGroup"
                ref="input"
                :value="formattedValue"
                :name="name"
                autocomplete="off"
                :type="type"
                class="form-control m-input"
                :readonly="readonly"
                :placeholder="placeholder ? placeholder : label ? label : ''"
                :disabled="isDisabled"
                @input="deformattedValue($event)"
            />

            <div v-else class="input-group m-input-group">
                <div v-if="prepend" class="input-group-prepend">
                    <slot name="input-group-prepend"></slot>
                </div>
                <input
                    ref="input"
                    :value="formattedValue"
                    :name="name"
                    autocomplete="off"
                    :type="type"
                    class="form-control m-input"
                    :placeholder="
                        placeholder ? placeholder : label ? label : ''
                    "
                    :readonly="readonly"
                    :disabled="isDisabled"
                    @input="deformattedValue($event)"
                />
                <div v-if="!prepend" class="input-group-append">
                    <slot name="input-group-append"></slot>
                </div>
            </div>
        </template>

        <template v-else-if="type == 'checkbox'">
            <label class="m-checkbox m-checkbox--state-primary">
                <input
                    ref="input"
                    type="checkbox"
                    :name="name"
                    :checked="value == trueValue"
                    @change="
                        $emit(
                            'input',
                            $event.target.checked ? trueValue : falseValue
                        )
                    "
                />
                {{ label }}
                <span></span>
            </label>
        </template>

        <template v-else-if="type == 'select'">
            <select2
                ref="input"
                class="form-control m-form__control"
                :value="value"
                :options="selectOptions.options ? selectOptions.options : null"
                :placeholder="
                    selectOptions.placeholder ? selectOptions.placeholder : ''
                "
                :multiple="
                    selectOptions.multiple ? selectOptions.multiple : false
                "
                :searchable="
                    selectOptions.searchable ? selectOptions.searchable : false
                "
                :allow-clear="
                    selectOptions.allowClear ? selectOptions.allowClear : false
                "
                :text-field="
                    selectOptions.textField ? selectOptions.textField : 'text'
                "
                :id-field="selectOptions.idField ? selectOptions.idField : 'id'"
                :label-group-field="
                    selectOptions.labelGroupField
                        ? selectOptions.labelGroupField
                        : 'text'
                "
                :ajax="selectOptions.ajax ? selectOptions.ajax : null"
                :post-data="
                    selectOptions.postData ? selectOptions.postData : {}
                "
                :has-all-option="
                    selectOptions.hasAllOption
                        ? selectOptions.hasAllOption
                        : false
                "
                :text-format="
                    selectOptions.textFormat ? selectOptions.textFormat : null
                "
                :disabled="isDisabled"
                :is-group="isGroup"
                @input="
                    e => {
                        this.$emit('input', e);
                    }
                "
            ></select2>
        </template>

        <template v-else>
            <textarea
                ref="input"
                :value="value"
                autocomplete="off"
                :readonly="readonly"
                :type="type"
                class="form-control m-input"
                :placeholder="placeholder ? placeholder : label ? label : ''"
                rows="5"
                :disabled="isDisabled"
                @input="$emit('input', $event.target.value)"
            ></textarea>
        </template>

        <div v-if="error" class="form-control-feedback">{{ error }}</div>
    </div>
</template>

<script>
import numeral from "numeral";

export default {
    name: "FormControl",
    props: {
        value: {},
        label: {
            type: String,
            default: null
        },
        name: {
            type: String,
            default: null
        },
        type: {
            type: String,
            default: "text"
        },
        placeholder: {
            type: String,
            default: null
        },
        required: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: null
        },
        isDisabled: {
            type: Boolean,
            default: false
        },
        selectOptions: {
            type: Object,
            default: () => {}
        },
        isGroup: {
            type: Boolean,
            default: false
        },
        trueValue: {
            default: true
        },
        falseValue: {
            default: false
        },
        prepend: {
            default: true
        },
        readonly: {
            type: Boolean,
            default: false
        },
        numberFormat: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        formattedValue() {
            if (this.numberFormat === true) {
                return numeral(this.value).format("0,0.[00000]");
            } else {
                return this.value;
            }
        }
    },
    methods: {
        deformattedValue($event) {
            let value = $event.target.value;
            if (this.numberFormat === true) {
                if (
                    numeral.validate(value) &&
                    !/[0-9]{1,}[.][0]{0,}[0]$/.test(value)
                ) {
                    this.$emit("input", numeral(value, "0,0.[000000]").value());
                }
            } else {
                this.$emit("input", value);
            }
        }
    },
    $_veeValidate: {
        value() {
            if (this.numberFormat === true) {
                return this.value;
            } else {
                return $(this.$refs.input).val();
            }
        },
        name() {
            return this.name;
        }
    }
};
</script>

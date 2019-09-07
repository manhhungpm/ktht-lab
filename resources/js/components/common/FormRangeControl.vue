<template>
    <div class="form-group m-form__group " :class="{ 'has-danger': error }">
        <div class="row" style="padding:0px 15px">
            <label
                v-if="label"
                :class="{ 'col-3': !inline, 'col-12': inline }"
                style="padding-left: 0px; padding-top:0.625rem"
                >{{ label }}
                <span v-if="required" class="text-danger">(*)</span></label
            >
            <div
                :class="{
                    'col-3': !inline,
                    'col-4': inline,
                    'inline-style-form-range-control': inline
                }"
            >
                <input
                    ref="input_from"
                    :value="formattedValueFrom"
                    :name="name"
                    autocomplete="off"
                    :type="'text'"
                    class="form-control m-input"
                    :readonly="readonly"
                    :placeholder="
                        placeholderFrom ? placeholderFrom : label ? label : ''
                    "
                    :disabled="isDisabled"
                    @input="deformattedValueFrom($event)"
                />
            </div>

            <div
                class="form-range-control-pivot col-1"
                style="text-align: center;padding: 0.625rem;"
            >
                -
            </div>

            <div
                :class="{
                    'col-3': !inline,
                    'col-4': inline,
                    'inline-style-form-range-control': inline
                }"
            >
                <input
                    ref="input_to"
                    :value="formattedValueTo"
                    :name="name"
                    autocomplete="off"
                    :type="'text'"
                    class="form-control m-input"
                    :readonly="readonly"
                    :placeholder="
                        placeholderTo ? placeholderTo : label ? label : ''
                    "
                    :disabled="isDisabled"
                    @input="deformattedValueTo($event)"
                />
            </div>

            <div v-if="append" class="input-group-append col-3">
                <slot name="input-group-append"></slot>
            </div>

            <div
                v-if="error"
                class="form-control-feedback"
                :class="{
                    'offset-3': !inline,
                    'error-style-form-range-control': !inline
                }"
            >
                {{ error }}
            </div>
        </div>
    </div>
</template>

<style>
.error-style-form-range-control {
    padding-left: 15px;
}
.inline-style-form-range-control {
    padding-left: 0px;
    padding-right: 0px;
}
</style>

<script>
import numeral from "numeral";

export default {
    name: "FormRangeControl",
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
        placeholderFrom: {
            type: String,
            default: null
        },
        placeholderTo: {
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
        append: {
            default: false
        },
        readonly: {
            type: Boolean,
            default: false
        },
        numberFormat: {
            type: Boolean,
            default: true
        },
        inline: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            from: null,
            to: null
        };
    },
    computed: {
        formattedValueFrom() {
            // console.log("1", this.value);
            if (this.numberFormat === true) {
                if (this.value) {
                    if (this.value.from) {
                        return numeral(this.value.from).format("0,0.[000000]");
                    } else return null;
                } else return null;
            } else {
                if (this.value) {
                    if (this.value.from) {
                        return this.value.from;
                    } else return null;
                } else return null;
            }
        },
        formattedValueTo() {
            // console.log("2", this.value);
            if (this.numberFormat === true) {
                if (this.value) {
                    if (this.value.to) {
                        return numeral(this.value.to).format("0,0.[000000]");
                    } else return null;
                } else return null;
            } else {
                if (this.value) {
                    if (this.value.to) {
                        return this.value.to;
                    } else return null;
                } else return null;
            }
        }
    },
    methods: {
        deformattedValueFrom($event) {
            let value = $event.target.value;
            // console.log("3", value);
            if (this.numberFormat === true) {
                if (
                    numeral.validate(value) &&
                    !/[0-9]{1,}[.][0]{0,}[0]$/.test(value)
                ) {
                    let newValue = { ...this.value };
                    if (this.value) {
                        newValue.from = numeral(value, "0,0.[000000]").value();
                    } else {
                        newValue = {
                            from: numeral(value, "0,0.[000000]").value(),
                            to: null
                        };
                    }
                    // console.log("3", newValue);
                    this.$emit("input", newValue);
                }
            } else {
                let newValue = { ...this.value };
                if (this.value) {
                    newValue.from = value;
                } else {
                    newValue = { from: value, to: null };
                }
                // console.log("3", newValue);
                this.$emit("input", newValue);
            }
        },
        deformattedValueTo($event) {
            let value = $event.target.value;
            // console.log("4", value);
            if (this.numberFormat === true) {
                if (
                    numeral.validate(value) &&
                    !/[0-9]{1,}[.][0]{0,}[0]$/.test(value)
                ) {
                    let newValue = { ...this.value };
                    if (this.value) {
                        newValue.to = numeral(value, "0,0.[000000]").value();
                    } else {
                        newValue = {
                            from: null,
                            to: numeral(value, "0,0.[000000]").value()
                        };
                    }
                    // console.log("4", newValue);
                    this.$emit("input", newValue);
                }
            } else {
                let newValue = { ...this.value };
                if (this.value) {
                    newValue.to = value;
                } else {
                    newValue = { from: null, to: value };
                }
                // console.log("4", newValue);
                this.$emit("input", newValue);
            }
        }
    },
    $_veeValidate: {
        value() {
            if (this.numberFormat === true) {
                return this.value;
            } else {
                return {
                    from: $(this.$refs.input_from).val(),
                    to: $(this.$refs.input_to).val()
                };
            }
        },
        name() {
            return this.name;
        }
    }
};
</script>

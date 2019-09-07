<template>
    <div
        class="form-group m-form__group"
        :class="{ 'has-danger': error, row: inline }"
    >
        <label v-if="label" :class="{ 'col-3 pt-md-2': inline }"
            >{{ label }}
            <span v-if="required" class="text-danger">(*)</span></label
        >
        <div :class="{ 'col-9': inline }">
            <el-time-picker
                :key="name"
                v-model="time"
                is-range
                class="date-range-picker-full-width"
                style="width:100%"
                :range-separator="$t('component.time_picker.range_separator')"
                :start-placeholder="
                    $t('component.time_picker.start_placeholder')
                "
                :end-placeholder="$t('component.time_picker.end_placeholder')"
                :format="format"
                :value-format="valueFormat"
            >
            </el-time-picker>

            <div
                v-if="error"
                class="form-control-feedback"
                style="margin-top: 8px;"
            >
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    name: "TheTimeRange",
    props: {
        value: {
            type: [String, Array]
        },
        format: {
            type: String,
            default: "HH:mm"
        },
        valueFormat: {
            type: String,
            default: "HH:mm"
        },
        label: {
            type: String,
            default: null
        },
        inline: {
            type: Boolean,
            default: true
        },
        name: {
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
        }
    },
    data() {
        return {
            time: this.value
        };
    },
    computed: {},
    watch: {
        time(val) {
            this.$emit("input", val);
        },
        value: {
            handler(newVal, oldVal) {
                if (newVal != oldVal) {
                    this.time = newVal;
                }
            },
            deep: true
        }
    },
    mounted() {},
    $_veeValidate: {
        value() {
            return this.value;
        },
        name() {
            return this.name;
        }
    }
};
</script>

<style scoped></style>

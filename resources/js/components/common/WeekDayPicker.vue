<template>
    <div
        class="form-group m-form__group"
        :class="{ 'has-danger': error, row: inline }"
    >
        <label
            v-if="label"
            :class="{ 'col-2 pt-md-3': inline }"
            style="text-align: left"
            >{{ label }}
            <span v-if="required" class="text-danger">(*)</span></label
        >
        <template v-for="(value, index) in items">
            <button
                :key="'picker-item-' + index"
                class="btn btn-primary btn-outline-primary m-btn m-btn--custom m--margin-5 no-hover"
                :class="{ active: isPicked(index) !== -1 }"
                :data-value="index"
                @click.prevent="clickHandle($event)"
            >
                {{ value }}
            </button>
        </template>
        <div
            v-if="error"
            class="form-control-feedback col-4"
            style="margin-top: 8px;"
        >
            {{ error }}
        </div>
    </div>
</template>
<script>
import i18n from "~/plugins/i18n";

export default {
    name: "WeekDayPicker",
    props: {
        value: {
            type: Array,
            default: () => []
        },
        fullHeight: {
            type: Boolean,
            default: false
        },
        items: {
            type: Array,
            default: () => [
                i18n.t("common.day.mon"),
                i18n.t("common.day.tue"),
                i18n.t("common.day.wed"),
                i18n.t("common.day.thu"),
                i18n.t("common.day.fri"),
                i18n.t("common.day.sat"),
                i18n.t("common.day.sun")
            ]
        },
        label: {
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
        inline: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            picked: this.value
        };
    },
    watch: {
        value(newVal, oldVal) {
            // console.log("new", newVal);
            // console.log("old", oldVal);
            if (oldVal != newVal) {
                this.picked = newVal;
            }
        }
    },
    methods: {
        async clickHandle(event) {
            const value = $(event.target).attr("data-value");
            $(event.target).blur();
            const index = this.isPicked(value);
            if (index === -1) {
                this.picked.push(value);
            } else {
                this.picked.splice(index, 1);
            }
            await this.$nextTick();
            this.$emit("value", this.picked);
            // console.log(this.picked);
        },
        isPicked(value) {
            const index = this.picked.findIndex(item => item == value);
            return index;
        }
    }
};
</script>

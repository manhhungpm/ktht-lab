<template>
    <button
        class="btn"
        :type="type"
        :class="[
            extraClass,
            styleType !== 'default'
                ? styleType === 'air'
                    ? 'm-btn--pill m-btn--air'
                    : `m-btn--${styleType}`
                : '',
            `btn-${color}`,
            size !== '' ? `btn-${size}` : '',
            outline ? `btn-outline-${color}` : '',
            loading ? 'm-loader m-loader--right m-loader--light' : ''
        ]"
        :disabled="disabled || loading"
    >
        <slot></slot>
    </button>
</template>

<script>
import colors from "~/constants/colors";

export default {
    name: "VButton",
    props: {
        type: {
            type: String,
            default: "button"
        },
        extraClass: {
            type: String,
            default: ""
        },
        styleType: {
            type: String,
            default: "default",
            validator: value =>
                ["default", "square", "pill", "air"].indexOf(value) !== -1
        },
        color: {
            type: String,
            default: "primary",
            validator: value => colors.indexOf(value) !== -1
        },
        size: {
            type: String,
            default: "",
            validator: value => ["lg", "sm", ""].indexOf(value) !== -1
        },
        outline: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        loading: {
            type: Boolean,
            default: false
        }
    }
};
</script>

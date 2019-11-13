<template>
    <div class="alert m-alert" role="alert"
         :class="[
            type != '' ? `m-alert--${type}` : '',
            outline ? 'm-alert--outline' : '',
            color ? `alert-${color}` : '',
            dismiss ? 'alert-dismissible fade show' : '',
            iconClass != '' ? 'm-alert--icon' : ''
         ]">
        <button v-if="dismiss" type="button" class="close" data-dismiss="alert" aria-label="Close"></button>

        <div class="m-alert__icon" v-if="iconClass != ''">
            <i :class="iconClass"></i>
        </div>

        <template v-if="iconClass != ''">
            <div class="m-alert__text">
                <slot></slot>
            </div>
            <slot name="action">

            </slot>
        </template>
        <template v-else>
            <slot></slot>
        </template>
    </div>
</template>

<script>
    import colors from '~/constants/colors'

    export default {
        name: 'Alert',
        props: {
            type: {
                type: String,
                default: '',
                validator: value => ['', 'square', 'air'].indexOf(value) !== -1
            },
            color: {
                type: String,
                default: 'primary',
                validator: value => colors.indexOf(value) !== -1
            },
            outline: {
                type: Boolean,
                default: false
            },
            dismiss: {
                type: Boolean,
                default: false
            },
            iconClass: {
                type: String,
                default: ''
            }
        }
    }
</script>

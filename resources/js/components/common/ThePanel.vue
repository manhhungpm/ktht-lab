<template>
    <div
        class="panel"
        :class="[
            size !== '' ? `panel-${size}` : '',
            fullHeight ? 'full-height' : ''
        ]"
    >
        <h5 v-if="title" class="panel-title">{{ title }}</h5>
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "ThePanel",
    props: {
        title: {
            type: String,
            default: ""
        },
        size: {
            type: String,
            default: "",
            validator: value => ["", "sm", "lg"].indexOf(value) !== -1
        },
        fullHeight: {
            type: Boolean,
            default: false
        }
    }
};
</script>

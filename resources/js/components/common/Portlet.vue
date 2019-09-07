<template>
    <div class="m-portlet ">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <slot name="head"></slot>
                <div v-if="title != null" class="m-portlet__head-title">
                    <span v-if="iconClass" class="m-portlet__head-icon">
                        <i :class="iconClass"></i>
                    </span>
                    <h3 class="m-portlet__head-text">{{ title }}</h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <slot name="tool"></slot>
            </div>
        </div>
        <div class="m-portlet__body">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "Portlet",
    props: {
        title: {
            type: String,
            default: ""
        },
        iconClass: {
            type: String,
            default: null
        }
    }
};
</script>

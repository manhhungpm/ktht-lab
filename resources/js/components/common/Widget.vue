<template>
    <div class="col-md-12 col-lg-6 col-xl-2">
        <div class="m-widget24">
            <div class="m-widget24__item">
                <h4 class="m-widget24__title wrap-text">{{ title }}: </h4>
                <div style="margin-left: 90px; font-size: 30px ">
                    <span class="" :class="typeClassText">
                        {{ value }}
                    </span>
                </div>
                <span class="m-widget24__desc">
                    {{ label }}
                </span>
                <div v-for="item in itemOptions">
                    <span class="" style="margin-left: 1.8rem">
                        {{ item.name }}: {{ item.value }}
                    </span>
                </div>
                <div class="m--space-10"></div>
                <template v-if="isProgressBar == true">
                    <div class="progress m-progress--sm">
                        <div
                            class="progress-bar"
                            :class="typeClassProgressBar"
                            role="progressbar"
                            style="width: 78%;"
                            aria-valuenow="50"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>
                    </div>
                    <span class="m-widget24__change">
                        Thay đổi
                    </span>
                    <span class="m-widget24__number">
                        78%
                    </span>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Widget",
    props: {
        value: {
            type: Number
        },
        label: {
            type: String,
            default: null
        },
        title: {
            type: String,
            default: null
        },
        isProgressBar: {
            type: Boolean,
            default: false
        },
        typeClassProgressBar: {
            type: String,
            default: null,
            validator: function(value) {
                return (
                    [
                        "m--bg-info",
                        "m--bg-brand",
                        "m--bg-danger",
                        "m--bg-success"
                    ].indexOf(value) !== -1
                );
            }
        },
        typeClassText: {
            type: String,
            default: null,
            validator: function(value) {
                return (
                    [
                        "m--font-info",
                        "m--font-brand",
                        "m--font-danger",
                        "m--font-success"
                    ].indexOf(value) !== -1
                );
            }
        },
        itemOptions: {
            type: Array,
            default: () => []
        }
    }
};
</script>

<style scoped></style>

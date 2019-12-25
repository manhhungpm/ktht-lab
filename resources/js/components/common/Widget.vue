<template>
    <div class="col-md-12 col-lg-6 col-xl-3">
        <div class="m-widget24">
            <div class="m-widget24__item">
                <div class="widget-main-content">
                    <h4 class="m-widget24__title wrap-text" style="font-size: 20px">{{ title }}:</h4>

                    <div
                        class=""
                        :class="typeClassText"
                        style="display:flex; justify-content: center;"
                    >
                        <div style="font-size: 40px ">{{ formattedValue }}</div>
                    </div>
                </div>

                <slot name="description" style="font-size: 12px"> </slot>
                <div v-for="item in itemOptions">
                    <li>
                        {{ item.name }}: <b>{{ item.value }}</b>
                    </li>
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
import { formatNumber } from "~/helpers/formats";

export default {
    name: "Widget",
    props: {
        value: {
            type: Number
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
    },
    computed: {
        formattedValue() {
            return formatNumber(this.value);
        }
    }
};
</script>

<style></style>

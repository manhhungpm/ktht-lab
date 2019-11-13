<template>
    <div class="m-scrollable"
         :style="{
            height: height + 'px'
         }">
        <slot></slot>
    </div>
</template>

<script>
    import PerfectScrollbar from 'perfect-scrollbar'

    export default {
        name: 'Scrollbar',
        props: {
            height: {
                type: Number,
                default: 200
            },
            mobileHeight: {
                type: Number,
                default: null
            }
        },
        data: () => {
            return {
                scrollbar: null
            }
        },
        mounted () {
            this.initScrollbar()
        },
        methods: {
            initScrollbar () {
                this.scrollbar = new PerfectScrollbar(this.$el, {
                    wheelSpeed: 0.5,
                    swipeEasing: true,
                    wheelPropagation: false,
                    minScrollbarLength: 40,
                    suppressScrollX: true
                })
            }
        }
    }
</script>

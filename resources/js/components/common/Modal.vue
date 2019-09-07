<template>
    <div class="modal fade" role="dialog">
        <div
            class="modal-dialog modal-lg"
            role="document"
            :class="{ 'modal-dialog-centered': center, 'modal-full': fullSize }"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <slot name="title"
                        ><h5 class="modal-title">{{ title }}</h5></slot
                    >
                    <button
                        type="button"
                        class="close"
                        aria-label="Close"
                        @click="hide"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot></slot>
                </div>
                <div v-if="$slots.footer" class="modal-footer">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Modal",
    props: {
        title: {
            type: String,
            default: ""
        },
        showFooter: {
            type: Boolean,
            default: true
        },
        center: {
            type: Boolean,
            default: false
        },
        fullSize: {
            type: Boolean,
            default: false
        },
        onShow: {
            type: Function,
            default: () => {}
        },
        onShown: {
            type: Function,
            default: () => {}
        },
        onHide: {
            type: Function,
            default: () => {}
        },
        onHidden: {
            type: Function,
            default: () => {}
        }
    },
    mounted() {
        this.registerEvent();
    },
    methods: {
        registerEvent() {
            $(this.$el).on(
                "show.bs.modal",
                function(e) {
                    this.onShow();
                }.bind(this)
            );

            $(this.$el).on(
                "shown.bs.modal",
                function(e) {
                    this.onShown();
                }.bind(this)
            );

            $(this.$el).on(
                "hide.bs.modal",
                function(e) {
                    this.onHide();
                }.bind(this)
            );

            $(this.$el).on(
                "hidden.bs.modal",
                function(e) {
                    this.onHidden();
                }.bind(this)
            );

            $(".modal-dialog", this.$el).draggable({
                handle: ".modal-header"
            });
        },
        show() {
            $(this.$el).modal("show");
        },
        hide() {
            $(this.$el).modal("hide");
        }
    }
};
</script>

<template>
    <dynamic-tag :tag="tag" class="m-dropdown m-dropdown--arrow"
                 :class="classCss">
        <a href="javascript:;"
           class="m-dropdown__toggle"
           :class="headerClass"
           @click="handleToggle('click')"
           @hover="handleToggle('hover')">
            <slot name="header"></slot>
        </a>

        <div class="m-dropdown__wrapper" style="z-index: 99999">
            <span class="m-dropdown__arrow"
                  :class="'m-dropdown__arrow--' + align"></span>
            <div class="m-dropdown__inner">
                <slot name="content"></slot>
            </div>
        </div>
    </dynamic-tag>
</template>

<script>
    export default {
        name: 'Dropdown',
        props: {
            tag: {
                type: String,
                default: 'li'
            },
            placement: {
                type: String,
                default: 'down',
                validator: function (value) {
                    return ['up', 'down'].indexOf(value) !== -1
                }
            },
            align: {
                type: String,
                default: 'left',
                validator: function (value) {
                    return ['left', 'center', 'right'].indexOf(value) !== -1
                }
            },
            toggle: {
                type: String,
                default: 'click',
                validator: function (value) {
                    return ['click', 'hover'].indexOf(value) !== -1
                }
            },
            hoverTimeout: {
                type: Number,
                default: 300
            },
            height: {
                type: [String, Number],
                default: 'auto'
            },
            maxHeight: {
                type: [Boolean, Number],
                default: false
            },
            minHeight: {
                type: [Boolean, Number],
                default: false
            },
            persistent: {
                type: Boolean,
                default: false
            },
            mobileOverlay: {
                type: Boolean,
                default: true
            },
            extraClass: {
                type: String,
                default: ''
            },
            headerClass: {
                style: String,
                default: ''
            }
        },
        data () {
            return {
                isOpen: false,

            }
        },
        mounted () {
            this.handleClickOutside();
        },
        methods: {
            handleToggle (evenType) {
                if (this.toggle == evenType) {
                    this.isOpen = !this.isOpen
                }
            },
            handleClickOutside(){
                $(document).mouseup(function (e) {
                    let el = $(this.$el);

                    if (!el.is(e.target) && el.has(e.target).length === 0)
                    {
                        this.isOpen = false;
                    }
                }.bind(this));
            }
        },
        computed: {
            classCss () {
                let className = this.extraClass

                if (this.isOpen) {
                    className += ' m-dropdown--open'
                }

                className += ' m-dropdown--' + this.placement
                className += ' m-dropdown--align-' + this.align

                return className
            }
        }
    }
</script>

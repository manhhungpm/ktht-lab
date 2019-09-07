<script>
    export default {
        name: 'DynamicTag',
        props: {
            tag: {
                type: String,
                default: 'li'
            }
        },
        render (createElement) {
            return createElement(this.tag, this.$slots.default)
        }
    }
</script>

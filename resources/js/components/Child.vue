<template>
    <transition name="fade" mode="out-in">
        <slot>
            <router-view/>
        </slot>
    </transition>
</template>

<script>
    export default {
        name: 'child'
    }
</script>

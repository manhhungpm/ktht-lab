<template>
    <router-view />
</template>

<script>
export default {
    layout: "default",
    middleware: "auth",
    metaInfo() {
        return { title: "Admin" };
    },
    data: () => ({})
};
</script>

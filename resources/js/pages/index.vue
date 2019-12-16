<template>
    <div>
        <dashboard></dashboard>
    </div>
</template>

<script>
    import Dashboard from "~/pages/dashboard/Dashboard";

    export default {
        components: {Dashboard},
        head() {
            return {
                title: this.$t("menu.dashboard")
            };
        },
        middleware: ["auth"],
        meta: {
            title: "menu.dashboard"
        }
    };
</script>

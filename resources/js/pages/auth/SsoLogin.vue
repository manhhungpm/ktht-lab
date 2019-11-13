<template>
    <div :v-loading="loading"></div>
</template>
<script>
// import axios from "axios";

export default {
    name: "SsoLogin",
    // middleware: "guest",
    transitionName: "page",
    metaInfo() {
        return { title: this.$t("auth.login") };
    },
    props: {
        query: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            remember: true,
            loading: true
        };
    },
    mounted() {
        this.ssoLogin();
    },
    methods: {
        async ssoLogin() {
            this.loading = true;
            this.$store.dispatch("auth/saveToken", {
                token: this.query,
                remember: this.remember
            });
            await this.$store.dispatch("auth/fetchUser");

            // Redirect home.
            this.loading = false;
            this.$router.push({ name: "dashboard" });
        }
    }
};
</script>

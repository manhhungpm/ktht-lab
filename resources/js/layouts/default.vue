<template>
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <Header />

        <div
            class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body"
        >
            <button
                id="m_aside_left_close_btn"
                class="m-aside-left-close  m-aside-left-close--skin-dark "
            >
                <i class="la la-close"></i>
            </button>
            <aside-left />

            <div class="m-grid__item m-grid__item--fluid m-wrapper">
                <div class="m-content">
                    <child />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import Header from "~/components/layouts/Header";
import AsideLeft from "../components/layouts/LeftAside/AsideLeft";

export default {
    name: "MainLayout",
    middleware: ["auth"],
    components: {
        AsideLeft,
        Header
    },
    beforeRouteEnter() {},
    computed: {
        ...mapState({
            routeName: state => state.route.name
        })
    }
};
</script>

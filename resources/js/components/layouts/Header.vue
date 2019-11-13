<template>
    <header
        id="m_header"
        class="m-grid__item m-header "
        m-minimize-offset="200"
        m-minimize-mobile-offset="200"
    >
        <div class="m-container m-container--fluid m-container--full-height">
            <div class="m-stack m-stack--ver m-stack--desktop">
                <!-- BEGIN: Brand -->
                <div class="m-stack__item m-brand  m-brand--skin-dark ">
                    <div class="m-stack m-stack--ver m-stack--general">
                        <div
                            class="m-stack__item m-stack__item--middle m-brand__logo"
                        >
                            <router-link to="/" class="m-brand__logo-wrapper">
                                <img
                                    alt=""
                                    :src="
                                        require('~assets/img/logo/logo3.svg')
                                    "
                                />
                            </router-link>
                        </div>
                        <div
                            class="m-stack__item m-stack__item--middle m-brand__tools"
                        >
                            <!-- BEGIN: Left Aside Minimize Toggle -->
                            <a
                                ref="toggleLeftAside"
                                href="javascript:;"
                                class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block"
                                @click="toggleLeftAside"
                            >
                                <span></span>
                            </a>
                            <!-- END -->

                            <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                            <a
                                id="m_aside_left_offcanvas_toggle"
                                href="javascript:;"
                                class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block"
                                @click="toggleLeftAsideResponsive"
                            >
                                <span></span>
                            </a>

                            <!-- END -->

                            <!-- BEGIN: Responsive Header Menu Toggler -->
                            <a
                                id="m_aside_header_topbar_mobile_toggle"
                                href="javascript:;"
                                class="m-brand__icon m--visible-tablet-and-mobile-inline-block"
                                @click="toggleTopBar"
                            >
                                <i class="flaticon-more"></i>
                            </a>
                            <!-- BEGIN: Topbar Toggler -->
                        </div>
                    </div>
                </div>
                <!-- END: Brand -->

                <div
                    id="m_header_nav"
                    class="m-stack__item m-stack__item--fluid m-header-head"
                >
                    <button
                        id="m_aside_header_menu_mobile_close_btn"
                        class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark "
                    >
                        <i class="la la-close"></i>
                    </button>

                    <div
                        id="m_header_menu"
                        class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark "
                    >
                        <h4 class="page-name">{{ routeName }}</h4>
                    </div>

                    <!-- BEGIN: Topbar -->
                    <div
                        id="m_header_topbar"
                        class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid"
                    >
                        <div class="m-stack__item m-topbar__nav-wrapper">
                            <ul class="m-topbar__nav m-nav m-nav--inline">
                                <!--<notification />-->
                                <language-chosen />
                                <profile-actions />
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    name: "Header",
    computed: {
        // ...mapState({
        //     routeName: state =>
        //         state.route.meta.title
        //             ? state.route.meta.title
        //             : state.route.name
        // })
        routeName() {
            const routeName = this.$store.state.route.name;
            if (routeName) {
                return this.$t("menu." + routeName);
            } else return "Chưa có name trong route";
        }
    },
    methods: {
        toggleLeftAside() {
            let bodyClass = "m-brand--minimize m-aside-left--minimize",
                toggleClass = "m-brand__toggler--active";

            $("body").toggleClass(bodyClass);
            $(this.$refs.toggleLeftAside).toggleClass(toggleClass);
        },

        toggleLeftAsideResponsive() {
            let toggleClass =
                "m-grid__item m-aside-left m-aside-left--skin-dark m-aside-left--on m-grid__item m-aside-left m-aside-left--skin-dark";
            $("#m_aside_left").toggleClass(toggleClass);
        },

        toggleTopBar() {
            let toggleClass = "m-topbar--on";
            $("body").toggleClass(toggleClass);
        }
    }
};
</script>

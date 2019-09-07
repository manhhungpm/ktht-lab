<template>
    <div
        id="m_aside_left"
        class="m-grid__item	m-aside-left  m-aside-left--skin-dark "
    >
        <div
            id="m_ver_menu"
            class="m-aside-menu m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark"
            m-menu-vertical="1"
            m-menu-scrollable="1"
            m-menu-dropdown-timeout="500"
        >
            <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow">
                <template v-for="menu in menus">
                    <router-link
                        v-if="!menu.children"
                        tag="li"
                        class="m-menu__item"
                        exact-active-class="m-menu__item--active"
                        :to="{ name: menu.route.name }"
                    >
                        <a class="m-menu__link">
                            <i class="m-menu__link-icon" :class="menu.icon"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">{{
                                        menu.title
                                    }}</span>
                                </span>
                            </span>
                        </a>
                    </router-link>
                    <li
                        v-else
                        class="m-menu__item m-menu__item--submenu"
                        :class="{
                            ' m-menu__item--expanded m-menu__item--open': checkActiveRoute(
                                menu.children,
                                true
                            )
                        }"
                    >
                        <a
                            href="javascript:;"
                            class="m-menu__link m-menu__toggle"
                        >
                            <i class="m-menu__link-icon" :class="menu.icon"></i>
                            <span class="m-menu__link-text">{{
                                menu.title
                            }}</span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>

                        <div class="m-menu__submenu">
                            <span class="m-menu__arrow"></span>
                            <ul class="m-menu__subnav">
                                <router-link
                                    v-for="item in menu.children"
                                    v-if="!item.children"
                                    :key="item.route.name"
                                    tag="li"
                                    :to="{ name: item.route.name }"
                                    active-class="m-menu__item--active"
                                    class="m-menu__item"
                                >
                                    <a class="m-menu__link ">
                                        <i
                                            class="m-menu__link-bullet m-menu__link-bullet--dot"
                                            ><span></span
                                        ></i>
                                        <span class="m-menu__link-text">{{
                                            item.title
                                        }}</span>
                                    </a>
                                </router-link>

                                <li
                                    v-else
                                    class="m-menu__item m-menu__item--submenu"
                                    :class="{
                                        ' m-menu__item--expanded m-menu__item--open': checkActiveRoute(
                                            item.children
                                        )
                                    }"
                                >
                                    <a
                                        href="javascript:;"
                                        class="m-menu__link m-menu__toggle"
                                    >
                                        <i
                                            class="m-menu__link-bullet m-menu__link-bullet--dot"
                                            ><span></span
                                        ></i>
                                        <span class="m-menu__link-text">{{
                                            item.title
                                        }}</span>
                                        <i
                                            class="m-menu__ver-arrow la la-angle-right"
                                        ></i>
                                    </a>

                                    <div class="m-menu__submenu">
                                        <span class="m-menu__arrow"></span>
                                        <ul class="m-menu__subnav">
                                            <router-link
                                                v-for="subItem in item.children"
                                                :key="subItem.route.name"
                                                tag="li"
                                                :to="{
                                                    name: subItem.route.name
                                                }"
                                                active-class="m-menu__item--active"
                                                class="m-menu__item"
                                            >
                                                <a class="m-menu__link ">
                                                    <i
                                                        class="m-menu__link-bullet m-menu__link-bullet--dot"
                                                        ><span></span
                                                    ></i>
                                                    <span
                                                        class="m-menu__link-text"
                                                        >{{
                                                            subItem.title
                                                        }}</span
                                                    >
                                                </a>
                                            </router-link>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
import menus from "./menuData";
import mMenu from "./menu";
import mUtil from "../../../plugins/util";
import { mapState } from "vuex";

export default {
    name: "LeftAside",
    data() {
        return {
            menus: menus
        };
    },
    mounted() {
        this.$nextTick(() => {
            this.initMenu();
        });
    },
    methods: {
        initMenu() {
            let scroll = {
                height: function() {
                    if (mUtil.isInResponsiveRange("desktop")) {
                        return (
                            mUtil.getViewPort().height -
                            parseInt(mUtil.css("m_header", "height"))
                        );
                    }
                }
            };

            let menu = new mMenu("m_ver_menu", {
                scroll: scroll,
                submenu: {
                    desktop: {
                        default: "accordion",
                        state: {
                            body: "m-aside-left--minimize",
                            mode: "dropdown"
                        }
                    },
                    tablet: "accordion", // menu set to accordion in tablet mode
                    mobile: "accordion" // menu set to accordion in mobile mode
                },

                accordion: {
                    autoScroll: false, // enable auto scrolling(focus) to the clicked menu item
                    expandAll: false // allow having multiple expanded accordions in the menu
                }
            });
        },
        checkActiveRoute(menu, isRoot) {
            if (isRoot) {
                let routNames = [];

                menu.forEach(item => {
                    if (item.route) {
                        routNames.push(item.route.name);
                    }

                    if (item.children) {
                        item.children.forEach(o => {
                            if (o.route) {
                                routNames.push(o.route.name);
                            }
                        });
                    }
                });

                if (routNames.indexOf(this.activeRouteName) !== -1) {
                    return true;
                }
            } else {
                let index = menu.findIndex(item => {
                    if (!item.route) {
                        return false;
                    }

                    return item.route.name == this.activeRouteName;
                });

                return index !== -1;
            }

            return false;
        }
    },
    computed: {
        ...mapState({
            activeRouteName: state => state.route.name
        })
    }
};
</script>

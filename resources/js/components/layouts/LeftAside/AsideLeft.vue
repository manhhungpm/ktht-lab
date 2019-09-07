<template>
    <div
        id="m_aside_left"
        class="m-grid__item	m-aside-left  m-aside-left--skin-dark "
    >
        <perfect-scrollbar :options="scrollOptions">
            <div
                id="m_ver_menu"
                class="m-aside-menu m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark"
                m-menu-vertical="1"
                m-menu-scrollable="1"
                m-menu-dropdown-timeout="500"
            >
                <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow">
                    <template v-for="menu in menus">
                        <template
                            v-if="
                                $hasRole(menu.roles) && $can(menu.permissions)
                            "
                        >
                            <router-link
                                v-if="!menu.children"
                                :key="menu.title"
                                tag="li"
                                class="m-menu__item"
                                exact-active-class="m-menu__item--active"
                                :to="menu.route"
                            >
                                <a class="m-menu__link">
                                    <i
                                        class="m-menu__link-icon"
                                        :class="menu.icon"
                                    ></i>
                                    <span class="m-menu__link-title">
                                        <span class="m-menu__link-wrap">
                                            <span class="m-menu__link-text">{{
                                                $t(menu.title)
                                            }}</span>
                                        </span>
                                    </span>
                                </a>
                            </router-link>
                            <li
                                v-else
                                :key="menu.title"
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
                                    <i
                                        class="m-menu__link-icon"
                                        :class="menu.icon"
                                    ></i>
                                    <span class="m-menu__link-text">{{
                                        $t(menu.title)
                                    }}</span>
                                    <i
                                        class="m-menu__ver-arrow la la-angle-right"
                                    ></i>
                                </a>

                                <div class="m-menu__submenu">
                                    <span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        <template v-for="item in menu.children">
                                            <template
                                                v-if="
                                                    $hasRole(item.roles) &&
                                                        $can(item.permissions)
                                                "
                                            >
                                                <router-link
                                                    v-if="!item.children"
                                                    :key="item.title"
                                                    tag="li"
                                                    :to="item.route"
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
                                                                $t(item.title)
                                                            }}</span
                                                        >
                                                    </a>
                                                </router-link>
                                                <li
                                                    v-else
                                                    :key="item.title"
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
                                                        <span
                                                            class="m-menu__link-text"
                                                            >{{
                                                                $t(item.title)
                                                            }}</span
                                                        >
                                                        <i
                                                            class="m-menu__ver-arrow la la-angle-right"
                                                        ></i>
                                                    </a>

                                                    <div
                                                        class="m-menu__submenu"
                                                    >
                                                        <span
                                                            class="m-menu__arrow"
                                                        ></span>
                                                        <ul
                                                            class="m-menu__subnav"
                                                        >
                                                            <template
                                                                v-for="subItem in item.children"
                                                            >
                                                                <template
                                                                    v-if="
                                                                        $hasRole(
                                                                            subItem.roles
                                                                        ) &&
                                                                            $can(
                                                                                subItem.permissions
                                                                            )
                                                                    "
                                                                >
                                                                    <router-link
                                                                        v-if="
                                                                            !subItem.children
                                                                        "
                                                                        :key="
                                                                            subItem.route
                                                                        "
                                                                        tag="li"
                                                                        :to="
                                                                            subItem.route
                                                                        "
                                                                        active-class="m-menu__item--active"
                                                                        class="m-menu__item"
                                                                    >
                                                                        <a
                                                                            class="m-menu__link "
                                                                        >
                                                                            <i
                                                                                class="m-menu__link-bullet m-menu__link-bullet--dot"
                                                                                ><span></span
                                                                            ></i>
                                                                            <span
                                                                                class="m-menu__link-text"
                                                                                >{{
                                                                                    $t(
                                                                                        subItem.title
                                                                                    )
                                                                                }}</span
                                                                            >
                                                                        </a>
                                                                    </router-link>
                                                                    <li
                                                                        v-else
                                                                        :key="
                                                                            subItem.title
                                                                        "
                                                                        class="m-menu__item m-menu__item--submenu"
                                                                        :class="{
                                                                            ' m-menu__item--expanded m-menu__item--open': checkActiveRoute(
                                                                                subItem.children
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
                                                                            <span
                                                                                class="m-menu__link-text"
                                                                                >{{
                                                                                    $t(
                                                                                        subItem.title
                                                                                    )
                                                                                }}</span
                                                                            >
                                                                            <i
                                                                                class="m-menu__ver-arrow la la-angle-right"
                                                                            ></i>
                                                                        </a>

                                                                        <div
                                                                            class="m-menu__submenu"
                                                                        >
                                                                            <span
                                                                                class="m-menu__arrow"
                                                                            ></span>
                                                                            <ul
                                                                                class="m-menu__subnav"
                                                                            >
                                                                                <template
                                                                                    v-for="subsubItem in subItem.children"
                                                                                >
                                                                                    <template
                                                                                        v-if="
                                                                                            $hasRole(
                                                                                                subsubItem.roles
                                                                                            ) &&
                                                                                                $can(
                                                                                                    subsubItem.permissions
                                                                                                )
                                                                                        "
                                                                                    >
                                                                                        <router-link
                                                                                            :key="
                                                                                                subsubItem.route
                                                                                            "
                                                                                            tag="li"
                                                                                            :to="
                                                                                                subsubItem.route
                                                                                            "
                                                                                            active-class="m-menu__item--active"
                                                                                            class="m-menu__item"
                                                                                        >
                                                                                            <a
                                                                                                class="m-menu__link "
                                                                                            >
                                                                                                <i
                                                                                                    class="m-menu__link-bullet m-menu__link-bullet--dot"
                                                                                                    ><span></span
                                                                                                ></i>
                                                                                                <span
                                                                                                    class="m-menu__link-text"
                                                                                                    >{{
                                                                                                        $t(
                                                                                                            subsubItem.title
                                                                                                        )
                                                                                                    }}</span
                                                                                                >
                                                                                            </a>
                                                                                        </router-link>
                                                                                    </template>
                                                                                </template>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                </template>
                                                            </template>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </template>
                                        </template>
                                    </ul>
                                </div>
                            </li>
                        </template>
                    </template>
                </ul>
            </div>
        </perfect-scrollbar>
    </div>
</template>

<script>
import menus from "~/constants/menus";
import mMenu from "./menu";
import mUtil from "~/plugins/util";

export default {
    name: "AsideLeft",
    data() {
        return {
            menus: menus,
            scrollOptions: {
                wheelPropagation: false
            }
        };
    },
    computed: {
        activeRoute() {
            return this.$router.history.current.fullPath;
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.initMenu();
        });
        // this.toggleMenu();
    },
    methods: {
        checkActiveRoute(menu, isRoot) {
            if (isRoot) {
                const routNames = [];
                menu.forEach(item => {
                    if (item.route) {
                        routNames.push(item.route);
                    }

                    if (item.children) {
                        item.children.forEach(o => {
                            if (o.route) {
                                routNames.push(o.route);
                            }
                        });
                    }
                });

                if (routNames.indexOf(this.activeRoute) !== -1) {
                    return true;
                }
            } else {
                const index = menu.findIndex(item => {
                    if (!item.route) {
                        return false;
                    }

                    return item.route === this.activeRoute;
                });

                return index !== -1;
            }

            return false;
        },
        toggleMenu() {
            $(this.$el).on("click", ".m-menu__toggle", function() {
                $(this)
                    .parent(".m-menu__item--submenu")
                    .toggleClass("m-menu__item--open");
            });
        },
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
        }
    }
};
</script>

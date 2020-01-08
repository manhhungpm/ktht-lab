<template>
    <div
            class="m-grid__item m-grid__item--fluid  m-grid__item--order-tablet-and-mobile-1 m-login__wrapper"
    >
        <div class="m-login__body">
            <div class="m-login__signin">
                <div class="m-login__title">
                    <h3>{{ $t("auth.login") }}</h3>
                </div>

                <form
                        class="m-login__form m-form"
                        @submit.prevent="validateForm"
                        @keydown="form.onKeydown($event)"
                >
                    <div style="height: 48px">
                        <alert
                                v-if="form.errors.has('error')"
                                :outline="true"
                                color="danger"
                        >
                            Thông tin đăng nhập không đúng!
                        </alert>
                    </div>

                    <div
                            class="form-group m-form__group"
                            :class="{ 'has-danger': errors.has('username') }"
                    >
                        <input
                                v-model="form.name"
                                v-validate="'required'"
                                class="form-control m-input"
                                type="text"
                                :placeholder="$t('auth.username')"
                                name="username"
                                :data-vv-as="$t('auth.username')"
                                autocomplete="off"
                        />

                        <div
                                v-if="errors.has('username')"
                                class="form-control-feedback"
                        >
                            {{ errors.first("username") }}
                        </div>
                    </div>
                    <div
                            class="form-group m-form__group"
                            :class="{ 'has-danger': errors.has('password') }"
                    >
                        <input
                                v-model="form.password"
                                v-validate="'required'"
                                class="form-control m-input m-login__form-input--last"
                                type="password"
                                :placeholder="$t('auth.password')"
                                :data-vv-as="$t('auth.password')"
                                name="password"
                        />

                        <div
                                v-if="errors.has('password')"
                                class="form-control-feedback"
                        >
                            {{ errors.first("password") }}
                        </div>
                    </div>

                    <div class="m-login__action">
                        <a href="javascript:;">
                            <v-button
                                    style="margin-left: 133px"
                                    extra-class="m-btn m-btn--air m-login__btn m-login__btn--primary"
                                    style-type="air"
                                    :loading="form.busy"
                                    type="submit"
                            >
                                {{ $t("auth.login") }}
                            </v-button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {API_LOGIN} from "~/constants/url";
    import Form from "vform";
    import moment from "moment";
    import {
        TIME_NOT_ACTIVE_TO_LOGOUT,
        TIME_CHECK_ACTIVE_TO_LOGOUT
    } from "~/constants/code";

    export default {
        name: "Login",
        middleware: "guest",
        layout: "auth",
        transitionName: "page",
        metaInfo() {
            return {title: this.$t("auth.login")};
        },
        data() {
            return {
                form: new Form({
                    name: "",
                    password: ""
                }),
                remember: true
            };
        },
        mounted() {
            $(".m-login__action").tooltip({selector: ".sso-login-button"});
        },
        methods: {
            validateForm() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.login();
                    }
                });
            },
            async login() {
                const {data} = await this.form.post(API_LOGIN);

                this.$store.dispatch("auth/saveToken", {
                    token: data.access_token,
                    remember: this.remember
                });

                // Fetch the user.
                await this.$store.dispatch("auth/fetchUser");

                // Redirect home.
                // this.$router.push({name: "dashboard"});
                // console.log(this.$hasRole("leader"));

                if(this.$hasRole("admin")){
                    this.$router.push({name: "dashboard"});
                } else if (this.$hasRole("leader")){
                    this.$router.push({name: "project"});
                } else if (this.$hasRole("user") || this.$hasRole("stocker")){
                    this.$router.push({name: "rent"});
                } else if (this.$hasRole("root")){
                    this.$router.push({name: "dashboard"});
                }

                setTimeout(() => {
                    if (this.$store.getters["auth/check"]) {
                        $(document).ready(function () {
                            window.localStorage.setItem(
                                "lastActive",
                                moment().format("YYYY-MM-DD HH:mm:ss")
                            );
                            $(document).mousemove(function (event) {
                                window.localStorage.setItem(
                                    "lastActive",
                                    moment().format("YYYY-MM-DD HH:mm:ss")
                                );
                                // console.log(
                                //     "a",
                                //     window.localStorage.getItem("lastActive")
                                // );
                            });
                        });
                    }
                }, 2000);
                // console.log("1111", this.$store.getters["auth/check"]);

                setInterval(async () => {
                    if (window.location.pathname != "/login") {
                        // console.log(moment().format("YYYY-MM-DD HH:mm:ss"));
                        if (
                            moment(
                                window.localStorage.getItem("lastActive"),
                                "YYYY-MM-DD HH:mm:ss"
                            )
                                .add(TIME_NOT_ACTIVE_TO_LOGOUT, "seconds")
                                .isSameOrBefore(moment())
                        ) {
                            if (this.$store.getters["auth/check"]) {
                                await this.$store.dispatch("auth/logout");
                                // window.location = "/login";
                                window.location.reload();
                                // console.log("login");
                            } else {
                                await window.location.reload();
                                // console.log("reload");
                            }
                        }
                    }
                }, 1000 * TIME_CHECK_ACTIVE_TO_LOGOUT);
            }
        }
    };
</script>

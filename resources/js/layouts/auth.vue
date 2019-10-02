<template>
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div
            class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-grid--hor-tablet-and-mobile m-login m-login--6"
        >
            <div
                class="m-grid__item   m-grid__item--order-tablet-and-mobile-2  m-grid m-grid--hor m-login__aside"
            >
                <div
                    class="m-grid__item m-grid__item--fluid m-grid m-grid--ver"
                >
                    <div class="m-grid__item m-grid__item--middle">
                        <span class="m-login__title"
                            >Chào mừng bạn đến với hệ thống <br />
                            AntiSpam Call
                        </span>
                        <span class="m-login__subtitle"
                            >Cung cấp các tính năng nghiệp vụ về hệ thống
                            AntiSpam Call</span
                        >
                    </div>
                </div>
                <div class="m-grid__item">
                    <div class="m-login__info">
                        <div class="m-login__section">
                            <router-link to="/" class="m-link"
                                >© 2019 AntiSpam Call</router-link
                            >
                        </div>
                        <div class="m-login__section">
                            <a href="javascript:;" class="m-link">Privacy</a>
                            <a href="javascript:;" class="m-link">Legal</a>
                            <a href="javascript:;" class="m-link">Contact</a>
                        </div>
                    </div>
                </div>
            </div>

            <child />
        </div>
    </div>
</template>

<script>
export default {
    name: "Auth"
};
</script>

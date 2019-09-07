<template>
    <dropdown
        extra-class="m-nav__item m-topbar__languages m-dropdown--small m-dropdown--header-bg-fill m-dropdown--mobile-full-width"
        header-class="m-nav__link"
        align="right">
        <template slot="header">
            <span class="m-nav__link-text">
                <img class="m-topbar__language-selected-img" :src="require('~assets/img/flags/'+ locale +'.svg')">
            </span>
        </template>

        <template slot="content">
            <div class="m-dropdown__header m--align-center">
                <span class="m-dropdown__header-subtitle">{{ $t('lang.choose_language') }}</span>
            </div>
            <div class="m-dropdown__body">
                <div class="m-dropdown__content">
                    <ul class="m-nav m-nav--skin-light">
                        <li class="m-nav__item" :class="{'m-nav__item--active': locale == 'vi'}">
                            <a href="javascript:;" class="m-nav__link" :class="{'m-nav__link--active': locale == 'vi'}"
                               @click="setLocale('vi')">
                                <span class="m-nav__link-icon">
                                    <img class="m-topbar__language-img" :src="require('~assets/img/flags/vi.svg')"/>
                                </span>
                                <span
                                    class="m-nav__link-title m-topbar__language-text m-nav__link-text">Tiếng Việt</span>
                            </a>
                        </li>
                        <li class="m-nav__item" :class="{'m-nav__item--active': locale == 'en'}">
                            <a href="javascript:;" class="m-nav__link" :class="{'m-nav__link--active': locale == 'en'}"
                               @click="setLocale('en')">
                                <span class="m-nav__link-icon">
                                    <img class="m-topbar__language-img" :src="require('~assets/img/flags/en.svg')"/>
                                </span>
                                <span class="m-nav__link-title m-topbar__language-text m-nav__link-text">English</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </template>
    </dropdown>
</template>

<script>
    import { mapGetters } from 'vuex'
    import { loadMessages } from '~/plugins/i18n'

    export default {
        name: 'LanguageChosen',
        computed: mapGetters({
            locale: 'lang/locale',
            locales: 'lang/locales'
        }),
        methods: {
            async setLocale (locale) {
                if (this.$i18n.locale !== locale) {
                    await loadMessages(locale)
                    this.$store.dispatch('lang/setLocale', { locale })
                }
            }
        }
    }
</script>

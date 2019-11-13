<template>
    <div class="row mb-4">
        <div class="col-md-6">
            <portlet
                v-if="$hasRole(role.ROLE_CC)"
                :title="$t('dashboard.label.spam_warning.title')"
                class="m-portlet--full-height small-portlet"
            >
                <div class="m-widget6">
                    <div class="m-widget6__body">
                        <div class="m-widget6__item">
                            <router-link to="/spam/spam-patterns/warning">
                                {{
                                    $t(
                                        "dashboard.label.spam_warning.unlabel_count"
                                    )
                                }}
                            </router-link>
                            <span
                                class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                            >
                                {{ spam_warning.unlabel_count | number }}
                            </span>
                        </div>
                        <div class="m-widget6__item">
                            <span class="m-widget6__text">
                                {{
                                    $t(
                                        "dashboard.label.spam_warning.warning_phone_count"
                                    )
                                }}
                            </span>
                            <span
                                class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                            >
                                {{ spam_warning.warning_phone_count | number }}
                            </span>
                        </div>
                        <div class="m-widget6__item">
                            <span class="m-widget6__text">
                                {{
                                    $t(
                                        "dashboard.label.spam_warning.review_missing_count"
                                    )
                                }}
                            </span>
                            <span
                                class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                            >
                                {{ spam_warning.review_missing_count | number }}
                            </span>
                        </div>
                        <div class="m-widget6__item">
                            <span class="m-widget6__text">
                                {{
                                    $t(
                                        "dashboard.label.spam_warning.review_wrong_count"
                                    )
                                }}
                            </span>
                            <span
                                class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                            >
                                {{ spam_warning.review_wrong_count | number }}
                            </span>
                        </div>
                    </div>
                </div>
            </portlet>
        </div>

        <div class="col-md-6">
            <portlet
                v-if="$hasRole(role.ROLE_A2P)"
                :title="$t('dashboard.label.a2p_warning.title')"
                class="m-portlet--full-height small-portlet"
            >
                <div class="m-widget6">
                    <div class="m-widget6__body">
                        <div class="m-widget6__item">
                            <span class="m-widget6__text">
                                {{
                                    $t(
                                        "dashboard.label.a2p_warning.remain_pattern_count"
                                    )
                                }}
                            </span>
                            <span
                                class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                            >
                                {{ a2p_warning.remain_pattern_count | number }}
                            </span>
                        </div>
                        <div class="m-widget6__item">
                            <span class="m-widget6__text">
                                {{
                                    $t(
                                        "dashboard.label.a2p_warning.pattern_count"
                                    )
                                }}
                            </span>
                            <span
                                class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                            >
                                {{ a2p_warning.pattern_count | number }}
                            </span>
                        </div>
                        <div class="m-widget6__item">
                            <span class="m-widget6__text">
                                {{
                                    $t(
                                        "dashboard.label.a2p_warning.keyword_count"
                                    )
                                }}
                            </span>
                            <span
                                class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                            >
                                {{ a2p_warning.keyword_count | number }}
                            </span>
                        </div>
                    </div>
                </div>
            </portlet>
        </div>
    </div>
</template>

<style scoped>
div > span {
    font-size: 16px !important;
}
div > a {
    font-size: 16px !important;
}
</style>

<script>
import * as ROLE from "~/constants/roles";
import axios from "axios";

export default {
    name: "OverviewStatistic",
    data() {
        return {
            spamLoading: true,
            spam_warning: {
                unlabel_count: 0,
                warning_phone_count: 0,
                review_missing_count: 0,
                review_wrong_count: 0
            },
            a2pLoading: true,
            a2p_warning: {
                remain_pattern_count: 0,
                pattern_count: 0,
                keyword_count: 0
            },
            role: ROLE
        };
    },
    mounted() {
        this.fetchSpamWarning();
        this.fetchA2PWarning();
    },
    methods: {
        async fetchSpamWarning() {
            if (this.$hasRole(this.role.ROLE_CC)) {
                this.spamLoading = true;
                try {
                    const { data } = await axios.post(
                        "/dashboard/spam-warning"
                    );
                    this.spam_warning = data.results;
                    this.spamLoading = false;
                } catch (e) {}
            }
        },
        async fetchA2PWarning() {
            if (this.$hasRole(this.role.ROLE_A2P)) {
                this.a2pLoading = true;
                try {
                    const { data } = await axios.post("/dashboard/a2p-warning");
                    this.a2p_warning = data.results;
                    this.a2pLoading = true;
                } catch (e) {}
            }
        }
    }
};
</script>

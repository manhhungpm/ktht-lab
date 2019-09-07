<template>
    <div>
        <overview-statistic />
        <spam-block-statistic />
    </div>
</template>

<script>
import OverviewStatistic from "../components/elements/statistics/OverviewStatistic";
import SpamBlockStatistic from "../components/elements/statistics/SpamBlockStatistic";

export default {
    components: { SpamBlockStatistic, OverviewStatistic },
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

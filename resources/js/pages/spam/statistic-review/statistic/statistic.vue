<template>
    <div class="row">
        <div class="col-12">
            <time-range-filter
                :title="'Tìm kiếm'"
                :default-filter="defaultTime"
                :exportable="true"
                :name-shortcut="[
                    'today',
                    'yesterday',
                    'last_7_days',
                    'last_30_days',
                    'this_month',
                    'last_month'
                ]"
                @search="filterTime"
            >
            </time-range-filter>
        </div>
        <div class="col-4">
            <el-collapse :value="['1']">
                <el-collapse-item :title="'Consistency'" name="1">
                    <div>
                        Consistent with real life: in line with the process and
                        logic of real life, and comply with languages and habits
                        that the users are used to;
                    </div>
                    <div>
                        Consistent within interface: all elements should be
                        consistent, such as: design style, icons and texts,
                        position of elements, etc.
                    </div>
                </el-collapse-item>
            </el-collapse>
        </div>
        <div class="col-4">
            <el-collapse :value="['1']">
                <el-collapse-item
                    :title="$t('spam.statistic.pattern.title')"
                    name="1"
                >
                    <div v-loading="patternLoading" class="collapse-wrapper">
                        <template v-if="pattern">
                            <div
                                v-for="(value, name) in pattern"
                                :key="name"
                                class="row"
                            >
                                <div class="col-9">
                                    <b>
                                        {{
                                            $t("spam.statistic.pattern." + name)
                                        }}</b
                                    >
                                </div>
                                <div class="col-3 text-right">
                                    {{ value | number }}
                                </div>
                            </div>
                        </template>
                    </div>
                </el-collapse-item>
            </el-collapse>
        </div>
        <div class="col-4">
            <el-collapse :value="['1']">
                <el-collapse-item
                    :title="$t('spam.statistic.phone.title')"
                    name="1"
                >
                    <div v-loading="phoneLoading" class="collapse-wrapper">
                        <template v-if="phone">
                            <div
                                v-for="(value, name) in phone"
                                :key="name"
                                class="row"
                            >
                                <div class="col-9">
                                    <b>
                                        {{
                                            $t("spam.statistic.phone." + name)
                                        }}</b
                                    >
                                </div>
                                <div class="col-3 text-right">
                                    {{ value | number }}
                                </div>
                            </div>
                        </template>
                    </div>
                </el-collapse-item>
            </el-collapse>
        </div>
    </div>
</template>

<style>
.collapse-wrapper {
    width: 100%;
    height: 150px;
}
</style>

<script>
import axios from "axios";
import moment from "moment";
export default {
    head() {
        return {
            title: this.$t("menu.spam.statistic")
        };
    },
    middleware: "auth",
    meta: {
        title: "menu.dashboard"
    },
    data: () => {
        return {
            pattern: null,
            patternLoading: true,
            phone: null,
            phoneLoading: true,
            defaultTime: {
                time: [
                    moment()
                        .startOf("day")
                        .subtract(1, "days")
                        .format("YYYY-MM-DD HH:mm:ss"),
                    moment()
                        .startOf("day")
                        .format("YYYY-MM-DD HH:mm:ss")
                ]
            },
            tableFilter: {
                time: [
                    moment()
                        .startOf("day")
                        .subtract(1, "days")
                        .format("YYYY-MM-DD HH:mm:ss"),
                    moment()
                        .startOf("day")
                        .format("YYYY-MM-DD HH:mm:ss")
                ]
            }
        };
    },
    computed: {},
    watch: {},
    mounted() {
        this.loadData();
    },
    methods: {
        async filterTime(data) {
            this.tableFilter = data;
            await this.$nextTick();
            this.loadData();
        },
        async loadData() {
            this.patternLoading = true;
            this.phoneLoading = true;
            try {
                const phone = await axios.post("/spam/statistic/phone", {
                    from: this.tableFilter.time[0],
                    to: this.tableFilter.time[1]
                });
                this.phone = phone.data;
                this.phoneLoading = false;
                const pattern = await axios.post("/spam/statistic/pattern", {
                    from: this.tableFilter.time[0],
                    to: this.tableFilter.time[1]
                });
                this.pattern = pattern.data;
                this.patternLoading = false;
            } catch (e) {
                console.log(e);
            }
        }
    }
};
</script>

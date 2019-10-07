<template>
    <div>
        <div class="row">
            <form-control
                v-model="form.msisdn"
                class="col-xl-4 col-lg-6 offset-lg-0 offset-xl-1"
                name="alias"
                :label="$t('statistic_suspected_phone.table.phone_number')"
                :placeholder="
                    $t('statistic_suspected_phone.placeholder.phone_number')
                "
            ></form-control>

            <form-control
                v-model="form.duration_type"
                class="col-xl-4 col-lg-6 offset-xl-2 offset-lg-0"
                :type="'select'"
                :select-options="timeOptions"
                :label="this.$t('statistic_suspected_phone.table.time_range')"
            >
            </form-control>

            <form-control
                v-model="form.carrier"
                class="col-xl-4 col-lg-6 offset-lg-0 offset-xl-1"
                :type="'select'"
                :select-options="carrierOptions"
                :label="this.$t('statistic_suspected_phone.table.carrier')"
            >
            </form-control>

            <form-control
                v-model="form.status"
                class="col-xl-4 col-lg-6 offset-xl-2 offset-lg-0"
                :type="'select'"
                :select-options="statusOptions"
                :label="this.$t('statistic_suspected_phone.table.status')"
            >
            </form-control>

            <div class="col-md-12 d-flex justify-content-center">
                <v-button
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    style="margin-right: 5px"
                    @click.native="search"
                >
                    <span>
                        <i class="la la-search"></i>
                        <span>{{ $t("button.search") }}</span>
                    </span>
                </v-button>
                <v-button
                    color="info"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="reset()"
                >
                    <span>
                        <i class="la la-refresh"></i>
                        <span>Reset</span>
                    </span>
                </v-button>
            </div>
        </div>
    </div>
</template>

<script>
import Form from "vform";
import { SUGGEST_LOCK, LOCKED, UNLOCKED, BYPASSED } from "~/constants/constant";

const defaultForm = {
    msisdn: "",
    carrier: null,
    status: null,
    duration_type: {
        id: 1,
        text: "3 ngày"
    }
};
export default {
    name: "SpamCallDetailFilter",
    data() {
        return {
            form: new Form(defaultForm)
        };
    },
    computed: {
        timeOptions() {
            return {
                placeholder: this.$t(
                    "statistic_suspected_phone.placeholder.time_range"
                ),
                multiple: false,
                searchable: false,
                options: [
                    {
                        id: 1,
                        text: "3 ngày"
                    },
                    {
                        id: 2,
                        text: "7 ngày"
                    },
                    {
                        id: 3,
                        text: "30 ngày"
                    }
                ]
            };
        },
        carrierOptions() {
            return {
                placeholder: this.$t(
                    "statistic_suspected_phone.placeholder.carrier"
                ),
                multiple: false,
                searchable: true,
                options: [
                    {
                        id: -1,
                        text: this.$t("statistic_suspected_phone.carrier.all"),
                        value: "all"
                    },
                    {
                        id: 1,
                        text: "Viettel",
                        value: "viettel"
                    },
                    {
                        id: 2,
                        text: "Vinaphone",
                        value: "vinaphone"
                    },
                    {
                        id: 3,
                        text: "Mobifone",
                        value: "mobifone"
                    },
                    {
                        id: 4,
                        text: "Vietnamobile",
                        value: "vietnamobile"
                    },
                    {
                        id: 5,
                        text: "Gmobile",
                        value: "gmobile"
                    },
                    {
                        id: 6,
                        text: this.$t(
                            "statistic_suspected_phone.carrier.other"
                        ),
                        value: "other"
                    }
                ]
            };
        },
        statusOptions() {
            return {
                placeholder: this.$t(
                    "statistic_suspected_phone.placeholder.status"
                ),
                multiple: true,
                searchable: false,
                options: [
                    {
                        id: SUGGEST_LOCK,
                        text: this.$t(
                            "statistic_suspected_phone.status.suggest"
                        )
                    },
                    {
                        id: LOCKED,
                        text: this.$t("statistic_suspected_phone.status.locked")
                    },
                    {
                        id: UNLOCKED,
                        text: this.$t(
                            "statistic_suspected_phone.status.unlocked"
                        )
                    },
                    {
                        id: BYPASSED,
                        text: this.$t(
                            "statistic_suspected_phone.status.bypassed"
                        )
                    }
                ]
            };
        }
    },
    methods: {
        reset() {
            this.form = new Form(defaultForm);
        },
        async search() {
            let filter = {
                msisdn: this.form.msisdn
            };
            if (this.form.carrier && this.form.carrier.value) {
                filter.carrier = this.form.carrier.value;
            }
            if (this.form.status && this.form.status.length > 0) {
                filter.status = this.form.status.map(e => {
                    return e.id;
                });
            }
            if (this.form.duration_type && this.form.duration_type.id) {
                filter.duration_type_id = this.form.duration_type.id;
            }
            await this.$nextTick();
            this.$emit("search", filter);
        }
    }
};
</script>

<style scoped></style>

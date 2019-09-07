<template>
    <modal
        ref="modal"
        :title="$t('brandname.report.detailed.add')"
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <form-control
                v-model="form.phone"
                v-validate="'required|max:100'"
                :label="$t('admin.users.phone')"
                name="phone"
                autocomplete="off"
                :placeholder="$t('brandname.report.detailed.placeholder.phone')"
                :error="errors.first('phone') || form.errors.get('phone')"
                :required="true"
                :data-vv-as="$t('brandname.report.detailed.phone')"
            >
            </form-control>

            <the-date-range
                v-model="form.time"
                v-validate="'required|afterWithinAWeek'"
                name="time"
                :data-vv-as="$t('filter.timerange')"
                :inline="false"
                :label="$t('component.date_picker.label')"
                :error="errors.first('time')"
                :shortcut="true"
                :required="true"
                :name-shortcut="[
                    'today',
                    'yesterday',
                    'last_7_days',
                    'last_30_days',
                    'this_month',
                    'last_month'
                ]"
            >
            </the-date-range>
        </form>
        <template slot="footer">
            <button class="btn btn-info" @click="closeModal">
                {{ $t("button.cancel") }}
            </button>
            <button class="btn btn-primary" @click="validateForm">
                {{ $t("button.add_request") }}
            </button>
        </template>
    </modal>
</template>

<script>
import Form from "vform";
import axios from "axios";

import { FORM_LABEL_WIDTH } from "~/constants/constant";
import FormControl from "~/components/common/FormControl";
import { SUCCESS } from "~/constants/code";
import {
    notifyTryAgain,
    notifyAddSuccess,
    notifyNoPermission
} from "~/helpers/bootstrap-notify";
import moment from "moment";

const defaultForm = {
    id: "",
    name: "",
    phone: "",
    from: "",
    to: "",
    time: ""
};

export default {
    name: "RequestModal",
    components: { FormControl },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            form: new Form(defaultForm),
            formLabelWidth: FORM_LABEL_WIDTH
        };
    },
    computed: {},
    mounted() {},
    methods: {
        show() {
            this.$refs.modal.show();
        },
        onModalHidden() {
            this.form = new Form(defaultForm);
            this.$validator.reset();
        },
        validateForm() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.addDetailRequest();
                }
            });
        },
        closeModal() {
            this.$refs.modal.hide();
        },
        async addDetailRequest() {
            console.log(this.form.time);
            console.log(this.form.phone);
            try {
                const res = await this.form.post(
                    "/brandname/report-detail-request/add"
                );
                const { data } = res;

                if (data.code === SUCCESS) {
                    notifyAddSuccess();
                    this.closeModal();
                    this.onActionSuccess();
                } else {
                    notifyTryAgain();
                }
            } catch (e) {
                const { status } = e.response;

                if (status != 403) {
                    notifyNoPermission();
                }
            }
        }
    }
};
</script>

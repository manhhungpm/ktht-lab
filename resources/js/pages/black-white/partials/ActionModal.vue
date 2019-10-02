<template>
    <modal
        ref="modal"
        :title="$t('label.notification')"
        :on-hidden="onModalHidden"
    >
        <form
            class="m-form m-form--state m-form--label-align-right"
            @submit.prevent="validateForm"
        >
            <h4>
                Bạn có muốn {{ isActive ? "kích hoạt" : "vô hiệu" }} các đầu số:
            </h4>
            <div class=" col-12 form-group m-form__group label-is-selected">
                <div
                    v-for="(item, index) in form.alias"
                    :key="item.id"
                    class="text-danger text-left"
                >
                    {{ index + 1 }}. {{ item.alias }}
                </div>
            </div>
        </form>
        <template slot="footer">
            <button class="btn btn-info" @click="closeModal">
                {{ $t("button.cancel") }}
            </button>
            <button class="btn btn-primary" @click="validateForm">
                {{ isActive ? $t("button.active") : $t("button.disable") }}
            </button>
        </template>
    </modal>
</template>

<script>
import Modal from "~/components/common/Modal";
import Form from "vform";
import {
    notifyTryAgain,
    notifyActiveSuccess,
    notifyDisableSuccess
} from "~/helpers/bootstrap-notify";
import axios from "axios";

const defaultForm = {
    id: [],
    alias: []
};
export default {
    name: "ActionModal",
    components: { Modal },
    props: {
        onActionSuccess: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            form: new Form(defaultForm),
            isActive: false
        };
    },
    methods: {
        show(ids, alias, isActive) {
            switch (isActive) {
                case true:
                    this.form.id = ids;
                    this.form.alias = alias;
                    this.isActive = true;
                    break;
                case false:
                    this.form.id = ids;
                    this.form.alias = alias;
                    this.isActive = false;
                    break;
            }
            this.$refs.modal.show();
        },
        closeModal() {
            this.$refs.modal.hide();
        },
        onModalHidden() {},
        validateForm() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    if (this.isActive) {
                        this.active();
                    } else {
                        this.disable();
                    }
                }
            });
        },
        async active() {
            let res = await axios.post("/blackwhite/list/active", {
                ids: this.form.id
            });
            const { data } = res;

            if (data.code == 0) {
                notifyActiveSuccess();
                this.closeModal();
                this.onActionSuccess();
            } else {
                notifyTryAgain();
            }
        },
        async disable() {
            let res = await axios.post("/blackwhite/list/disable", {
                ids: this.form.id
            });
            const { data } = res;

            if (data.code == 0) {
                notifyDisableSuccess();
                this.closeModal();
                this.onActionSuccess();
            } else {
                notifyTryAgain();
            }
        }
    }
};
</script>

<style scoped></style>

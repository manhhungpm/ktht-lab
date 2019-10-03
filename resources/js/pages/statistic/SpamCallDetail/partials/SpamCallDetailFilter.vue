<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <form-control
                    v-model="form.status"
                    :type="'select'"
                    :select-options="statusOptions"
                    :label="this.$t('brandname.list.active')"
                >
                </form-control>
            </div>

            <div class="col-md-12 d-flex justify-content-center">
                <v-button
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    style="margin-right: 5px"
                    @click.native="validateForm"
                >
                    <span>
                        <i class="la la-search"></i>
                        <span>{{ $t("button.search") }}</span>
                    </span>
                </v-button>
                <v-button
                    color="warning"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="reset"
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
const defaultForm = {
    name: null,
    status: null,
    role: null
};
export default {
    name: "SpamCallDetailFilter",
    data() {
        return {
            form: new Form(defaultForm),
            statusOptions: {
                placeholder: "Chọn thời gian",
                multiple: false,
                searchable: true,
                options: [
                    {
                        id: 1,
                        text: "7 ngày gần đây"
                    },
                    {
                        id: 0,
                        text: "30 ngày gần đây"
                    }
                ]
            }
        };
    },
};
</script>

<style scoped></style>

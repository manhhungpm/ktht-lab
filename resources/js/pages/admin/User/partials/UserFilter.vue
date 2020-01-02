<template>
    <div>
        <div class="row" @keydown.enter.prevent="search">
            <div class="col-md-6">
                <label>{{ $t("admin.users.user_name") }}</label>
                <input
                    v-model="form.name"
                    class="form-control"
                    :placeholder="$t('admin.users.user_name')"
                />
            </div>

            <div class="col-md-6">
                <form-control
                    v-model="form.status"
                    :type="'select'"
                    :select-options="statusOptions"
                    :label="this.$t('admin.users.status')"
                >
                </form-control>
            </div>

            <div class="col-md-6">
                <form-control
                    v-model="form.role"
                    :type="'select'"
                    :select-options="roleOptions"
                    name="role"
                    :label="$t('admin.users.role')"
                ></form-control>
            </div>

            <div class="col-md-6">
                <class-chosen :required="false" v-model="form.class"></class-chosen>
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
                    color="accent"
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
    import axios from "axios";
    import i18n from "~/plugins/i18n";
    import ClassChosen from "../../../../components/elements/chosens/ClassChosen";

    const defaultForm = {
        name: null,
        status: null,
        role: null,
        class: null
    };
    export default {
        name: "UserFilter",
        components: {ClassChosen},
        props: {
            onActionSuccess: {
                type: Function,
                default: () => {
                }
            },
            isRequiredToExport: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                form: new Form(defaultForm),
                statusOptions: {
                    placeholder: i18n.t("admin.users.placeholder.select_status"),
                    multiple: true,
                    searchable: true,
                    options: [
                        {
                            id: 1,
                            text: this.$t("label.active")
                        },
                        {
                            id: 0,
                            text: this.$t("label.disable")
                        }
                    ]
                },
                roleOptions: {
                    placeholder: i18n.t("admin.users.placeholder.select_role"),
                    multiple: true,
                    searchable: true,
                    options: [],
                    textField: "text",
                    idField: "id"
                },
                roleList: []
            };
        },
        mounted() {
            this.loadingData();
        },
        methods: {
            validateForm() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.search();
                    }
                });
            },
            search() {
                let searchParams = this.filter();
                this.$emit("search", searchParams);
            },
            reset() {
                this.form = new Form(defaultForm);
            },
            filter() {
                let searchParams = {};

                if (this.form.name) {
                    searchParams.name = this.form.name;
                }

                if (this.form.status) {
                    searchParams.status = this.form.status.map(e => {
                        return e.id;
                    });
                }

                if (this.form.role) {
                    searchParams.role = this.form.role.map(e => {
                        return e.id;
                    });
                }

                if (this.form.class) {
                    searchParams.class = this.form.class.map(e => {
                        return e.id;
                    });
                }

                console.log(searchParams);
                return searchParams;
            },
            async getRole() {
                try {
                    const res = await axios.post("/admin/role/listing");
                    const {data} = res;

                    this.roleList = data.data;
                    // const displayName = [
                    //     "A2P",
                    //     "Admin",
                    //     "CC",
                    //     "Csp",
                    //     "Politic",
                    //     "Roaming",
                    //     "Root",
                    //     "Sms2way",
                    //     "User"
                    // ];
                    // this.roleList.forEach(function(value, index) {
                    //     value.display_name = displayName[index];
                    // });
                } catch (e) {
                    console.log(e);
                }
            },
            async loadingData() {
                await this.getRole();

                for (var i = 0; i < this.roleList.length; i++) {
                    this.roleOptions.options.push({
                        id: this.roleList[i].id,
                        // text: this.roleList[i].display_name,
                        text: this.roleList[i].name,
                        display_name: this.roleList[i].display_name
                    });
                }
            }
        }
    };
</script>

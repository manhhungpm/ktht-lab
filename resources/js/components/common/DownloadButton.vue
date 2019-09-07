<template>
    <v-button
        color="info"
        style-type="air"
        class="m-btn m-btn--icon m--margin-right-10"
        :loading="loadingExport"
        @click.native="exportFile"
    >
        <span>
            <i class="la la-cloud-download"></i>
            <span>{{ $t("button.export") }}</span>
        </span>
    </v-button>
</template>

<script>
import axios from "axios";
import { downloadFile } from "~/helpers/downloadFile";

export default {
    name: "DownloadButton",
    props: {
        url: {
            type: String,
            default: null
        },
        postData: {
            type: Object,
            default: null
        }
    },
    data: () => ({
        loadingExport: false
    }),
    methods: {
        async exportFile() {
            if (!this.loadingExport) {
                this.loadingExport = true;
                if (this.url != null) {
                    try {
                        let res = await axios.post(this.url, this.postData, {
                            responseType: "blob"
                        });

                        downloadFile(res);
                        this.loadingExport = false;
                    } catch (e) {
                        this.loadingExport = false;
                    }
                }
            }
        }
    }
};
</script>

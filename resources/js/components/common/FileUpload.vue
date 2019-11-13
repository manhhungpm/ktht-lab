<template>
    <div class="m-dropzone dropzone m-dropzone--primary">
        <div class="m-dropzone__msg dz-message needsclick">
            <h3 class="m-dropzone__msg-title">{{ title }}</h3>
            <div class="m-dropzone__msg-desc">{{ desc }}</div>
            <div class="m-dropzone__msg-desc">
                {{ $t("component.dropzone.allowed_extension") }}
                {{ acceptedFiles }}
            </div>
            <div class="m-dropzone__msg-desc">
                {{ $t("component.dropzone.max_size") }} {{ maxFilesize }} MB
            </div>
        </div>
    </div>
</template>

<script>
import Dropzone from "dropzone";
import unionBy from "lodash/unionBy";
import filter from "lodash/filter";

export default {
    name: "FileUpload",
    props: {
        title: {
            type: String,
            default: "Chọn tập tin để tải lên"
        },
        desc: {
            type: String,
            default: "Bạn có thể kéo thả hoặc nhấn vào để chọn..."
        },
        uploadMultiple: {
            type: Boolean,
            default: false
        },
        maxFiles: {
            type: Number,
            default: 1
        },
        maxFilesize: {
            type: Number,
            default: 20 //Mb
        },
        acceptedFiles: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            files: []
        };
    },
    watch: {
        files(val) {
            this.$emit("input", val);
        }
    },
    mounted() {
        let dropzone = new Dropzone(this.$el, {
            paramName: "file",
            url: "/api/media/upload",
            uploadMultiple: this.uploadMultiple,
            maxFiles: this.maxFiles,
            maxFilesize: this.maxFilesize,
            addRemoveLinks: true,
            acceptedFiles: this.acceptedFiles,
            parallelUploads: 1,
            dictFileTooBig:
                "Tập tin quá lớn ({{filesize}}MiB). Kích thước cho phép: {{maxFilesize}}MiB.",
            dictInvalidFileType: "Bạn không thể tải lên loại tập tin này.",
            dictResponseError: "Tải lên lỗi. Mã {{statusCode}}.",
            dictCancelUpload: "Hủy tải lên",
            dictCancelUploadConfirmation: "Bạn chắc chắn muốn hủy tải lên?",
            dictRemoveFile: "Xóa tập tin",
            dictMaxFilesExceeded: "Bạn không thể tải thêm tập tin khác."
        });

        dropzone
            .on("success", file => {
                let files = JSON.parse(file.xhr.responseText);
                this.files = unionBy(this.files, files, "id");
            })
            .on("removedfile", file => {
                if (file.status == "success") {
                    let files = JSON.parse(file.xhr.responseText);

                    this.files = filter(this.files, function(item) {
                        return item.id != files[0].id;
                    });
                }
            })
            .on("error", (file, response) => {
                $(file.previewElement)
                    .find(".dz-error-message")
                    .text(response.message);
            });
    },
    $_veeValidate: {
        value() {
            return this.files;
        },
        name() {
            return "file";
        }
    }
};
</script>

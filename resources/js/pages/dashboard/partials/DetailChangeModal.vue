<template>
    <modal ref="diffModal" :title="'Chi tiết sự thay đổi'" :full-size="true" :onShown="onShown"
           ::on-hide="onModalHidden" >

        <div class="row">
            <div class="col-sm-6">
                <label>Dữ liệu cũ</label> <b></b>
            </div>
            <div class="col-sm-6">
                <label>Dữ liệu mới</label> <b></b>
            </div>
        </div>

        <div class="mergely-resizer">
            <div ref="mergely"></div>
        </div>
    </modal>
</template>

<style>
    @import "~codemirror/lib/codemirror.css";
</style>

<script>
    import Form from 'vform'
    import {parserObjectName, htmlEscapeEntities} from '~/helpers/tableHelper'
    import '~/libs/mergely'

    const defaultLogs = {
        oldValue: null,
        newValue: null,
        oldFile: null,
        newFile: null,
        textOldValue: "",
        textNewValue: ""
    }

    export default {
        name: "DetailChangeModal",
        props: {
            onActionSuccess: {
                type: Function,
                default: () => {
                }
            },
        },
        data() {
            return {
                form: new Form(defaultLogs),
                modalShown: false,
            }
        },

        methods: {
            htmlEscapeEntities(value) {
                return htmlEscapeEntities(value)
            },
            onShown() {
                this.initMergely()
            },
            show(item = null) {
                item.newValue = null
                item.oldValue = null
                item.newFile = null
                item.oldFile = null
                item.textOldValue = ""
                item.textNewValue = ""
                if (item != null) {
                    this.form = new Form(item)
                    this.form.newValue = JSON.parse(this.form.new_value)
                    this.form.oldValue = JSON.parse(this.form.old_value)

                    if (this.form.newValue && this.form.newValue.file) {
                        this.form.newFile = JSON.parse(this.form.newValue.file)
                        delete this.form.newValue.file
                    }
                    if (this.form.oldValue && this.form.oldValue.file) {
                        this.form.oldFile = JSON.parse(this.form.oldValue.file)
                        delete this.form.oldValue.file
                    }

                    if (this.form.oldValue) {
                        Object.keys(this.form.oldValue).forEach((key) => {
                            this.form.textOldValue += key + ': ' + this.form.oldValue[key] + '\n'
                        });
                    }
                    if (this.form.newValue) {
                        Object.keys(this.form.newValue).forEach((key) => {
                            this.form.textNewValue += key + ': ' + this.form.newValue[key] + '\n'
                        });
                    }

                    if (this.form.oldFile && this.form.oldFile.length > 0) {
                        this.form.textOldValue += 'file: '
                        for (var i = 0; i < this.form.oldFile.length; i++) {
                            if (i != 0) {
                                this.form.textOldValue += '      '
                            }
                            this.form.textOldValue += `${i + 1}` + '. ' + this.form.oldFile[i].name + '\n'
                        }
                    }

                    if (this.form.newFile && this.form.newFile.length > 0) {
                        this.form.textNewValue += 'file: '
                        for (var i = 0; i < this.form.newFile.length; i++) {
                            if (i != 0) {
                                this.form.textNewValue += '      '
                            }
                            this.form.textNewValue += `${i + 1}` + '. ' + this.form.newFile[i].name + '\n'
                        }
                    }
                }
                this.$refs.diffModal.show()
            },
            onModalHidden() {
                this.form = new Form(defaultLogs)
            },
            initMergely() {
                $(this.$refs.mergely).mergely()
                $(this.$refs.mergely).mergely({
                    wrap_lines: true,
                    line_numbers: false
                });
                $(this.$refs.mergely).mergely('lhs', this.form.textOldValue)

                $(this.$refs.mergely).mergely('rhs', this.form.textNewValue)
            }
        },
        computed: {}
    }
</script>

<template>
    <select
        ref="selectComponent"
        class="form-control m-select2"
        :disabled="isDisabled"
    >
    </select>
</template>

<script>
import "select2";
import isEqual from "lodash/isEqual";
import unionBy from "lodash/unionBy";

export default {
    name: "Select2",
    props: {
        value: {
            type: [Object, Array],
            default: () => {}
        },
        options: {
            type: Array,
            default: () => []
        },
        placeholder: {
            type: String,
            default: "Vui lòng chọn một lựa chọn..."
        },
        multiple: {
            type: Boolean,
            default: false
        },
        searchable: {
            type: Boolean,
            default: true
        },
        allowClear: {
            type: Boolean,
            default: false
        },
        idField: {
            type: String,
            default: "id"
        },
        textField: {
            type: String,
            default: "text"
        },
        labelGroupField: {
            type: String,
            default: "text"
        },
        ajax: {
            type: String,
            default: null
        },
        postData: {
            type: Object,
            default: () => {}
        },
        hasAllOption: {
            type: Boolean,
            default: false
        },
        isDisabled: {
            type: Boolean,
            default: false
        },
        textFormat: {
            type: Function,
            default: null
        },
        isGroup: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            ajaxResult: []
        };
    },
    computed: {
        mapOptions() {
            if (this.isGroup) {
                let options = [];
                if (this.options.length > 0) {
                    this.options.forEach(e => {
                        let optionChild = {};
                        optionChild.text = e.text || e[this.labelGroupField];
                        optionChild.children = [];
                        e.children.forEach(ec => {
                            let optionDescendant = {};
                            optionDescendant.id = ec.id || ec[this.idField];
                            optionDescendant.text =
                                ec.text || ec[this.textField];
                            optionChild.children.push(optionDescendant);
                        });
                        options.push(optionChild);
                    });
                }

                return options;
            } else {
                return this.options.map(item => {
                    let obj = Object.assign({}, item);
                    obj.id = item.id || item[this.idField];
                    obj.text = item.text || item[this.textField];

                    return obj;
                });
            }
        }
    },
    watch: {
        value: async function(newVal, oldVal) {
            if (!isEqual(newVal, oldVal)) {
                //cho phép delete liên tiếp multiple select
                if (this.multiple) {
                    if (newVal == null) {
                        $(this.$refs.selectComponent).select2("close");
                        $(this.$refs.selectComponent).select2("open");
                    } else if (oldVal != null && newVal != null) {
                        if (newVal.length < oldVal.length) {
                            $(this.$refs.selectComponent).select2("close");
                            let select2SearchField = $(this.$el)
                                .parent()
                                .find(".select2-search__field");
                            setTimeout(function() {
                                select2SearchField.focus();
                            }, 10);
                            $(this.$refs.selectComponent).select2("open");
                        }
                    }
                }
                if (this.ajax == null) {
                    let selectedValue = null;
                    if (newVal) {
                        if (this.isGroup) {
                            if (this.multiple) {
                                selectedValue = this.getOriginSelectedValue(
                                    newVal
                                );
                            } else {
                                selectedValue = this.getOriginSelectedValue([
                                    newVal
                                ]);
                            }
                        } else {
                            selectedValue = this.getOriginSelectedValue(newVal);
                        }
                    }
                    $(this.$el)
                        .val(selectedValue)
                        .trigger("change");
                } else {
                    if (newVal) {
                        if (!this.multiple) {
                            if (
                                !oldVal ||
                                (oldVal &&
                                    String(newVal[this.idField]) !=
                                        String(oldVal[this.idField]))
                            ) {
                                var option = new Option(
                                    newVal[this.textField],
                                    newVal[this.idField],
                                    true,
                                    true
                                );

                                $(this.$el)
                                    .append(option)
                                    .trigger("change", [true]);
                                $(this.$el).trigger({
                                    type: "select2:select",
                                    params: {
                                        data: newVal
                                    }
                                });
                            }
                        } else {
                            if (this.hasAllOption) {
                                if (this.checkHasOptionAll(newVal)) {
                                    newVal = this.ajaxResult.filter(e => {
                                        return e.id != -1;
                                    });
                                    this.$emit(
                                        "input",
                                        this.getOriginSelected(newVal)
                                    );
                                }
                            }

                            this.ajaxResult = unionBy(
                                this.ajaxResult,
                                newVal,
                                this.idField
                            );

                            $(this.$el).empty();

                            $(this.$el).val(null);

                            newVal.forEach(item => {
                                var option = new Option(
                                    item[this.textField],
                                    item[this.idField],
                                    true,
                                    true
                                );
                                $(this.$el).append(option);
                            });

                            $(this.$el).trigger("change", [true]);

                            $(this.$el).trigger({
                                type: "select2:select",
                                params: {
                                    data: newVal
                                }
                            });
                        }
                    } else {
                        if (newVal !== undefined) {
                            $(this.$el)
                                .val(null)
                                .trigger("change");
                        }
                    }
                }
            }
        },
        options: function(options) {
            $(this.$el).empty();
            this.initControl();
        },
        destroyed: function() {
            $(this.$el)
                .off()
                .select2("destroy");
        },
        postData: {
            handler: function(newVal, oldVal) {
                if (
                    Object.keys(oldVal).length > 0 &&
                    Object.keys(newVal).length > 0
                ) {
                    let [first] = Object.keys(oldVal);
                    if (oldVal[first] != null && !_.isEqual(newVal, oldVal)) {
                        $(this.$el)
                            .empty()
                            .select2("destroy");
                        this.initControl();
                        this.$emit("input", null);
                    }
                }
            }
            // deep: true
        }
    },
    mounted() {
        this.initControl();
        this.unSelect();
        this.openDropDown();
    },
    methods: {
        initControl() {
            let vm = this;

            let configs = {
                width: "100%",
                placeholder: this.placeholder,
                multiple: this.multiple,
                allowClear: this.allowClear,
                closeOnSelect: !this.multiple
            };

            if (this.$i18n.locale == "vi") {
                configs.language = {
                    errorLoading: function() {
                        return "Có lỗi khi tìm kiếm";
                    },
                    inputTooLong: function(args) {
                        let overChars = args.input.length - args.maximum;
                        return "Bạn đã nhập quá " + overChars + " ký tự.";
                    },
                    inputTooShort: function(args) {
                        var remainingChars = args.minimum - args.input.length;
                        return (
                            "Bạn cần nhập thêm " + remainingChars + " ký tự."
                        );
                    },
                    loadingMore: function() {
                        return "Tải thêm dữ liệu...";
                    },
                    maximumSelected: function(args) {
                        return (
                            "Không thể chọn quá " + args.maximum + " lựa chọn"
                        );
                    },
                    noResults: function() {
                        return "Không tìm thấy kết quả.";
                    },
                    searching: function() {
                        return "Đang tìm kiếm...";
                    }
                };
            }

            if (this.ajax) {
                var $this = this;
                configs.ajax = {
                    url: "/api" + this.ajax,
                    type: "POST",
                    dataType: "json",
                    cache: true,
                    data: function(params) {
                        let query = {
                            search: params.term,
                            page: params.page || 0
                        };
                        Object.assign(query, $this.postData);
                        return query;
                    },
                    processResults: (data, params) => {
                        params.page = params.page || 0;

                        let results = data.results.map(o => {
                            if (this.textFormat) {
                                o.text = this.textFormat(o);
                            } else o.text = o[this.textField];
                            return o;
                        });

                        if (this.hasAllOption) {
                            var all = { id: -1, text: "Tất cả" };
                            all[this.textField] = "Tất cả";

                            if (params.page === 0 && results.length > 0) {
                                results.unshift(all);
                            }
                        }

                        this.ajaxResult = unionBy(
                            this.ajaxResult,
                            results,
                            this.idField
                        );

                        return {
                            results: results,
                            pagination: {
                                more: (params.page + 1) * 10 < data.total
                            }
                        };
                    }
                };

                $(this.$el)
                    .select2(configs)
                    .on("change", function(e, state) {
                        if (typeof state != "undefined" && state) {
                            return false;
                        }

                        let data = $(vm.$el).select2("data");

                        let selectedValue = vm.getOriginSelected(data);
                        vm.$emit("input", selectedValue);
                    });
            } else {
                configs.data = this.mapOptions;
                configs.minimumResultsForSearch = this.searchable ? 0 : -1;
                let selectedValue = this.getOriginSelectedValue(this.value);
                $(this.$el)
                    .select2(configs)
                    .val(selectedValue)
                    .trigger("change")
                    .on("change", function() {
                        let data = $(vm.$el).select2("data");
                        vm.$emit("input", vm.getOriginSelected(data));
                    });
            }
        },
        getOriginSelected(currentItem) {
            let selected = null;
            if (currentItem != null) {
                if (this.isGroup) {
                    if (!this.multiple) {
                        if (currentItem.length != 0) {
                            if (this.ajax) {
                                // selected = this.ajaxResult.find(item => item[this.idField] == (Array.isArray(currentItem) ? currentItem[0][this.idField] : currentItem[this.idField]))
                                console.log("chua xu ly dau, vao dev di");
                            } else {
                                selected = this.getResult(currentItem);
                            }
                        }
                    } else {
                        if (currentItem.length != 0) {
                            if (this.ajax) {
                                // selected = this.ajaxResult.filter(item => currentItem.findIndex(o => o[this.idField] == item[this.idField]) > -1)
                                console.log("chua xu ly dau, vao dev di");
                            } else {
                                selected = this.getResult(currentItem);
                            }
                        }
                    }
                } else {
                    if (!this.multiple) {
                        if (currentItem.length != 0) {
                            if (this.ajax) {
                                selected = this.ajaxResult.find(
                                    item =>
                                        item[this.idField] ==
                                        (Array.isArray(currentItem)
                                            ? currentItem[0][this.idField]
                                            : currentItem[this.idField])
                                );
                            } else {
                                selected = this.options.find(
                                    item =>
                                        item[this.idField] ==
                                        (Array.isArray(currentItem)
                                            ? currentItem[0][this.idField]
                                            : currentItem[this.idField])
                                );
                            }
                        }
                    } else {
                        if (currentItem.length != 0) {
                            if (this.ajax) {
                                selected = this.ajaxResult.filter(
                                    item =>
                                        currentItem.findIndex(
                                            o =>
                                                o[this.idField] ==
                                                item[this.idField]
                                        ) > -1
                                );
                            } else {
                                selected = this.options.filter(
                                    item =>
                                        currentItem.findIndex(
                                            o =>
                                                o[this.idField] ==
                                                item[this.idField]
                                        ) > -1
                                );
                            }
                        }
                    }
                }
            }
            return selected;
        },
        getOriginSelectedValue(currentItem) {
            let selected = this.getOriginSelected(currentItem);
            if (!this.multiple) {
                return selected ? selected[this.idField] : null;
            } else {
                if (selected) {
                    return selected.map(item => item[this.idField]);
                } else {
                    return null;
                }
            }
        },
        checkHasOptionAll(newVal) {
            let hasOptionAll = false;
            newVal.forEach(e => {
                if (e.id == -1) {
                    hasOptionAll = true;
                }
            });
            return hasOptionAll;
        },
        getResult(item) {
            let result = null;
            if (!this.multiple) {
                this.options.forEach(e => {
                    e.children.forEach(ec => {
                        if (item[0].id != undefined) {
                            if (ec[this.idField] == item[0].id) {
                                result = ec;
                            }
                        } else if (item[0][this.idField]) {
                            if (ec[this.idField] == item[0][this.idField]) {
                                result = ec;
                            }
                        }
                    });
                });
            } else {
                result = [];
                item.forEach(itemChild => {
                    this.options.forEach(e => {
                        e.children.forEach(ec => {
                            if (itemChild.id != undefined) {
                                if (itemChild.id == ec[this.idField]) {
                                    result.push(ec);
                                }
                            } else if (itemChild[this.idField] != undefined) {
                                if (
                                    itemChild[this.idField] == ec[this.idField]
                                ) {
                                    result.push(ec);
                                }
                            }
                        });
                    });
                });
            }
            return result;
        },
        async unSelect() {
            await $(this.$el).on("select2:unselect", e => {
                if (this.multiple && !this.ajax) {
                    let oldData = $(this.$el).select2("data");
                    let newData = $(this.$el).select2("data");
                    let unSelectData = e.params.data;
                    oldData.forEach((element, index) => {
                        if (unSelectData.id == element.id) {
                            newData.splice(index, 1);
                        }
                    });

                    this.$emit("input", newData);
                } else if (this.multiple && this.ajax) {
                    let oldData = $(this.$el).select2("data");
                    let newData = $(this.$el).select2("data");
                    let unSelectData = e.params.data;
                    oldData.forEach((element, index) => {
                        if (unSelectData.id == element.id) {
                            newData.splice(index, 1);
                        }
                    });

                    let newVal = newData;
                    newData.forEach((e, index) => {
                        newVal[index][this.textField] = e.text;
                    });

                    this.ajaxResult = unionBy(
                        this.ajaxResult,
                        newVal,
                        this.idField
                    );
                    $(this.$el).empty();

                    $(this.$el).val(null);

                    newVal.forEach(item => {
                        let option = new Option(
                            item[this.textField],
                            item[this.idField],
                            true,
                            true
                        );
                        $(this.$el).append(option);
                    });

                    $(this.$el).trigger("change", [true]);

                    $(this.$el).trigger({
                        type: "select2:select",
                        params: {
                            data: newVal
                        }
                    });

                    this.$emit("input", newVal);
                }
            });
        },
        openDropDown() {
            $(this.$el).on("select2:select", e => {
                if (this.multiple) {
                    $(this.$refs.selectComponent).select2("open");
                }
            });
        }
    }
};
</script>

<template>
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-error-6">
            <div class="m-error_container">
                <div class="m-error_subtitle m--font-light">
                    <h1>Xin lỗi...</h1>
                </div>
                <p class="m-error_description m--font-light">
                    Trang bạn đang tìm kiếm không tồn tại! <br />Hoặc bạn không
                    có quyền truy cập!
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "NotFound",
    layout: "none",
    metaInfo() {
        return { title: "404" };
    }
};
</script>

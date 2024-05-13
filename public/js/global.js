function themeToggle() {
    const element = document.getElementById("theme-toggle");
    if (element.classList.contains("light")) {
        element.classList.remove("light");
        element.classList.add("dark");
    } else {
        element.classList.remove("dark");
        element.classList.add("light");
    }
}

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const preview = document.getElementById('preview');
        preview.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
    document.getElementById('imagePreview').style.display = 'block';
}

function deleteImage() {
    const input = document.getElementById('productImage');
    input.value = ''; // Đặt giá trị của input thành rỗng
    document.getElementById('preview').src = '#'; // Xóa hình ảnh trong phần xem trước
    document.getElementById('imagePreview').style.display = 'none'; // Ẩn phần xem trước hình ảnh
}

function deleteImageFromDB() {
    // Lấy id của sản phẩm
    var productId = "{{ product.id }}";

    // Gửi yêu cầu AJAX để xoá hình ảnh
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/admin/products/{productID}/deleteImage", true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Xử lý phản hồi từ máy chủ (nếu cần)
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Nếu xoá thành công, cập nhật hình ảnh trên giao diện
                document.getElementById('productImagePreview').src = null; // Xoá hình ảnh trên giao diện
                document.querySelector('.image-container .btn').style.display = 'none'; // Ẩn nút "Xoá hình ảnh"
            } else {
                // Xử lý nếu có lỗi xoá hình ảnh
                console.error(response.error);
            }
        }
    };
    xhr.send(JSON.stringify({productId: productId}));
}
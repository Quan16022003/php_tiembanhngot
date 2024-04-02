let sortStates = {}; // Lưu trữ trạng thái sắp xếp cho mỗi cột

// Hàm sắp xếp bảng theo cột
function sortTable(columnIndex) {
    let table, rows, switching, i, x, y, shouldSwitch;
    table = document.querySelector(".product-table");
    switching = true;

    // Kiểm tra nếu đã có trạng thái sắp xếp cho cột này
    if (!sortStates[columnIndex]) {
        sortStates[columnIndex] = "asc"; // Nếu không, mặc định là sắp xếp tăng dần
    } else {
        // Đảo ngược trạng thái sắp xếp nếu đã tồn tại
        sortStates[columnIndex] = sortStates[columnIndex] === "asc" ? "desc" : "asc";
    }

    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[columnIndex];
            y = rows[i + 1].getElementsByTagName("td")[columnIndex];
            let xValue = isNaN(parseFloat(x.innerHTML)) ? x.innerHTML.toLowerCase() : parseFloat(x.innerHTML);
            let yValue = isNaN(parseFloat(y.innerHTML)) ? y.innerHTML.toLowerCase() : parseFloat(y.innerHTML);

            // Sắp xếp tăng dần hoặc giảm dần tùy thuộc vào trạng thái
            if ((sortStates[columnIndex] === "asc" && xValue > yValue) || (sortStates[columnIndex] === "desc" && xValue < yValue)) {
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}

// ADD PRODUCT FORM
function showAddProductForm() {
    let form = document.getElementById("add-product-form");
    form.style.display = "block";
}

function hideAddProductForm() {
    let form = document.getElementById("add-product-form");
    form.style.display = "none";
}

// EDIT PRODUCT FORM
function showEditProductForm(productID) {
    // Tạo một đối tượng XMLHttpRequest
    const xhr = new XMLHttpRequest();

    // Xác định phương thức và URL cho yêu cầu
    xhr.open("GET", "/admin/products/get/" + productID, true);

    // Xử lý sự kiện khi yêu cầu được gửi đi
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Xử lý dữ liệu JSON nhận được
                let product = JSON.parse(xhr.responseText);
                // Điền thông tin sản phẩm vào form sửa sản phẩm
                document.getElementById("productID").value = product.id;
                document.getElementById("productCategoryID").value = product.category_id;
                document.getElementById("productName").value = product.name;
                document.getElementById("productQuantity").value = product.stock;
                document.getElementById("productPrice").value = product.price;
                // Hiển thị form sửa sản phẩm
                document.getElementById("edit-product-form").style.display = "block";
            } else {
                console.error("Có lỗi xảy ra khi gửi yêu cầu lấy thông tin sản phẩm.");
            }
        }
    };

    // Gửi yêu cầu
    xhr.send();
}

function hideEditProductForm() {
    document.getElementById("edit-product-form").style.display = "none";
}

// Hàm xử lý khi nhấp vào nút xóa
function deleteProduct(productID) {
    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/admin/products/delete/" + productID, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Xóa sản phẩm thành công, có thể reload trang hoặc làm gì đó khác
                    location.reload()
                } else {
                    console.error("Có lỗi xảy ra khi gửi yêu cầu xóa sản phẩm.");
                }
            }
        };
        xhr.send("productID=" + productID);
    }
}


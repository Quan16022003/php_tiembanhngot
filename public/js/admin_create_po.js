$(document).ready(function() {
    var i = 1;
    loadDatalist()
    $('#supplierSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        $('#addressInput').val(selectedOption.data('address'));
        $('#contactNameInput').val(selectedOption.data('contact-name'));
        $('#emailInput').val(selectedOption.data('email'));
        $('#phoneInput').val(selectedOption.data('phone'));

    });

    $('#addRowBtn').click( function() {
        i += 1;
        addRow(i)
    });


    $(document).on('input', '.product-quantity, .product-price', function() {
        calculateProductTotal(this);
        calculateTotal(this);
    });

    $(document).on('input', '#shipping-fee, #tax', function() {
        calculateTotal();
    });

    $(document).on('click', '.removeRowBtn', function() {
        $(this).closest('.product-row').remove();
        calculateTotal();
    });

    $('.product-name').on('input', function() {
        // Lấy giá trị data-id của option được chọn từ datalist
        var selectedOption = $(this).val();
        var dataListId = $(this).attr('list');
        var dataId = $('#' + dataListId + ' option[value="' + selectedOption + '"]').data('id');

        // Tìm phần tử input có class 'product-id' trong cùng một hàng
        var productIDInput = $(this).closest('.product-row').find('.product-id');

        // Cập nhật giá trị của input 'product-id' với giá trị data-id tương ứng
        productIDInput.val(dataId);
    });
});

var productList = [];
function loadDatalist() {
    $.ajax({
        url: "/admin/api/get_all_products", // Thay đổi đường dẫn tương ứng với API của bạn
        type: "GET",
        dataType: "json",
        success: function(data) {
            productList = data.products;
            importProductToSelect(1)
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching options:', errorThrown);
        }
    });
}

function importProductToSelect(i) {
    var dataListId = '#productSelect' + i;
    console.log(dataListId);
    $(dataListId).append('<option value="">Chọn một sản phẩm</option>');
    $.each(productList, function(index, product) {
        $(dataListId).append('<option value="' + product.id + '">'+product.name+'</option>');
    });
}

function addRow(i) {
    var newRow = `
      <div class="row product-row">
        <div class="col col-12 col-lg-6">
            <div class="form-floating mb-3">
                <select type="text" class="form-control product-name" name="product_id[]" id="productSelect`+i+`" required>

                </select>
                <label for="productSelect`+i+`">Tên sản phẩm</label>
                </datalist>
            </div>
        </div>
        <div class="col">
            <div class="form-floating mb-3">
                <input type="number" class="form-control product-quantity" name="product_quantity[]" placeholder="Số lượng" required>
                <label for="quantity[]">Số lượng</label>
            </div>
        </div>
        <div class="col">
            <div class="form-floating mb-3">
                <input type="number" class="form-control product-price" name="product_price[]" placeholder="Giá" required>
                <label for="price1">Giá</label>
            </div>
        </div>
        <div class="col">
            <div class="form-floating mb-3">
                <input type="number" class="form-control border-0 shadow-none product-total" readonly>
                <label for="total">Tổng</label>
            </div>
        </div>
        <div class="col col-1">
            <button type="button" class="btn border-0 btn-lg text-danger removeRowBtn">
                <i class="fa-solid fa-circle-minus"></i>
            </button>
        </div>
    </div>`;

    $("#products").append(newRow);
    importProductToSelect(i)
}

function calculateProductTotal(input) {
    const row = $(input).closest('.product-row');
    const quantityValue = row.find('.product-quantity').val();
    const priceValue = row.find('.product-price').val();
    const quantity = !quantityValue ? 0 : parseFloat(quantityValue);
    const price = !priceValue ? 0 : parseFloat(priceValue);
    const total = quantity * price;
    if (!Number.isFinite(total)) {
        row.find('.product-total').val(0);
    } else {
        row.find('.product-total').val(total.toFixed(0));
    }
}

function calculateTotal(input) {
    var subtotal = 0;
    $('.product-row').each(function() {
        var productTotal = parseFloat($(this).find('.product-total').val());
        subtotal += productTotal
    });

    var taxRate = parseFloat($('#tax').val()) / 100;

    var tax = parseInt(subtotal * taxRate);
    var shippingFee = parseInt($('#shipping-fee').val());
    if (isNaN(shippingFee)) {
        shippingFee = 0;
    }

    var total = subtotal + tax + shippingFee;

    $('#subtotal').text(isNaN(subtotal)  ? '_' : subtotal);
    $('#tax-fee').text(isNaN(tax) ? '_' : tax);
    $('#totalPrice').text(isNaN(total) ? '_' : total);
}



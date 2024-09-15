<div class="container">
    <div class="content-container">
        <p>
            <i class="fa-solid fa-cart-shopping" style="font-size: 25px;"></i>
            <span class="h2">Giỏ hàng</span>
        </p>
        <?php
$totalPrice = 0;
if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo '        <div class="card" style="margin-bottom: 10px;">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-2 d-flex justify-content-center">
                <div class="name_col" style="display: none;"></div>
            </div>
            <div class="col-md-3 d-flex justify-content-center">
                <div class="name_col">Tên sản phẩm</div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div class="name_col">Số lượng</div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div class="name_col">Giá bán</div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div class="name_col">Thành tiền</div>
            </div>
            <div class="col-md-1 d-flex justify-content-center">
            </div>
        </div>
    </div>
</div>';
    foreach($_SESSION['cart'] as  $key => $item) {
    $gia = $item['price']*$item['quantity'];
    echo '<div class="card">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-2">
                <img src="'.$item['image'].'"
                    class="img-fluid" alt="Generic placeholder image">
            </div>
            <div class="col-md-3 d-flex justify-content-center">
                <div>
                    <div class="content_col">'.$item['title'].'</div>
                </div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div class="quantity" style="display:flex">
                    <div class="qtyminus">-</div>
                    <input type="text" name="quantity" data-index="'.$key.'" value="'.$item['quantity'].'" min="1" class="qty">
                    <div class="qtyplus">+</div>
                </div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div>
                    <div class="content_col amount-to-format">'.$item['price'].'</div>
                </div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div>
                    <div class="content_col amount-to-format">'.$item['price']*$item['quantity'].'</div>
                </div>
            </div>
            <div class="col-md-1 d-flex justify-content-center">
                <div class="content_col"><a href="http://localhost:8088/shop/cart/delete?index='.$key.'">Xóa</a></div>
            </div>
        </div>
    </div>
    </div>';
    $totalPrice += $item['price']*$item['quantity'];
}
echo '  <div class="fixed-box">
            <div class="bottom-box float-end">
                <p class="sum">
                    <span style="font-size: 16px;padding-right: 5px;">Tổng thanh toán: </span>
                    <span style="font-size: 16px;padding-right: 5px;" class="amount-to-format">'.$totalPrice.'</span>
                </p>
                <form action="http://localhost:8088/shop/cart/checkout" method="post">
                    <div class="buy-btn">
                        <button type="submit" class="btn btn-primary">Đặt hàng</button>
                    </div>
                </form>
            </div>
        </div>';
}
else {
echo '<div class="card" style="height: 400px; border:none; display: flex;justify-content: center;align-items: center; ">
    <img src="'._WEB_ROOT.'/public/clients/images/empty-cart.webp" alt="" style="width:300px; margin-bottom:20px">
    <a href="http://localhost:8088/shop/home">
        <button class="btn btn-primary">Mua ngay</button>
    </a>
</div>';
}
?>
    </div>
</div>

<script>
const profileText = document.querySelector(".profile-text");
const item = document.querySelector(".profile-dropdown");

profileText.addEventListener("click", (event) => {
    item.classList.toggle("dropdown-active");
    event.stopPropagation(); // Ngăn chặn sự kiện click từ việc lan ra ngoài
});

document.addEventListener("click", (event) => {
    const isClickInsideItem = item.contains(event.target);
    const isClickOnProfileText = event.target === profileText;
    if (!isClickInsideItem && !isClickOnProfileText) {
        item.classList.add("dropdown-active");
    }
});

const qtyminus = document.querySelectorAll(".qtyminus");
const qtyplus = document.querySelectorAll(".qtyplus");
const quantityInputs = document.querySelectorAll('.qty');

const updateCart = function(index, quantity) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost:8088/shop/cart/update', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            window.location.reload();
        }
    };

    xhr.send(`index=${index}&quantity=${quantity}`);

};

qtyminus.forEach(button => {
    button.addEventListener("click", function() {
        const parent = this.parentElement;
        const qty = parent.querySelector('.qty');
        const index = qty.getAttribute('data-index');
        console.log(index);
        var val = qty.value;
        if (parseInt(val) > 1) {
            qty.value = parseInt(val) - 1;
        }
        updateCart(index, qty.value);
    });
});

qtyplus.forEach(button => {
    button.addEventListener("click", function() {
        const parent = this.parentElement;
        const qty = parent.querySelector('.qty');
        const index = qty.getAttribute('data-index');
        console.log(index);
        var val = qty.value;
        if (parseInt(val) < 999) {
            qty.value = parseInt(val) + 1;
        }
        updateCart(index, qty.value);
    });
});

quantityInputs.forEach(input => {
    input.addEventListener('blur', function() {
        const index = this.getAttribute('data-index');
        const newValue = parseInt(this.value);

        if (newValue >= 1) {
            updateCart(index, newValue);
        } else {
            this.value = 1;
            updateCart(index, 1);
        }
    });
});

function formatToVND(amount) {
    return amount.toLocaleString('vi-VN', {
        style: 'currency',
        currency: 'VND'
    });
}

// Lặp qua tất cả các thẻ có class="amount-to-format" và định dạng lại số tiền thành VND
document.querySelectorAll('.amount-to-format').forEach(element => {
    const amountValue = parseFloat(element.textContent); // Lấy giá trị số tiền từ nội dung của thẻ
    element.textContent = formatToVND(
        amountValue); // Định dạng lại số tiền thành VND và cập nhật nội dung của thẻ
});
</script>
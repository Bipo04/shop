<div class="container">
    <div class="content-container">
        <h3 style="text-align: center;">Thanh toán</h3>
        <div class="card">
            <div class="content">
                <div class="title">Địa chỉ nhận hàng</div>
                <div class="content-profile">
                    <div class="content-profile-item" id="n">
                        <?php if(isset($_SESSION['temp']) && $_SESSION['temp']['fullname'] != '') {
                            echo $_SESSION['temp']['fullname']; 
                        }
                        else echo $_SESSION['user']['fullname']; ?>
                    </div>
                    <div class="content-profile-item" id="p">
                        <?php if(isset($_SESSION['temp']) && $_SESSION['temp']['phone_number'] != '') {
                            echo $_SESSION['temp']['phone_number']; 
                        }
                        else echo $_SESSION['user']['phone_number']; ?>
                    </div>
                    <div class="content-profile-item" id="a">
                        <?php if(isset($_SESSION['temp']) && $_SESSION['temp']['address'] != '') {
                            echo $_SESSION['temp']['address']; 
                        }
                        else echo $_SESSION['user']['address']; ?>
                    </div>
                    <div class="content-profile-item" id="change"><button>Thay đổi</button></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 d-flex justify-content-center">
                        <div class="name_col" style="display: none;"></div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
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
                </div>
            </div>
            <hr>
            <?php
$totalPrice = 0;
if($data['cart']) {
    $result = $data['cart'];
}
else {
    $result = $_SESSION['cart'];
}
foreach($result as $item) {
    $totalPrice += $item['price']*$item['quantity'];
    echo '
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-2">
                <img src="'.$item['image'].'"
                    class="img-fluid" alt="Generic placeholder image">
            </div>
            <div class="col-md-4 d-flex justify-content-center">
                <div class="content_col">'.$item['title'].'</div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div class="content_col">'.$item['quantity'].'</div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div class="content_col amount-to-format">'.$item['price'].'</div>
            </div>
            <div class="col-md-2 d-flex justify-content-center">
                <div class="content_col amount-to-format">'.$item['price']*$item['quantity'].'</div>
            </div>
        </div>
    </div>
    <hr>';
}
echo '    <div class="flex-end">
<div class="sum">
    <div style="font-size: 16px;padding-right: 5px;">Tổng thanh toán: </div>
    <div id="tongtien" style="font-size: 16px;" class="amount-to-format">'.$totalPrice.'</div>
</div>
</div>';
    ?>
        </div>
    </div>
    <div class="xacnhan">
        <div class="btn btn-primary">Đặt đơn</div>
    </div>
    <div class="Modal hidden">
        <div class="Modal_overlay"></div>
        <div class="formbuy" id="formbuy">
            <div class="loaibo">
                <i class="fa-solid fa-xmark" id="bo"></i>
            </div>
            <h3>Thông tin nhận hàng</h3>
            <div class="Modal-formbuy">
                <div>
                    <label for="fullname">Họ tên người nhận:</label>
                    <input type="text" id="fullname" name="fullname" value="<?php if(isset($_SESSION['temp']) && $_SESSION['temp']['fullname'] != '') {
                            echo $_SESSION['temp']['fullname']; 
                        }
                        else echo $_SESSION['user']['fullname']; ?>">
                </div>
                <div>
                    <label for="phone_number">Số điện thoại:</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?php if(isset($_SESSION['temp']) && $_SESSION['temp']['phone_number'] != '') {
                            echo $_SESSION['temp']['phone_number']; 
                        }
                        else echo $_SESSION['user']['phone_number']; ?>">
                </div>
                <div>
                    <label for="address">Địa chỉ nhận hàng:</label>
                    <input type="text" id="address" name="address" value="<?php if(isset($_SESSION['temp']) && $_SESSION['temp']['address'] != '') {
                            echo $_SESSION['temp']['address']; 
                        }
                        else echo $_SESSION['user']['address']; ?>">
                </div>
                <div>
                    <div class="btn btn-primary" id="saveChangesBtn">Lưu thay đổi</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="orderSuccessModal" class="successModel">
    <div class="successModel-content">
        <span class="close">&times;</span>
        <div class="successModel-content-success">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <p>Đặt hàng thành công!</p>
    </div>
</div>

<script>
const profileText = document.querySelector(".profile-text");
const item = document.querySelector(".profile-dropdown");

profileText.addEventListener("click", (event) => {
    item.classList.toggle("dropdown-active");
    event.stopPropagation();
});

document.addEventListener("click", (event) => {
    const isClickInsideItem = item.contains(event.target);
    const isClickOnProfileText = event.target === profileText;
    if (!isClickInsideItem && !isClickOnProfileText) {
        item.classList.add("dropdown-active");
    }
});
document.querySelector('#change').onclick = () => {
    document.querySelector('.Modal').classList.remove('hidden');
}
document.querySelector('#bo').onclick = () => {
    document.querySelector('.Modal').classList.add('hidden');
}

document.getElementById('saveChangesBtn').addEventListener('click', function() {
    document.querySelector('.Modal').classList.add('hidden');

    var fullname = document.getElementById('fullname').value;
    var phone_number = document.getElementById('phone_number').value;
    var address = document.getElementById('address').value;

    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('n').innerText = fullname;
            document.getElementById('p').innerText = phone_number;
            document.getElementById('a').innerText = address;

        }
    };

    xhr.open('POST', 'http://localhost:8088/shop/cart/checkout', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`fullname=${fullname}&phone_number=${phone_number}&address=${address}&type=change`);
});

var modal = document.getElementById("orderSuccessModal");
var span = document.getElementsByClassName("close")[0];

document.querySelector('.xacnhan').addEventListener('click', function() {
    var fullname = document.getElementById('fullname').value;
    var phone_number = document.getElementById('phone_number').value;
    var address = document.getElementById('address').value;
    
    if (phone_number.trim() === '' || address.trim() === '') {
        document.querySelector('.Modal').classList.remove('hidden');
    }
    else {
        var totalprice = document.querySelector('#tongtien').innerHTML;
    
        totalprice = convertNumber(totalprice);
    
        const xhr = new XMLHttpRequest();
    
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                modal.style.display = "block";
            }
        };
    
        xhr.open('POST', 'http://localhost:8088/shop/cart/buy', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(
            `consignee_name=${fullname}&phone_number=${phone_number}&address=${address}&totalprice=${totalprice}&type=buy`
        );
    }
});

span.onclick = function() {
    modal.style.display = "none";
    window.location.href = "http://localhost:8088/shop/home";
}

function convertNumber(numberStr) {
    // Sử dụng replace() để loại bỏ dấu chấm
    return numberStr.replace(/\./g, '');
}

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
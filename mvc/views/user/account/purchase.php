<style>
a {
    text-decoration: none;
}

.card_state {
    display: flex;
    width: 100%;
    position: sticky;
    top: 0;
}

.state {
    flex-grow: 1;
    flex-shrink: 1;
    flex-basis: 0%;
    align-items: center;
    text-align: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    color: black;
}

.state.active {
    color: red;
}

.state:hover {
    color: red;
}

.img_card_none {
    width: 200px;
}
</style>

<div class="container">
    <div class="sidebar">
        <a class="sidebar-item" href="http://localhost:8088/shop/account/profile">Thông tin cá nhân</a>
        <a class="sidebar-item active" href="http://localhost:8088/shop/account/purchase">Đơn hàng</a>
    </div>
    <div class="content-container">
        <div class="profile-content">
            <h3 style="text-align: center; margin-bottom: 20px">Đơn hàng của bạn</h3>
            <div class="card" style="margin-bottom: 10px; height: 60px; padding: auto; position: sticky;">
                <section class="card_state card-body">
                    <a class="state <?php if($data['type'] == 1) echo 'active'; ?>"
                        href="http://localhost:8088/shop/account/purchase?type=1" title="Tất cả" aria-role="Tab">
                        <span>Tất cả</span>
                    </a>
                    <a class="state <?php if($data['type'] == 2) echo 'active'; ?>"
                        href="http://localhost:8088/shop/account/purchase?type=2" title="Chờ xử lí" aria-role="Tab">
                        <span>Chờ xử lí</span>
                    </a>
                    <a class="state <?php if($data['type'] == 3) echo 'active'; ?>"
                        href="http://localhost:8088/shop/account/purchase?type=3" title="Đang chuẩn bị" aria-role="Tab">
                        <span>Đang chuẩn bị</span>
                    </a>
                    <a class="state <?php if($data['type'] == 4) echo 'active'; ?>"
                        href="http://localhost:8088/shop/account/purchase?type=4" title="Đang giao" aria-role="Tab">
                        <span>Đang giao</span>
                    </a>
                    <a class="state <?php if($data['type'] == 5) echo 'active'; ?>"
                        href="http://localhost:8088/shop/account/purchase?type=5" title="Đã giao" aria-role="Tab">
                        <span>Đã giao</span>
                    </a>
                    <a class="state <?php if($data['type'] == 6) echo 'active'; ?>"
                        href="http://localhost:8088/shop/account/purchase?type=6" title="Đã hủy" aria-role="Tab">
                        <span>Đã hủy</span>
                    </a>
                </section>
            </div>
            <?php
if($data['purchase'] == null) {
    echo '  <div class="card" style="margin-top: 10px;width: 100%; height: 450px; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <img class="img_card_none" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/orderlist/5fafbb923393b712b964.png" alt="">
                <p>Chưa có đơn hàng</p>
            </div>';
}
foreach($data['purchase'] as $item) {
    $total = 0;
    echo 
'<div class="card" style="margin-bottom: 10px;">';
    echo '      <div class="card-body" style="border-bottom: 1px solid rgba(0, 0, 0, .09);border-radius: 0 0 5px 5px;">
                    <div style="padding: 10px 24px;align-items:center; display:flex">
                        <div style="flex-grow:1;">
                            <span>Ngày đặt hàng: </span>
                            <span>'.$item['order_date'].'</span>
                        </div>
                        <div style="display: flex; align-items:center;">
                            <p style="margin:0px">
                                <span style="padding-right: 5px;">Trạng thái:</span>
                                <span id="status-'.$item['id'].'">'.$item['status'].'</span>
                            </p>';                        
    if($item['status'] == 'Chờ xử lí') {
        echo '<button class="button" id="'.$item['id'].'" onclick="deleteProduct(this)" style="border: 1px solid;margin-left:10px;background-color: #008CBA;color:white; border-radius:5px">Hủy đơn</button>';
    }
    echo               '</div>
                </div>';
    foreach($item['products'] as $d) {
        $total += $d['price']*$d['num'];
        $thumbnails = explode(',', $d['thumbnail']);
        echo 
        '<div class="new_card" style="padding: 10px 24px;align-items:center; display:flex; border-top: 1px solid rgba(0, 0, 0, .09);">
            <div style="flex-grow:1; display:flex;">
                <img src="'.$thumbnails[0].'"
                alt="" class="img-fluid" style="width:100px">
                <div style="padding: 0 0 0 12px;display:flex; align-items:center">
                    <div>
                        <p class="content_col">'.$d['title'].'</p>
                        <p class="content_col">x'.$d['num'].'</p>
                        <p>
                            <span>Đơn giá: </span>
                            <span class="amount-to-format">'.$d['price'].'</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>';
    }
    echo 
        '<div class="card-body" style="border-top: 1px solid rgba(0, 0, 0, .09);border-radius: 5px 5px 0 0;">
            <div class="float-end" style="padding-right: 24px;">
                <span style="font-size: 17px;padding-right: 5px;">Thành tiền: </span>
                <span class="amount-to-format" style="font-size: 20px;color:red">'.$total.'</span>
            </div>
        </div>
    </div>';

echo '</div>';
}
?>
        </div>
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

/* myFunction toggles between adding and removing the show class, which is used to hide and show the dropdown content */
function myFunction() {
    document.getElementById("ac_menu").classList.toggle("show");
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<script>
function deleteProduct() {
    var xoa = confirm("Bạn có muốn hủy đơn hàng không?");
    if (xoa) {
        id = event.target.id;
        document.getElementById('status-' + id).innerText = 'Đã hủy';
        event.target.remove();

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost:8088/shop/account/purchase', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(`id=${id}`);
    }
}


// Hàm để định dạng số tiền sang VND
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
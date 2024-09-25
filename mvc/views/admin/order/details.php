<style>
.card1 {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin: 10px;
    display: flex;
    align-items: center;
}

.card1-info {
    display: flex;
    width: 100%;
    justify-content: center;
}

.card1-info .box1 {
    width: 25%;
    text-align: center;
}

.card1child {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin: 10px;
    align-items: center;
}

.card1child-img {
    width: 15%;
}

.card1child-info {
    display: flex;
    width: 100%;
    justify-content: center;
}

.card1child-info .box1child {
    width: 25%;
    text-align: center;
    margin: auto;
}

.user1order {
    margin-left: 20px;
    display: flex;
}
</style>
<div class="container">
    <div class="content-container" style="margin-left:30px;">
        <div style="text-align:center;font-size: 25px;">Thông tin đơn hàng</div>
        <div style="padding:0 20px; margin-top: 20px; margin-bottom:20px">
            <p style="margin-bottom:5px;"><strong>Mã đơn hàng: </strong><?= $data['order']['order_code'] ?></p>
            <p style="margin-bottom:5px;"><strong>Người nhận: </strong><?= $data['order']['consignee_name'] ?></p>
            <p style="margin-bottom:5px;"><strong>Số điện thoại: </strong><?= $data['order']['phone_number'] ?></p>
            <p style="margin-bottom:5px;"><strong>Địa chỉ: </strong><?= $data['order']['address'] ?></p>
        </div>
        <div class="card1">
            <div style="width: 15%;"></div>
            <div class="card1-info">
                <div class="box1">Tên sản phẩm </div>
                <div class="box1">Số lượng </div>
                <div class="box1">Giá bán</div>
                <div class="box1">Thành tiền</div>
            </div>
        </div>
        <!-- 1 san pham  -->
        <?php
$totalprice = 0;
foreach($data['orderDetails'] as $item) {
    $totalprice += $item['num']*$item['price'];
    $images = explode(',',$item['thumbnail']);
    echo '  <div class="card1child">
                <div style="display: flex;">
                    <div class="card1child-img"><img src="'.url_s3.$images[0].'" alt=""
                            class="img-fluid"></div>
                    <div class="card1child-info">
                        <div class="box1child">'.$item['title'].'</div>
                        <div class="box1child">'.$item['num'].'</div>
                        <div class="box1child amount-to-format">'.$item['price'].'</div>
                        <div class="box1child amount-to-format">'.$item['num']*$item['price'].'</div>
                    </div>
                </div>
            </div>';
}
?>
        <!-- het 1 san pham  -->
        <div style="display: flex;font-size:20px;margin-bottom:20px; margin-right:20px">
            <div style="width:80%;"></div>
            <div><strong>Tổng:</strong></div>
            <div style="width:2%"></div>
            <div class="sum1">
                <span class="amount-to-format"><?= $totalprice?></span>
            </div>
        </div>
    </div>
</div>
</div>
<div class="a" style="width=100%; height:100px;"></div>
</div>
</div>

<script>
function formatToVND(amount) {
    return amount.toLocaleString('vi-VN', {
        style: 'currency',
        currency: 'VND'
    });
}
document.querySelectorAll('.amount-to-format').forEach(element => {
    const amountValue = parseFloat(element.textContent); // Lấy giá trị số tiền từ nội dung của thẻ
    element.textContent = formatToVND(
        amountValue); // Định dạng lại số tiền thành VND và cập nhật nội dung của thẻ
});
</script>
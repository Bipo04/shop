<div class="body1">
    <div class="container1">
        <div class="main1">
            <div style="display: flex;justify-content: space-between;padding-right: 30px;">
                <h4>Dashboard</h4>

                <div class="date1">
                    <input type="date" id="dateInput" max="<?php echo date('Y-m-d'); ?>"
                        value="<?php echo $data['date']; ?>">
                </div>
            </div>

            <div class="insights">
                <div class="sales">
                    <span class="material-symbols-outlined">
                        trending_up
                    </span>
                    <div class="middle">
                        <div class="left">
                            <h6 style="margin-top:8px">Số đơn hàng</h6>
                            <h4><?=$data['orderSold']['sold']?></h4>
                        </div>
                    </div>
                    <p>Trong 24 giờ</p>
                </div>
                <div class="orders">
                    <span class="material-symbols-outlined">
                        order_approve
                    </span>
                    <div class="middle">
                        <div class="left">
                            <h6 style="margin-top:8px">Doanh thu</h6>
                            <h4 class="amount-to-format"><?=$data['kq']['TotalRevenue']?></h4>
                        </div>
                    </div>
                    <p>Trong 24 giờ</p>
                </div>
                <!-- end selling -->
                <!-- selling -->
                <div class="income">
                    <span class="material-symbols-outlined">
                        stacked_line_chart
                    </span>
                    <div class="middle">
                        <div class="left">
                            <h6 style="margin-top:8px">Lợi nhuận</h6>
                            <h4 class="amount-to-format"><?=$data['kq']['Profit']?></h4>
                        </div>
                    </div>
                    <p>Trong 24 giờ</p>
                </div>
                <!-- end selling -->
            </div>
            <!-- recent order -->
            <class class="recent_order">
                <div style="display: flex;justify-content: space-between;padding-right: 30px;">
                    <h4>Đơn hàng gần đây</h4>
                    <div class="page1">
                        <button id="previousButton"><span class="material-symbols-outlined" style="font-size: 13px;">
                                arrow_back_ios
                            </span></button>
                        <span id="pageInfo"></span>
                        <button id="nextButton"><span class="material-symbols-outlined" style="font-size: 13px;">
                                arrow_forward_ios
                            </span></button>
                    </div>
                </div>
                <table id="recentOrderTable">
                    <thead>
                        <tr>
                            <th>Người đặt hàng</th>
                            <th>Giá trị</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
foreach($data['order'] as $item) {
    echo '  <tr>
                <td>'.$item['consignee_name'].'</td>
                <td class="amount-to-format">'.$item['total_money'].'</td>
                <td class="dd_time">'.$item['order_date'].'</td>
                <td>'.$item['status'].'</td>
                <td class="text-primary"><a href="http://localhost:8088/web/admin/order/detail?id='.$item['id'].'">Chi tiết</a></td>
            </tr>';
}
?>
                    </tbody>
                </table>
            </class>
            <!-- recent order -->
        </div>
        <div class="ThongKe">
            <h3>Đơn hàng theo ngày</h3>
            <div class="card1">
                <div class="card1-body">
                    <canvas id="pieChart" data-status="[<?php $value = array_values($data['statusShow']);
                    $kq = implode(',', $value); echo $kq ?>]"
                        data-labels='["Chờ xử lí", "Đang chuẩn bị", "Đang giao hàng", "Đã giao hàng"]' width="auto"
                        height="310">
                    </canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="a" style="width=100%; height:100px;"></div>
</div>
</div>
</div>
</div>

<script src="<?=_WEB_ROOT?>/public/admin/js/dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.getElementById('dateInput').addEventListener('change', function() {
    const newDate = this.value;
    console.log('Ngày đã chọn: ' + newDate);

    const xhr = new XMLHttpRequest();

    const currentUrl = window.location.href;
    const url = currentUrl.split('?')[0];
    window.location.href = `${url}?date=${newDate}`;

    xhr.open('POST', 'http://localhost:8088/web/admin/dashboard', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`date=${encodeURIComponent(newDate)}`);
})

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
document.querySelectorAll('.amount-to-format').forEach(element => {
    if (element.innerHTML !== '') {
        const amountValue = parseFloat(element.textContent);
        element.textContent = formatToVND(
            amountValue);
    }
});

function removeMilliseconds(dateTimeStr) {
    // Tách chuỗi thành ngày và thời gian
    let parts = dateTimeStr.split(' ');

    // Lấy phần ngày và phần thời gian
    let datePart = parts[0];
    let timePart = parts[1];

    // Tách phần thời gian để loại bỏ ".000"
    let timeParts = timePart.split('.');
    let timeWithoutMs = timeParts[0];

    // Kết hợp lại thành định dạng mới
    let formattedDateTime = datePart + ' ' + timeWithoutMs;

    return formattedDateTime;
}

document.querySelectorAll(".dd_time").forEach((value, index) => {
    value.textContent = removeMilliseconds(value.textContent);
    console.log(value);
})
</script>
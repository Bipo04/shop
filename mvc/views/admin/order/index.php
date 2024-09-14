<style>
.navbar {
    display: flex;
    justify-content: space-around;
}

.nav-button {
    background: none;
    border: none;
    color: #007bff;
    font-size: 18px;
    cursor: pointer;
    padding: 10px 20px;
}

.nav-button.active {
    font-weight: bold;
    color: #0056b3;
}
</style>

<div class="container">
    <h1 class="h3 mb-2 text-gray-800 text-center"><?php echo $data['title'] ?></h1>
    <div class="card shadow mb-4">
        <input type="text" id="search" placeholder="Tìm kiếm đơn hàng" oninput="searchOrder()"
            style="border:none;padding:10px">
    </div>
    <div class="card shadow mb-4">
        <!-- Navbar -->
        <div class="navbar">
            <button class="nav-button <?php if($data['typ'] == '1') echo 'active';?>" id="1">Tất cả</button>
            <button class="nav-button <?php if($data['typ'] == '2') echo 'active';?>" id="2">Chờ xử lí</button>
            <button class="nav-button <?php if($data['typ'] == '3') echo 'active';?>" id="3">Đang chuẩn bị</button>
            <button class="nav-button <?php if($data['typ'] == '4') echo 'active';?>" id="4">Đang giao hàng</button>
            <button class="nav-button <?php if($data['typ'] == '5') echo 'active';?>" id="5">Đã giao hàng</button>
            <button class="nav-button <?php if($data['typ'] == '6') echo 'active';?>" id="6">Đã hủy</button>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Người nhận</th>
                            <th>Số điện thoại </th>
                            <th>Giá trị</th>
                            <th>Thời gian</th>
                            <th width=190px>Trạng thái</th>
                            <th style="width: 160px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px;">
                        <?php
foreach($data['orders'] as $item) {
    echo '              <tr id="'.$item['id'].'">
                            <td>'.$item['order_code'].'</td>
                            <td>'.$item['consignee_name'].'</td>
                            <td>'.$item['phone_number'].'</td>
                            <td class="amount-to-format">'.$item['total_money'].'</td>
                            <td class="dd_time">'.$item['order_date'].'</td>
                            <td class="order-status">'.$item['status'].'</td>
                            <td>
                                <button class="btn btn-outline-primary" onclick="editOrder(this)"
                                    style="font-size: 15px;">Sửa</button>
                                <a href="http://localhost:8088/shop/admin/order/detail?id='.$item['id'].'">
                                <button class="btn btn-outline-dark"
                                    style="font-size: 15px">Chi tiết</button></a>
                            </td>
                        </tr>';
}
?>
                        <!-- Thêm các hàng khác nếu cần -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<div class="a" style="width=100%; height:100px;"></div>
</div>
</div>

<!-- <script src="<?php echo _WEB_ROOT ?>/public/admin/js/order.js"></script> -->

<script>
function editOrder(button) {
    const orderRow = button.closest('tr');
    const statusCell = orderRow.querySelector('.order-status');
    const editButton = button;
    if (editButton.textContent === "Sửa") {
        // Lưu trạng thái hiện tại
        const currentStatus = statusCell.textContent;
        // Tạo dropdown
        statusCell.innerHTML = `
            <select class="form-control">
                <option ${currentStatus === 'Chờ xử lí' ? 'selected' : ''}>Chờ xử lí</option>
                <option ${currentStatus === 'Đang chuẩn bị' ? 'selected' : ''}>Đang chuẩn bị</option>
                <option ${currentStatus === 'Đang giao hàng' ? 'selected' : ''}>Đang giao hàng</option>
                <option ${currentStatus === 'Đã giao hàng' ? 'selected' : ''}>Đã giao hàng</option>
                <option ${currentStatus === 'Đã hủy' ? 'selected' : ''}>Đã hủy</option>
            </select>
        `;
        // Đổi nút thành "Lưu"
        editButton.textContent = "Lưu";
        editButton.classList.remove('btn-outline-primary');
        editButton.classList.add('btn-outline-success');
    } else {
        // Lấy trạng thái mới từ dropdown
        const newStatus = statusCell.querySelector('select').value;
        const id = orderRow.id;
        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Cập nhật trạng thái trong bảng
                statusCell.textContent = newStatus;
                // Đổi nút lại thành "Chỉnh sửa"
                editButton.textContent = "Sửa";
                editButton.classList.remove('btn-outline-success');
                editButton.classList.add('btn-outline-primary');
            }
        };

        xhr.open('POST', 'http://localhost:8088/web/admin/order/update', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(`id=${id}&status=${newStatus}`);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll('.nav-button');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const currentUrl = window.location.href;
            const url = currentUrl.split('?')[0];
            window.location.href = `${url}?type=${this.id}`;
        });
    });
});

function searchOrder() {
    let searchValue = document.getElementById("search").value.trim().toLowerCase();
    let rows = document.querySelectorAll("#productTable tbody tr");
    rows.forEach(row => {
        let orderId = row.querySelector("td:first-child").textContent.trim().toLowerCase();
        if (orderId.includes(searchValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

function formatToVND(amount) {
    return amount.toLocaleString('vi-VN', {
        style: 'currency',
        currency: 'VND'
    });
}
document.querySelectorAll('.amount-to-format').forEach(element => {
    const amountValue = parseFloat(element.textContent);
    element.textContent = formatToVND(
        amountValue); 
});

function removeMilliseconds(dateTimeStr) {
    let parts = dateTimeStr.split(' ');
    let datePart = parts[0];
    let timePart = parts[1];
    let timeParts = timePart.split('.');
    let timeWithoutMs = timeParts[0];
    let formattedDateTime = datePart + ' ' + timeWithoutMs;

    return formattedDateTime;
}

document.querySelectorAll(".dd_time").forEach((value, index) => {
    value.textContent = removeMilliseconds(value.textContent);
})
</script>
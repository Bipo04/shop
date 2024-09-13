<style>
label {
    font-size: 16px;
    color: #333;
    margin-right: 10px;
}

.form-select {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 5px 7px;
    font-size: 16px;
    color: #333;
    cursor: pointer;
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 150px;
}
</style>
<div class="container">
    <label for="dateType">Xem theo:</label>
    <select class="form-select" id="dateType">
        <option value="day" <?php if($data['kind'] == 'day') echo 'selected'; ?>>Ngày</option>
        <option value="month" <?php if($data['kind'] == 'month') echo 'selected'; ?>>Tháng</option>
        <option value="year" <?php if($data['kind'] == 'year') echo 'selected'; ?>>Năm</option>
    </select>
    <div class="select" style="margin-top:10px; margin-bottom:10px">
        <?php
if($data['kind'] == 'day') {
    echo '  <div class="row align-items-center">
                <div class="col-md-3">
                    <label for="dateType">Ngày đầu:</label>
                    <div class="date-start">
                        <input type="date" value="'.$data['a']['start'].'" max="'.$data['a']['end'].'">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="dateType">Ngày cuối:</label>
                    <div class="date-end">
                        <input type="date" value="'.$data['a']['end'].'" min="'.$data['a']['start'].'">
                    </div>
                </div>
            </div>';
}
if($data['kind'] == 'month') {
    echo '  <label for="monthSelect">Chọn tháng:</label>
            <select class="form-select" id="monthSelect">';
    for($i = $data['a']['currentMonth']; $i >0; $i--) {
        if($i == $data['a']['month']) {
            echo '<option value="'.$i.'" selected>'.$i.'/2024</option>';
        } else {
            echo '<option value="'.$i.'">'.$i.'/2024</option>';
        }
    }
    echo '</select>';
}
if($data['kind'] == 'year') {
    echo '  <label for="yearSelect">Chọn năm:</label>
            <select class="form-select" id="yearSelect">';
    for($i = 0; $i < 5; $i++) {
        $a = $data['a']['currentYear'] - $i;
        if($a == $data['a']['year']) {
            echo '<option value="'.$a.'" selected>'.$a.'</option>';
        } else {
        echo '<option value="'.$a.'">'.$a.'</option>';
        }
    }
    echo '</select>';
}
?>
    </div>
    <h1 class="h3 mb-2 text-gray-800 text-center">Bảng tổng hợp</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="350px">Tổng số lượng sản phẩm bán ra</th>
                            <th>Tổng doanh thu</th>
                            <th>Lợi nhuận</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=$data['tongquat']['Sold']?></td>
                            <td class="amount-to-format"><?=$data['tongquat']['TotalRevenue']?></td>
                            <td class="amount-to-format"><?=$data['tongquat']['Profit']?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <h1 class="h3 mb-2 text-gray-800 text-center">Thống kê theo sản phẩm</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá nhập</th>
                            <th>Số lượng bán</th>
                            <th>Doanh thu</th>
                            <th>Lợi nhuận</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
foreach($data['revenue'] as $item) {
    echo '              <tr>
                            <td>'.$item['Id'].'</td>
                            <td>'.$item['Name'].'</td>
                            <td class="costPrice amount-to-format">'.$item['Inbound_price'].'</td>
                            <td class="quantitySold">'.$item['sold'].'</td>
                            <td class="revenue amount-to-format">'.$item['Revenue'].'</td>
                            <td class="profit amount-to-format">'.$item['Profit'].'</td>
                        </tr>';
}
?>
                        <!-- Thêm các dòng khác tương tự cho các sản phẩm khác -->
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const today = new Date().toISOString().split('T')[0];
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1; // Tháng hiện tại

    function updateURLParams() {
        const selectElement = document.getElementById('dateType');
        const type = selectElement.value;
        const currentUrl = window.location.href;
        const url = currentUrl.split('?')[0];

        let params = '';
        if (type === 'day') {
            const start = document.querySelector('.date-start input');
            const end = document.querySelector('.date-end input');
            params = `type=day&date-start=${today}&date-end=${today}`;
            if (dateStart !== null && dateEnd !== null) {
                params = `type=day&date-start=${start.value}&date-end=${end.value}`;
            }
        } else if (type === 'month') {
            const monthSelect = document.getElementById('monthSelect');
            var selectedMonth = currentMonth;
            if (monthSelect !== null) {
                selectedMonth = monthSelect.value;
            }
            params = `type=month&month=${selectedMonth}&year=${currentYear}`;
        } else if (type === 'year') {
            const yearSelect = document.getElementById('yearSelect');
            var selectedYear = currentYear;
            if (yearSelect !== null) {
                selectedYear = yearSelect.value;
            }
            params = `type=year&year=${selectedYear}`;
        }

        const newUrl = `${url}?${params}`;

        // Kiểm tra nếu URL mới khác với URL hiện tại thì mới chuyển hướng
        if (newUrl !== currentUrl) {
            window.location.href = newUrl;
        }
    }

    const selectElement = document.getElementById('dateType');

    selectElement.addEventListener('change', function() {
        console.log(this.value);
        updateURLParams();
    });

    const dateStart = document.querySelector('.date-start input');
    const dateEnd = document.querySelector('.date-end input');
    if (dateStart !== null) {
        dateStart.addEventListener('change', function() {
            updateURLParams();
        });
    }
    if (dateEnd !== null) {
        dateEnd.max = today;
        dateEnd.addEventListener('change', function() {
            updateURLParams();
        });
    }
    const monthSelect = document.getElementById('monthSelect');
    if (monthSelect !== null) {
        monthSelect.addEventListener('change', function() {
            updateURLParams();
        });
    }
    const yearSelect = document.getElementById('yearSelect');
    if (yearSelect !== null) {
        yearSelect.addEventListener('change', function() {
            updateURLParams();
        });
    }
});

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
</script>
<style>
:root {
    --clr-primary: #7380ec;
    --clr-danger: #ff7782;
    --clr-success: #41f1b6;
    --clr-white: #fff;
    --clr-info-dark: #7d8da1;
    --clr-info-light: #dce1eb;
    --clr-dark: #363949;
    --clr-warnig: #ff4edc;
    --clr-light: rgba(132, 139, 200, 0.18);
    --clr-primary-variant: #111e88;
    --clr-dark-variant: #677483;
    --clr-color-background: #f6f6f9;

    --card-border-radius: 2rem;
    --border-radius-1: 0.4rem;
    --border-radius-2: 0.8rem;
    --border-radius-3: 1.2rem;

    --card-padding: 1.8rem;
    --padding-1: 1.2rem;
    --box-shadow: 0rem 0rem 3rem var(--clr-light);
}

.container1 {
    display: grid;
    width: 98%;
    gap: 1.8rem;
    grid-template-columns: auto;
    margin: 0 auto;
}

.main1 {
    width: auto;
}

.content-table,
.tableTSP {
    border-collapse: collapse;
    margin: 25px 0 0 0;
    font-size: 0.9em;
    width: 100%;
    border-radius: 5px 5px 0 0;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    font-size: 16px;
}

.content-table thead tr {
    background-color: #fecedc;
    text-align: left;
    font-weight: bold;
}

.content-table th,
.content-table td {
    padding: 12px 15px;
}

.content-table tr .img-tb1-name {
    min-width: 350px;
}

.content-table tbody tr {
    border-bottom: 1px solid #ddd;
}

.content-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.TopSellingProduct {
    margin-left: 5px;
    transition: all .3s ease;
    color: #5a5c69;
}

.TopSellingProduct {
    padding: auto;
}


.imgTSP {
    width: 80px;
}

.page1 {
    font-size: 16px;
    display: flex;
}

.page1 button {
    background-color: var(--clr-white);
    width: 100%;
    border-radius: var(--card-border-radius);
    text-align: center;
    box-shadow: var(--box-shadow);
    transition: all .3s ease;
    color: var(--clr-dark);
    width: 25px;
    height: 24.7px;
    border: 0.2px;
}

.hidden {
    display: none;
}

.table1 {
    color: #5a5c69;
}
</style>
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<div class="container">
    <div class="container1">
        <div class="main1">
            <div class="table1">
                <!-- Thống kê sản phẩm  -->
                <h1 class="h3 mb-2 text-gray-800 text-center">Thống kê sản phẩm</h1>
                <div style="display:flex;justify-content:space-between">
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
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$index = 1;
foreach($data['kq'] as  $item) {
    $image = explode(',', $item['thumbnail']);
    echo '              <tr>
                            <td>'.$index++.'</td>
                            <td><img class="imgTSP" src="'.url_s3.$image[0].'" alt="">
                            </td>
                            <td class="img-tb1-name">'.$item['Name'].'</td>
                            <td>'.$item['sold'].'</td>
                        </tr>';
}
?>
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
// Table 1
document.addEventListener("DOMContentLoaded", function() {
    var tableRows = document.querySelectorAll(".content-table tbody tr");
    var maxRows = 10; //So hang
    var currentPage = 1;

    function showRows(page) {
        var start = (page - 1) * maxRows;
        var end = start + maxRows;

        tableRows.forEach(function(row, index) {
            if (index >= start && index < end) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });

        var totalRows = tableRows.length;
        var totalPages = Math.ceil(totalRows / maxRows);

        var pageInfo = document.getElementById("pageInfo");
        if (totalPages >= 1) {
            pageInfo.textContent = "Trang " + page + "/" + totalPages;
            pageInfo.style.display = "inline"; // Hiển thị thông tin trang
        } else {
            pageInfo.style.display = "none"; // Ẩn thông tin trang nếu chỉ có một trang
        }

        var previousButton = document.getElementById("previousButton");
        var nextButton = document.getElementById("nextButton");
    }

    showRows(currentPage);

    document.getElementById("previousButton").addEventListener("click", function() {
        if (currentPage > 1) {
            currentPage--;
            showRows(currentPage);
        }
    });

    document.getElementById("nextButton").addEventListener("click", function() {
        var totalRows = tableRows.length;
        var totalPages = Math.ceil(totalRows / maxRows);

        if (currentPage < totalPages) {
            currentPage++;
            showRows(currentPage);
        }
    });
});
// Danh so va chuyen trang cho table2
document.addEventListener("DOMContentLoaded", function() {
    var tableRows = document.querySelectorAll(".TopSellingProduct tbody tr");
    var maxRows = 5;
    var currentPage = 1;

    function showRows(page) {
        var start = (page - 1) * maxRows;
        var end = start + maxRows;

        tableRows.forEach(function(row, index) {
            if (index >= start && index < end) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });

        var totalRows = tableRows.length;
        var totalPages = Math.ceil(totalRows / maxRows);

        var pageInfo = document.getElementById("pageInfo1");
        if (totalPages >= 1) {
            pageInfo.textContent = "Trang " + page + "/" + totalPages;
            pageInfo.style.display = "inline"; // Hiển thị thông tin trang
        } else {
            pageInfo.style.display = "none"; // Ẩn thông tin trang nếu chỉ có một trang
        }

        var previousButton = document.getElementById("previousButton1");
        var nextButton = document.getElementById("nextButton1");
    }

    showRows(currentPage);

    document.getElementById("previousButton1").addEventListener("click", function() {
        if (currentPage > 1) {
            currentPage--;
            showRows(currentPage);
        }
    });

    document.getElementById("nextButton1").addEventListener("click", function() {
        var totalRows = tableRows.length;
        var totalPages = Math.ceil(totalRows / maxRows);

        if (currentPage < totalPages) {
            currentPage++;
            showRows(currentPage);
        }
    });
});
</script>
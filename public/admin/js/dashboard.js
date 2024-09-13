document.addEventListener("DOMContentLoaded", function () {
    var tableRows = document.querySelectorAll(".recent_order tbody tr");
    var maxRows = 4;
    var currentPage = 1;

    function showRows(page) {
        var start = (page - 1) * maxRows;
        var end = start + maxRows;

        tableRows.forEach(function (row, index) {
            if (index >= start && index < end) {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        });

        var totalRows = tableRows.length;
        var totalPages = Math.ceil(totalRows / maxRows);

        var pageInfo = document.getElementById("pageInfo");
        if (totalPages > 1) {
            pageInfo.textContent = "Trang " + page + "/" + totalPages;
            pageInfo.style.display = "inline"; // Hiển thị thông tin trang
        } else {
            pageInfo.style.display = "none"; // Ẩn thông tin trang nếu chỉ có một trang
        }

        var previousButton = document.getElementById("previousButton");
        var nextButton = document.getElementById("nextButton");

        if (currentPage === 1) {
            previousButton.style.display = "none"; // Ẩn nút "Trang trước" khi ở trang đầu tiên
        } else {
            previousButton.style.display = "inline";
        }

        if (currentPage === totalPages || totalPages === 0) {
            nextButton.style.display = "none"; // Ẩn nút "Trang sau" khi ở trang cuối cùng hoặc không có trang nào
        } else {
            nextButton.style.display = "inline";
        }
    }

    showRows(currentPage);

    document.getElementById("previousButton").addEventListener("click", function () {
        if (currentPage > 1) {
            currentPage--;
            showRows(currentPage);
        }
    });

    document.getElementById("nextButton").addEventListener("click", function () {
        var totalRows = tableRows.length;
        var totalPages = Math.ceil(totalRows / maxRows);

        if (currentPage < totalPages) {
            currentPage++;
            showRows(currentPage);
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Pie Chart for Order Statuses
    var pieChartElem = document.getElementById('pieChart'); // Lấy phần tử canvas với id 'pieChart'
    var pieLabels = JSON.parse(pieChartElem.getAttribute('data-labels')); // Lấy nhãn từ dữ liệu JSON trong thuộc tính 'data-labels' và chuyển đổi thành mảng
    var pieData = JSON.parse(pieChartElem.getAttribute('data-status')); // Lấy dữ liệu từ JSON trong thuộc tính 'data-status' và chuyển đổi thành mảng

    var ctxPie = pieChartElem.getContext('2d'); // Lấy ngữ cảnh vẽ 2D cho canvas

    // Màu nền của các phần của biểu đồ
    var backgroundColorArray = ['coral', '#7380ec', 'rgba(104, 255, 86, 0.75)']; // Màu coral, #7380ec, và xanh lá cây tươi nhạt hơn

    // Màu border của các phần của biểu đồ
    var borderColorArray = ['rgba(255, 127, 80, 0.4)', 'rgba(115, 128, 236, 0.4)', 'rgba(104, 205, 86, 0.4)']; // Màu border tương ứng nhạt hơn

    // Tạo biểu đồ với các dữ liệu và border đã chỉ định
    var pieChart = new Chart(ctxPie, { // Khởi tạo biểu đồ pie mới
        type: 'pie', // Loại biểu đồ là pie
        data: {
            labels: pieLabels, // Nhãn cho các phần của biểu đồ
            datasets: [{
                data: pieData, // Dữ liệu cho các phần của biểu đồ
                backgroundColor: backgroundColorArray, // Màu nền của các phần
                borderColor: borderColorArray, // Màu viền của các phần
                borderWidth: 1 // Độ rộng của viền
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top', // Vị trí của chú thích
                },
                tooltip: {
                    callbacks: {
                        label: function (context) { // Hàm gọi lại để hiển thị nhãn của tooltip
                            var label = context.label || ''; // Nhãn của phần đó, mặc định là rỗng
                            if (label) {
                                label += ': '; // Nếu có nhãn, thêm dấu hai chấm và khoảng trắng
                            }
                            if (context.parsed !== null) { // Kiểm tra xem dữ liệu đã được phân tích hay không
                                label += context.parsed + ' orders'; // Thêm số lượng đơn hàng và chữ 'orders' vào nhãn
                            }
                            return label; // Trả về nhãn đã được định dạng
                        }
                    }
                }
            }
        }
    });
    var combinedChartElem = document.getElementById('combinedChart');
    var ctxCombined = combinedChartElem.getContext('2d');
    var combinedChart = new Chart(ctxCombined, {
        type: 'bar',
        data: {
            labels: JSON.parse(combinedChartElem.getAttribute('data-daily-labels')),
            datasets: [{
                label: 'Doanh thu',
                type: 'line',
                data: JSON.parse(combinedChartElem.getAttribute('data-daily-sale')),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

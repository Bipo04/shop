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

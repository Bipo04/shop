<div class="container">
    <h1 class="h3 mb-2 text-gray-800 text-center"><?php echo $data['title'] ?></h1>
    <div class="card shadow mb-4">
        <input type="text" id="search" placeholder="Tìm kiếm người dùng" oninput="searchOrder()"
            style="border:none;padding:10px">
    </div>
    <!-- <a href="http://localhost:8088/web/admin/user/add"><button class="btn btn-primary" style="margin-bottom: 15px">Thêm
            tài khoản</button></a> -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ và tên</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Ngày tạo</th>
                            <th colspan="2">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    $index = 1;
                    if ($data['users']) {
                        foreach ($data['users'] as $item) {
                            $kq = '<tr id="'.$item['id'].'">
                                        <td>' . $index . '</td>
                                        <td class="name">' . $item['fullname'] . '</td>
                                        <td>' . $item['username'] . '</td>
                                        <td>' . $item['phone_number'] . '</td>
                                        <td>' . $item['email'] . '</td>
                                        <td>' . ucfirst($item['name']) . '</td>
                                        <td class="dd_time">' . $item['created_at'] . '</td>
                                        <td>
                                        <button class="btn btn-outline-primary"
                                            onclick="editUser(this)">Sửa</button>
                                        </td>
                                        <td>
                                        <button class="btn btn-outline-danger"
                                            onclick="deleteUser(this)">Xóa</button>
                                        </td>
                                    </tr>';
                            echo $kq;
                            $index++;
                        }
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
function editUser(btn) {
    const row = btn.parentNode.parentNode;
    const roleCell = row.cells[5]; // Cột vai trò của người dùng
    const currentRole = roleCell.innerText.trim(); // Lấy vai trò hiện tại của người dùng

    const UserId = row.id;

    var id = document.cookie.split(';');
    id = id[1].split('_');
    console.log(id[1]);

    if (id[1] === UserId) {
        alert('Bạn không thể sửa role của chính mình');
        return;
    }
    // Tạo một dropdown để chọn vai trò mới
    const selectRole = document.createElement('select');
    selectRole.classList.add('form-select'); // Thêm lớp để xác định kích thước chữ

    // Tạo các tùy chọn cho dropdown (user và admin)
    const optionUser = document.createElement('option');
    optionUser.value = 'User';
    optionUser.text = 'User';
    const optionAdmin = document.createElement('option');
    optionAdmin.value = 'Admin';
    optionAdmin.text = 'Admin';

    // Chọn tùy chọn hiện tại dựa trên vai trò của người dùng
    if (currentRole === 'User') {
        optionUser.selected = true;
    } else if (currentRole === 'Admin') {
        optionAdmin.selected = true;
    }

    // Thêm các tùy chọn vào dropdown
    selectRole.add(optionUser);
    selectRole.add(optionAdmin);

    // Xóa nội dung hiện tại của ô vai trò
    roleCell.innerHTML = '';

    // Thêm dropdown vào ô vai trò
    roleCell.appendChild(selectRole);

    // Thay đổi nút chỉnh sửa thành "Xác nhận"
    const editButton = row.querySelector('.btn-outline-primary');
    editButton.innerText = 'OK';
    editButton.classList.remove('btn-outline-primary');
    editButton.classList.add('btn-outline-success');
    editButton.setAttribute('onclick', 'confirmEdit(this)');
}

function confirmEdit(btn) {
    const row = btn.parentNode.parentNode;
    const roleCell = row.cells[5]; // Cột vai trò của người dùng
    const selectRole = roleCell.querySelector('select');

    // Lấy vai trò mới từ dropdown
    const newRole = selectRole.value;
    const UserId = row.id;

    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Cập nhật vai trò của người dùng
            roleCell.innerText = newRole;
            // Thay đổi nút "Xác nhận" thành "Sửa" và gán lại hàm chỉnh sửa
            btn.innerText = 'Sửa';
            btn.setAttribute('onclick', 'editUser(this)');
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-outline-primary');
        }
    };

    xhr.open('POST', 'http://localhost:8088/web/admin/user/update', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`id=${UserId}&role=${newRole}`);
}


function deleteUser(btn) {
    const xoa = confirm("Bạn có chắc muốn xóa không?");
    if (xoa) {
        const row = btn.parentNode.parentNode;
        const table = row.parentNode;

        // Lấy ID của sản phẩm sẽ bị xóa
        const deletedUserId = row.id;

        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Xóa hàng
                table.removeChild(row);

                // Cập nhật ID của các sản phẩm dưới
                const rows = table.rows;
                for (const i = 0; i < rows.length; i++) {
                    const currentProductId = parseInt(rows[i].cells[0].innerText);
                    if (currentProductId > deletedUserId) {
                        rows[i].cells[0].innerText = currentProductId - 1;
                    }
                }
            }
        };

        xhr.open('POST', 'http://localhost:8088/web/admin/user/delete', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(`id=${deletedUserId}`); // Corrected the variable name
    }
}

function searchOrder() {
    let searchValue = document.getElementById("search").value.trim().toLowerCase();
    let rows = document.querySelectorAll("#productTable tbody tr");
    rows.forEach(row => {
        let orderId = row.querySelector(".name").textContent.trim().toLowerCase();
        if (orderId.includes(searchValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

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
})
</script>
<div class="container">
    <h1 class="h3 mb-2 text-gray-800 text-center"><?php echo $data['title'] ?></h1>
    <div class="card shadow mb-4">
        <input type="text" id="search" placeholder="Tìm kiếm người dùng" oninput="searchOrder()"
            style="border:none;padding:10px">
    </div>
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
    const roleCell = row.cells[5];
    const currentRole = roleCell.innerText.trim(); 

    const selectRole = document.createElement('select');
    selectRole.classList.add('form-select'); 

    const optionUser = document.createElement('option');
    optionUser.value = 'User';
    optionUser.text = 'User';
    const optionAdmin = document.createElement('option');
    optionAdmin.value = 'Admin';
    optionAdmin.text = 'Admin';

    if (currentRole === 'User') {
        optionUser.selected = true;
    } else if (currentRole === 'Admin') {
        optionAdmin.selected = true;
    }

    selectRole.add(optionUser);
    selectRole.add(optionAdmin);

    roleCell.innerHTML = '';

    roleCell.appendChild(selectRole);

    const editButton = row.querySelector('.btn-outline-primary');
    editButton.innerText = 'OK';
    editButton.classList.remove('btn-outline-primary');
    editButton.classList.add('btn-outline-success');
    editButton.setAttribute('onclick', 'confirmEdit(this)');
}

function confirmEdit(btn) {
    const row = btn.parentNode.parentNode;
    const roleCell = row.cells[5];
    const selectRole = roleCell.querySelector('select');
    const newRole = selectRole.value;
    const UserId = row.id;

    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            roleCell.innerText = newRole;
            btn.innerText = 'Sửa';
            btn.setAttribute('onclick', 'editUser(this)');
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-outline-primary');
        }
    };

    xhr.open('POST', 'http://localhost:8088/shop/admin/user/update', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`id=${UserId}&role=${newRole}`);
}


function deleteUser(btn) {
    const xoa = confirm("Bạn có chắc muốn xóa không?");
    if (xoa) {
        const row = btn.parentNode.parentNode;
        const table = row.parentNode;
        const deletedUserId = row.id;

        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                table.removeChild(row);
                const rows = table.rows;
                for (const i = 0; i < rows.length; i++) {
                    const currentProductId = parseInt(rows[i].cells[0].innerText);
                    if (currentProductId > deletedUserId) {
                        rows[i].cells[0].innerText = currentProductId - 1;
                    }
                }
            }
        };

        xhr.open('POST', 'http://localhost:8088/shop/admin/user/delete', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(`id=${deletedUserId}`);
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
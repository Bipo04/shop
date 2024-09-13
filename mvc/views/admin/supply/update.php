<div class="container" style="width:60%">
    <h1 class="h3 mb-2 text-gray-800 text-center"><?php echo $data['title'] ?></h1>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?= $data['supply']['id'] ?>" class="form-control">
        <div class="form-group mb-3">
            <label for="">Tên</label>
            <input type="text" name="name" value="<?= $data['supply']['name'] ?>" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="gender">Địa chỉ</label>
            <input type="text" name="address" value="<?= $data['supply']['address'] ?>" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="gender">Email</label>
            <input type="text" name="email" value="<?= $data['supply']['email'] ?>" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="gender">Số điện thoại</label>
            <input type="text" name="phone" value="<?= $data['supply']['phone'] ?>" class="form-control">
        </div>
        <div class="form-group mb-3">
            <input type="submit" name="btn" class="btn" style="background-color:#fecedc; color:black" value="Cập nhật">
        </div>
    </form>
</div>
</div>
<div class="a" style="width=100%; height:100px;"></div>
</div>
</div>
<div class="container" style="width:60%">
    <div class="panel-heading" style="margin-top: 10px">
        <h2 class="text-center"><?php echo $data['title'] ?></h2>
    </div>
    <form action="" method="post">
        <div class="form-group mb-3">
            <label for="">Tên</label>
            <input type="text" name="name" class="form-control" value="<?php echo $data['category'][0]['name'] ?>">
        </div>
        <div class="form-group mb-3">
            <label for="gender">Giới tính</label>
            <select name="gender" class="form-control">
                <option value="boy" <?php if($data['category'][0]['gender']=='boy' ) echo 'selected' ?>>Nam</option>
                <option value="girl" <?php if($data['category'][0]['gender']=='girl' ) echo 'selected' ?>>Nữ
                </option>
            </select>
        </div>
        <div class="form-group mb-3">
            <input type="hidden" name="id" class="form-control" value="<?php echo $data['category'][0]['id']; ?>">
            <input type="submit" name="btn" class="btn" style="background-color:#fecedc; color:black" value="Cập nhật">
        </div>
    </form>
</div>
</div>
<div class="a" style="width=100%; height:100px;"></div>
</div>
</div>
<style>
textarea {
    height: 100px;
    width: 100%;
    font-family: Nunito, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
}

.Image>div>div {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
}

.Image>div>div>img {
    width: 100%;
    height: auto;
    padding: auto;
}
</style>
<div class="container" style="width:60%">
    <h1 class="h3 mb-2 text-gray-800 text-center"><?php echo $data['title'] ?></h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="">Tên</label>
            <input type="text" name="title" class="form-control" value="<?=$data['product']['title'] ?>">
        </div>
        <div class="form-group mb-3">
            <label for="">Danh mục</label>
            <input type="text" name="category_name" class="form-control" value="<?=$data['product']['category_name'] ?>"
                readonly>
        </div>
        <div class="form-group mb-3">
            <label for="">Giới tính</label>
            <input type="text" name="gender" class="form-control" value="<?php if($data['product']['gender'] == 'boy') echo 'Nam';
                    else echo 'Nữ';?>" readonly>
        </div>
        <div class="form-group mb-3">
            <label for="">Nhà cung cấp</label>
            <input type="text" name="supply" class="form-control" value="<?=$data['product']['supply']?>" readonly>
        </div>
        <div class="form-group mb-3">
            <label for="">Giá nhập</label>
            <input type="text" name="inbound_price" class="form-control"
                value="<?=$data['product']['inbound_price'] ?>">
        </div>
        <div class="form-group mb-3">
            <label for="">Giá bán</label>
            <input type="text" name="outbound_price" class="form-control"
                value="<?=$data['product']['outbound_price'] ?>">
        </div>
        <div class="form-group mb-3">
            <label for="">Giá discount</label>
            <input type="text" name="discount" class="form-control" value="<?=$data['product']['discount'] ?>">
        </div>
        <div class="form-group mb-3">
            <label for="">Số lượng</label>
            <input type="text" name="quantity" class="form-control" value="<?=$data['product']['quantity'] ?>">
        </div>
        <div class="form-group mb-3">
            <label for="">Mô tả</label>
            <div><textarea name="description" id=""
                    style="height=100%; weight=100%; color: #6e707e;border: 1px solid #d1d3e2; border-radius: .35rem;"><?=$data['product']['description'] ?></textarea>
            </div>
        </div>
        <input type="hidden" name="old_thumbnail" class="form-control"
                value="<?=$data['product']['thumbnail'] ?>">
        <div class="form-group mb-3 Image">
            <div class="mb-3" id="CurrentImages">
                <label for="">Ảnh hiện tại</label>
                <div>
                    <?php
$images = explode(',',$data['product']['thumbnail']);
foreach($images as $item) {
    echo '<img src="'.$item.'" alt="">';
}
        ?>
                </div>
            </div>
            <div class="form-group mb-3" id="NewImages">
                <label for="">Ảnh mới</label>
                <input type="file" class="form-control" id="imageUpload" name="img[]" multiple
                    onchange="previewImages(event)">
                <div class="preview-container" id="previewContainer"></div>
            </div>
        </div>
        <input type="hidden" name="id" value="<?=$data['id'] ?>">
        <div class="form-group mb-3">
            <input type="submit" name="btn" class="btn" style="background-color:#fecedc; color:black" value="Cập nhật">
        </div>
    </form>
</div>
</div>
<div class="a" style="width=100%; height:100px;"></div>
</div>
</div>

<script>
// Lấy vị trí hiển thị ảnh là thẻ div previewImagé
function previewImages(event) {
    var previewContainer = document.getElementById('previewImages');
    var files = event.target.files;
    // Vòng lặp thêm ảnh và các thẻ liên quan
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();
        reader.onload = function(e) {
            // tạo thẻ div có class image-container
            var imageContainer = document.createElement('div');
            imageContainer.classList.add('image-container');
            // tạo img
            var image = document.createElement('img');
            image.src = e.target.result;
            image.alt = file.name;
            // tạo button
            var closeButton = document.createElement('button');
            closeButton.classList.add('close-button');
            closeButton.innerHTML = 'x';
            // Thêm hàm cho btn
            closeButton.addEventListener('click', deleteImage);
            //thêm các thẻ
            imageContainer.appendChild(image);
            imageContainer.appendChild(closeButton);
            previewContainer.appendChild(imageContainer);
        };
        reader.readAsDataURL(file);
    }
}

function previewImages(event) {
    const imageFiles = event.target.files;
    const previewImagesContainer = document.getElementById('previewContainer');
    previewImagesContainer.innerHTML = ''; // Xóa các hình ảnh hiện tại
    for (const file of imageFiles) {
        const imageElement = document.createElement('img');
        imageElement.src = URL.createObjectURL(file);
        previewImagesContainer.appendChild(imageElement);
    }
}
</script>
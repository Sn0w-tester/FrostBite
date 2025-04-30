<?php
session_start();
include "../conn.php";

include "../theme/header.php";

// Lấy ID và kiểm tra hợp lệ
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id <= 0) {
    echo '<div class="alert alert-danger">Invalid food ID.</div>';
    include "../theme/footer.php";
    exit();
}

// Lấy thông tin món ăn
$stmt = $link->prepare("SELECT * FROM food WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $food_name = $row["food_name"];
    $food_category = $row["food_category"];
    $food_description = $row["food_description"];
    $food_original_price = $row["food_original_price"];
    $food_discount_price = $row["food_discount_price"];
    $food_availability = $row["food_availability"];
    $food_veg_nonveg = $row["food_veg_nonveg"];
    $food_ingredients = $row["food_ingredients"];
    $food_image = $row["food_image"];
} else {
    echo '<div class="alert alert-danger">Food not found.</div>';
    include "../theme/footer.php";
    $stmt->close();
    exit();
}
$stmt->close();
?>

<link rel="stylesheet" href="cropping_css/croppie.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Edit Food</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="display.php">Foods</a></li>
                    <li class="active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content mt-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Edit Food</strong>
                </div>
                <div class="card-body">
                    <div id="pay-invoice">
                        <div class="card-body">
                            <div class="alert alert-success" role="alert" id="success" style="display:none">
                                Food Updated successfully.
                            </div>
                            <div class="alert alert-danger" role="alert" id="error" style="display:none">
                                Duplicate Food Found!
                            </div>
                            <div class="alert alert-danger" role="alert" id="error_general" style="display:none">
                                Error updating food. Please try again.
                            </div>

                            <form name="form1" action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <div id="uploaded_image" style="cursor: pointer"
                                        onclick="document.getElementById('upload_image').click();">
                                        <img src="<?php echo htmlspecialchars($food_image ?: 'images/camera.jpg'); ?>"
                                            id="image1" height="100" width="100">
                                    </div>
                                    <input type="file" name="upload_image" id="upload_image" style="display:none"
                                        accept="image/png,image/jpeg">
                                </div>

                                <div class="form-group">
                                    <label for="food_name" class="control-label mb-1">Food Name</label>
                                    <input id="food_name" name="food_name" type="text" class="form-control"
                                        placeholder="Enter Food Name" required
                                        value="<?php echo htmlspecialchars($food_name); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="food_category" class="control-label mb-1">Food Category</label>
                                    <select name="food_category" class="form-control" required>
                                        <?php
                                        $stmt = $link->prepare("SELECT food_categories FROM food_categories ORDER BY food_categories ASC");
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_assoc()) {
                                            $selected = ($food_category == $row["food_categories"]) ? "selected" : "";
                                            echo "<option $selected>" . htmlspecialchars($row["food_categories"]) . "</option>";
                                        }
                                        $stmt->close();
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="food_description" class="control-label mb-1">Food Description</label>
                                    <textarea name="food_description" class="form-control" rows="4"
                                        maxlength="500"><?php echo htmlspecialchars($food_description); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="food_original_price" class="control-label mb-1">Food Original Price</label>
                                    <input id="food_original_price" name="food_original_price" type="number" step="0.01"
                                        class="form-control" placeholder="Enter Food Original Price" required
                                        value="<?php echo htmlspecialchars($food_original_price); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="food_discount_price" class="control-label mb-1">Food Discount Price</label>
                                    <input id="food_discount_price" name="food_discount_price" type="number" step="0.01"
                                        class="form-control" placeholder="Enter Food Discount Price" required
                                        value="<?php echo htmlspecialchars($food_discount_price); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="food_availability" class="control-label mb-1">Food Availability</label>
                                    <select name="food_availability" class="form-control" required>
                                        <option value="Yes" <?php if ($food_availability == "Yes") echo "selected"; ?>>Yes</option>
                                        <option value="No" <?php if ($food_availability == "No") echo "selected"; ?>>No</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="food_veg_nonveg" class="control-label mb-1">Food Veg/Nonveg</label>
                                    <select name="food_veg_nonveg" class="form-control" required>
                                        <option value="Veg" <?php if ($food_veg_nonveg == "Veg") echo "selected"; ?>>Veg</option>
                                        <option value="NonVeg" <?php if ($food_veg_nonveg == "NonVeg") echo "selected"; ?>>NonVeg</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="control-label mb-1">Food Ingredients</label>
                                    <div class="row">
                                        <?php
                                        $stmt = $link->prepare("SELECT food_ingredients FROM food_ingredients ORDER BY food_ingredients ASC");
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $current_ingredients = explode(",", $food_ingredients);
                                        while ($row = $res->fetch_assoc()) {
                                            $ingredient = $row["food_ingredients"];
                                            $checked = in_array($ingredient, $current_ingredients) ? "checked" : "";
                                            ?>
                                            <div class="col-lg-4">
                                                <label>
                                                    <input type="checkbox" name="ingredients[]"
                                                        value="<?php echo htmlspecialchars($ingredient); ?>" <?php echo $checked; ?>>
                                                    <?php echo htmlspecialchars($ingredient); ?>
                                                </label>
                                            </div>
                                            <?php
                                        }
                                        $stmt->close();
                                        ?>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-lg btn-info btn-block" name="submit1">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST["submit1"])) {
    // Xác thực dữ liệu đầu vào
    $food_name = trim($_POST["food_name"]);
    $food_category = $_POST["food_category"];
    $food_description = trim($_POST["food_description"]);
    $food_original_price = floatval($_POST["food_original_price"]);
    $food_discount_price = floatval($_POST["food_discount_price"]);
    $food_availability = $_POST["food_availability"];
    $food_veg_nonveg = $_POST["food_veg_nonveg"];
    $ingredients = isset($_POST["ingredients"]) && is_array($_POST["ingredients"]) ? array_map('trim', $_POST["ingredients"]) : [];

    // Kiểm tra tên món ăn trùng lặp
    $stmt = $link->prepare("SELECT id FROM food WHERE food_name = ? AND id != ?");
    $stmt->bind_param("si", $food_name, $id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        ?>
        <script type="text/javascript">
            document.getElementById("error").style.display = "block";
            document.getElementById("success").style.display = "none";
        </script>
        <?php
        $stmt->close();
    } else {
        $stmt->close();

        // Xác thực giá
        if ($food_original_price <= 0 || $food_discount_price <= 0) {
            ?>
            <script type="text/javascript">
                document.getElementById("error_general").style.display = "block";
                document.getElementById("success").style.display = "none";
            </script>
            <?php
        } else {
            // Chuẩn bị ingredients
            $ingredients_str = implode(",", $ingredients);

            // Cập nhật thông tin món ăn
            $stmt = $link->prepare("UPDATE food SET food_name = ?, food_category = ?, food_description = ?, food_original_price = ?, food_discount_price = ?, food_availability = ?, food_veg_nonveg = ?, food_ingredients = ? WHERE id = ?");
            $stmt->bind_param("ssssssssi", $food_name, $food_category, $food_description, $food_original_price, $food_discount_price, $food_availability, $food_veg_nonveg, $ingredients_str, $id);
            
            if ($stmt->execute()) {
                // Xử lý hình ảnh
                if (isset($_SESSION["image_name01"])) {
                    $image_name = preg_replace("/[^A-Za-z0-9._-]/", "", $_SESSION["image_name01"]);
                    $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                    if (in_array($ext, ['png', 'jpg', 'jpeg']) && file_exists("temp_photo/$image_name")) {
                        $dst = "images/$image_name";
                        if (copy("temp_photo/$image_name", $dst)) {
                            unlink("temp_photo/$image_name");
                            $stmt = $link->prepare("UPDATE food SET food_image = ? WHERE id = ?");
                            $stmt->bind_param("si", $dst, $id);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                    unset($_SESSION["image_name01"]);
                }

                ?>
                <script type="text/javascript">
                    document.getElementById("success").style.display = "block";
                    document.getElementById("error").style.display = "none";
                    setTimeout(function () {
                        window.location = "display.php";
                    }, 1000);
                </script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    document.getElementById("error_general").style.display = "block";
                    document.getElementById("success").style.display = "none";
                </script>
                <?php
            }
            $stmt->close();
        }
    }
}
?>

<div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload & Crop Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-center">
                        <div id="image_demo" style="width:350px;"></div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button class="btn btn-success crop_image">Crop & Upload Image</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="cropping_js/croppie.js"></script>
<script src="cropping_js/exif.js"></script>
<script>
$(document).ready(function () {
    $image_crop = $('#image_demo').croppie({
        enforceBoundary: false,
        enableOrientation: true,
        viewport: { width: 270, height: 230, type: 'square' },
        boundary: { width: 300, height: 250 }
    });

    $('#upload_image').on('change', function () {
        if (this.files.length === 0) return;
        const file = this.files[0];
        const validTypes = ['image/png', 'image/jpeg'];
        if (!validTypes.includes(file.type)) {
            alert('Please upload a PNG or JPEG image.');
            this.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) { // 5MB
            alert('Image size must be less than 5MB.');
            this.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function (event) {
            $image_crop.croppie('bind', {
                url: event.target.result
            }).then(function () {
                console.log('jQuery bind complete');
            });
        };
        reader.readAsDataURL(file);
        $('#uploadimageModal').modal('show');
    });

    $('.crop_image').click(function (event) {
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (response) {
            $.ajax({
                url: "crop_and_upload01.php",
                type: "POST",
                data: { "image": response },
                success: function (data) {
                    if (data.error) {
                        alert('Error uploading image: ' + data.error);
                    } else {
                        $('#uploadimageModal').modal('hide');
                        $('#uploaded_image').html(data);
                    }
                },
                error: function () {
                    alert('Error uploading image. Please try again.');
                }
            });
        });
    });
});
</script>

<?php
include "../theme/footer.php";
?>
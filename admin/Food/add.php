<?php
session_start();
include "../conn.php";
include "../theme/header.php";
?>

<link rel="stylesheet" href="cropping_css/croppie.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Add New Food</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">

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
                    <strong class="card-title">Add New Food</strong>
                </div>
                <div class="card-body">
                    <!-- Credit Card -->
                    <div id="pay-invoice">
                        <div class="card-body">

                            <div class="alert alert-success" role="alert" id="success" style="display:none">
                                Food inserted successfully.
                            </div>
                            <div class="alert alert-danger" role="alert" id="error" style="display:none">
                                Duplicate Food Found!!
                            </div>

                            <form name="form1" action="" method="post">

                                <div class="form-group">
                                    <div id="uploaded_image" style="cursor: pointer"
                                        onclick="document.getElementById('upload_image').click();">
                                        <img src="camera.jpg" id="image1" height="100" width="100">
                                    </div>
                                    <input type="file" name="upload_image" id="upload_image" style="display:none" required>
                                </div>

                                <div class="form-group">
                                    <label for="food_name" class="control-label mb-1">Food Name</label>
                                    <input id="food_name" name="food_name" type="text" class="form-control"
                                        placeholder="Enter Category" required>
                                </div>

                                <div class="form-group">
                                    <label for="food_category" class="control-label mb-1">Food Category</label>
                                    <select name="food_category" id="" class="form-control">
                                        <?php
                                        $res = mysqli_query($link, "SELECT * FROM food_categories ORDER BY food_categories ASC");
                                        while ($row = mysqli_fetch_array($res)) {
                                            echo "<option>";
                                            echo $row["food_categories"];
                                            echo "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="food_description" class="control-label mb-1">Food Description</label>
                                    <textarea name="food_description" id="" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="food_original_price" class="control-label mb-1">Food original
                                        price</label>
                                    <input id="food_original_price" name="food_original_price" type="text"
                                        class="form-control" placeholder="Enter food original price" required>
                                </div>

                                <div class="form-group">
                                    <label for="food_discount_price" class="control-label mb-1">Food discount
                                        price</label>
                                    <input id="food_discount_price" name="food_discount_price" type="text"
                                        class="form-control" placeholder="Enter food discount price" required>
                                </div>

                                <div class="form-group">
                                    <label for="food_availability" class="control-label mb-1">Food Availability</label>
                                    <select name="food_availability" class="form-control">
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="food_veg_nonveg" class="control-label mb-1">Food veg / nonveg</label>
                                    <select name="food_veg_nonveg" class="form-control">
                                        <option>Veg</option>
                                        <option>NonVeg</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <?php
                                    $res = mysqli_query($link, "SELECT * FROM food_ingredients ORDER BY food_ingredients ASC");
                                    while ($row = mysqli_fetch_array($res)) {
                                        ?>
                                        <div class="col-lg-4">
                                            <input type="checkbox" name="ingredients[]"
                                                value="<?php echo $row["food_ingredients"] ?>">&nbsp;<?php echo $row["food_ingredients"] ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block"
                                        name="submit1">
                                        <span id="payment-button-amount">Submit</span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div> <!-- .card -->

        </div><!--/.col-->
    </div>

</div> <!-- .content -->

<?php
if (isset($_POST["submit1"])) {

    $count = 0;
    $ingredients = "";
    foreach ($_POST["ingredients"] as $check) {
        $count = $count + 1;
        if ($count == 1) {
            $ingredients = $check;
        } else {
            $ingredients = $ingredients . "," . $check;
        }
    }

    copy('temp_photo/' . $_SESSION["image_name01"], 'images/' . $_SESSION["image_name01"]);
    $dst1 = "images/" . $_SESSION["image_name01"];

    $count = 0;
    $res = mysqli_query($link, "SELECT * FROM food WHERE food_name='$_POST[food_name]'") or die(mysqli_error($link));
    $count = mysqli_num_rows(($res));
    if ($count > 0) {
        ?>
        <script type="text/javascript">
            document.getElementById("error").style.display = "block";
            document.getElementById("success").style.display = "none";

        </script>
        <?php
    } else {
        mysqli_query($link, "INSERT INTO food VALUES (Null,'$_POST[food_name]','$_POST[food_category]','$_POST[food_description]','$_POST[food_original_price]','$_POST[food_discount_price]','$_POST[food_availability]','$_POST[food_veg_nonveg]','$ingredients','$dst1')") or die(mysqli_error($link));
        ?>
        <script type="text/javascript">
            document.getElementById("success").style.display = "block";
            document.getElementById("error").style.display = "none";

            setTimeout(function () {
                window.location.href = window.location.href;
            }, 3000);
        </script>
        <?php
    }

    unset($_SESSION["image_name01"]);

    ?>
    <script type="text/javascript">
        setTimeout(function () {
            window.location.href = window.location.href;
        }, 1000);
    </script>
    <?php

}

?>

<!-- hàm xử lý ảnh -->

<div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog" style="width:auto">
        <div class="modal-content" style="width: 1000px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload & Crop Image</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-center">
                        <div id="image_demo" style="width:350px;"></div>

                    </div>

                    <div class="col-md-12">
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
<script>
    //https://foliotek.github.io/Croppie/
    $(document).ready(function () {
        $image_crop = $('#image_demo').croppie({
            enforceBoundary: false,
            enableOrientation: true,
            viewport: {
                width: 270,
                height: 230,
                type: 'square'
            },
            boundary: {
                width: 300,
                height: 250
            }
        });

        $('#upload_image').on('change', function () {

            var reader = new FileReader();
            reader.onload = function (event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                }).then(function () {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
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
                        $('#uploadimageModal').modal('hide');
                        $('#uploaded_image').html(data);
                    }
                });
            })
        });

    });
</script>
<script src="cropping_js/bootstrap.min.js"></script>
<script src="cropping_js/croppie.js"></script>
<script src="cropping_js/exif.js"></script>


<?php
include "../theme/footer.php";
?>
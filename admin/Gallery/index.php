<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

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
                <h1>Add new gallery photo</h1>
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
                    <strong class="card-title">Add new gallery photo</strong>
                </div>
                <div class="card-body">
                    <!-- Credit Card -->
                    <div id="pay-invoice">
                        <div class="card-body">

                            <div class="alert alert-success" role="alert" id="success" style="display:none">
                                Photo inserted successfully.
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
                                    <label for="title" class="control-label mb-1">Photo title</label>
                                    <input id="title" name="title" type="text" class="form-control"
                                        placeholder="Enter Category" required>
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

<div class="content mt-3">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Food Image</th>
                                <th scope="col">Food name</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count=0;
                            $res=mysqli_query($link,"SELECT * FROM gallery");
                            while($row=mysqli_fetch_array($res))
                            {
                                $count=$count+1;
                                echo "<tr>";
                                echo "<td>"; echo $count; echo "</td>";
                                echo "<td>"; ?> <img src="<?php echo $row["image"];?>" alt="<?php echo $row["title"];?>"> <?php echo "</td>";
                                echo "<td>"; echo $row["title"]; echo "</td>";
                                echo "<td>"; ?> <a href="delete.php?id=<?php echo $row["id"]; ?>" style="color: red;">Delete</a> <?php echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div> <!-- .content -->
<?php
if (isset($_POST["submit1"])) {

    copy('temp_photo/' . $_SESSION["image_name01"], 'images/' . $_SESSION["image_name01"]);
    $dst1 = "images/" . $_SESSION["image_name01"];

    $count = 0;
    $res = mysqli_query($link, "SELECT * FROM gallery WHERE title='$_POST[title]'") or die(mysqli_error($link));
    $count = mysqli_num_rows(($res));
    if ($count > 0) {
        ?>
        <script type="text/javascript">
            document.getElementById("error").style.display = "block";
            document.getElementById("success").style.display = "none";
        </script>
        <?php
    } else {
        mysqli_query($link, "INSERT INTO gallery VALUES (Null,'$dst1','$_POST[title]')") or die(mysqli_error($link));
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
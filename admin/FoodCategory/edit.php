<?php
include "../conn.php";
include "../theme/header.php";
$id=$_GET["id"];
$category_name="";
$res=mysqli_query($link,"SELECT * FROM food_categories WHERE id=$id");
while($row=mysqli_fetch_array($res))
{
    $category_name=$row["food_categories"];
}
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Edit categories</h1>
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
                    <strong class="card-title">Edit categories</strong>
                </div>
                <div class="card-body">
                    <!-- Credit Card -->
                    <div id="pay-invoice">
                        <div class="card-body">

                            <form name="form1" action="" method="post">

                                <div class="form-group">
                                    <label for="food_category" class="control-label mb-1">Category Name</label>
                                    <input id="food_category" name="food_category" type="text" class="form-control"
                                        placeholder="Enter Category" required value="<?php echo $category_name ?>">
                                </div>

                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block"
                                        name="submit1">
                                        <span id="payment-button-amount">Edit</span>
                                    </button>
                                </div>
                                <div class="alert alert-success" role="alert" id="success" style="display:none">
                                    Categories updated successfully.
                                </div>
                                <div class="alert alert-danger" role="alert" id="error" style="display:none">
                                    Duplicate Categories Found
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
    $res = mysqli_query($link, "SELECT * FROM food_categories WHERE food_categories='$_POST[food_category]'") or die(mysqli_error($link));
    $count = mysqli_num_rows(($res));
    if ($count > 0) {
        ?>
        <script type="text/javascript">
            document.getElementById("error").style.display = "block";
            document.getElementById("success").style.display = "none";

        </script>
        <?php
    } else {
        mysqli_query($link, "UPDATE food_categories SET food_categories = '$_POST[food_category]' WHERE id=$id") or die(mysqli_error($link));
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

    ?>
    <script type="text/javascript">
        setTimeout(function () {
            window.location = "index.php"
        }, 1000);
    </script>
    <?php

}

?>


<?php
include "../theme/footer.php";
?>
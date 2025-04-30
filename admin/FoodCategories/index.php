<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

include "../conn.php";
include "../theme/header.php";
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Add/Edit categories</h1>
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
                    <strong class="card-title">Add/Edit categories</strong>
                </div>
                <div class="card-body">
                    <!-- Credit Card -->
                    <div id="pay-invoice">
                        <div class="card-body">

                            <form name="form1" action="" method="post">

                                <div class="form-group">
                                    <label for="food_category" class="control-label mb-1">Category Name</label>
                                    <input id="food_category" name="food_category" type="text" class="form-control"
                                        placeholder="Enter Category" required>
                                </div>

                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block"
                                        name="submit1">
                                        <span id="payment-button-amount">Submit</span>
                                    </button>
                                </div>
                                <div class="alert alert-success" role="alert" id="success" style="display:none">
                                    Categories inserted successfully.
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Categories</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Category name</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count=0;
                            $res=mysqli_query($link,"SELECT * FROM food_categories");
                            while($row=mysqli_fetch_array($res))
                            {
                                $count=$count+1;
                                echo "<tr>";
                                echo "<td>"; echo $count; echo "</td>";
                                echo "<td>"; echo $row["food_categories"]; echo "</td>";
                                echo "<td>"; ?> <a href="edit.php?id=<?php echo $row["id"]; ?>" style="color: green;">Edit</a> <?php echo "</td>";
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
        mysqli_query($link, "INSERT INTO food_categories VALUES (Null,'$_POST[food_category]')") or die(mysqli_error($link));
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
            window.location.href = window.location.href;
        }, 1000);
    </script>
    <?php

}

?>


<?php
include "../theme/footer.php";
?>
<?php
include "../conn.php";
include "../theme/header.php";
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Foods menu</h1>
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
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Food Image</th>
                                <th scope="col">Food name</th>
                                <th scope="col">Category</th>
                                <th scope="col">Description</th>
                                <th scope="col">Original Price</th>
                                <th scope="col">Discount Price</th>
                                <th scope="col">Availability</th>
                                <th scope="col">Veg / NonVeg</th>
                                <th scope="col">Ingredients</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count=0;
                            $res=mysqli_query($link,"SELECT * FROM food");
                            while($row=mysqli_fetch_array($res))
                            {
                                $count=$count+1;
                                echo "<tr>";
                                echo "<td>"; echo $count; echo "</td>";
                                echo "<td>"; ?> <img src="<?php echo $row["food_image"];?>" alt="<?php echo $row["food_name"];?>"> <?php echo "</td>";
                                echo "<td>"; echo $row["food_name"]; echo "</td>";
                                echo "<td>"; echo $row["food_category"]; echo "</td>";
                                echo "<td>"; echo $row["food_description"]; echo "</td>";
                                echo "<td>"; echo $row["food_original_price"]; echo "</td>";
                                echo "<td>"; echo $row["food_discount_price"]; echo "</td>";
                                echo "<td>"; echo $row["food_availability"]; echo "</td>";
                                echo "<td>"; echo $row["food_veg_nonveg"]; echo "</td>";
                                echo "<td>"; echo $row["food_ingredients"]; echo "</td>";
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
include "../theme/footer.php";
?>
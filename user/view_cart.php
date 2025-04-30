<?php
session_start();
include "./theme/header.php";
include "../admin/conn.php";
?>
<link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
<title>FrostBite</title>

<!-- Page Title -->
<section class="page-title" style="background-image: url(assets/images/background/11.jpg)">
    <div class="auto-container">
        <h1>Shoping Cart</h1>
        </ul>
    </div>
</section>
<!-- End Page Title -->


<?php
if (load_cart_data() > 0) {
    ?>
    <!--Cart Section-->
    <section class="cart-section">
        <div class="auto-container">

            <!--Cart Outer-->
            <form action="" method="post">
                <div class="cart-outer">
                    <div class="table-outer">
                        <table class="cart-table">
                            <thead class="cart-header">
                                <tr>
                                    <th>Preview</th>
                                    <th class="prod-column">product</th>
                                    <th class="price">Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $max = 0;
                                $count = 0;
                                $grand_total = 0;
                                if (isset($_SESSION["cart"])) {
                                    $max = sizeof($_SESSION["cart"]);
                                }

                                for ($i = 0; $i < $max; $i++) {
                                    if (isset($_SESSION['cart'][$i])) {
                                        $img_session = "";
                                        $nm_session = "";
                                        $price_session = "";
                                        $qty_total_session = "";
                                        $tb_id_session = "";
                                        $unit_session = "";

                                        foreach ($_SESSION['cart'][$i] as $key => $val) {
                                            if ($key == "img1") {
                                                $img_session = $val;
                                            }

                                            if ($key == "nm") {
                                                $nm_session = $val;
                                            }
                                            if ($key == "price") {
                                                $price_session = $val;
                                            }
                                            if ($key == "qty_total") {
                                                $qty_total_session = $val;
                                            }
                                            if ($key == "tb_id") {
                                                $tb_id_session = $val;
                                            }
                                            if ($key == "unit") {
                                                $unit_session = $val;
                                            }
                                        }
                                    }

                                    // echo $nm_session."==".$qty_total_session;
                                    ?>

                                    <?php
                                    if ($img_session != "" && $img_session != null) {
                                        $count = $count + 1;
                                        $grand_total = $grand_total + ($price_session * $qty_total_session);

                                        ?>
                                        <tr>
                                            <td class="prod-column">
                                                <div class="column-box">
                                                    <figure class="prod-thumb"><a href="#"><img
                                                                src="../admin/Food/<?php echo $img_session ?>" alt=""></a></figure>
                                                </div>
                                            </td>
                                            <td>
                                                <h4 class="prod-title"><?php echo $nm_session ?></h4>
                                            </td>
                                            <td class="sub-total"><?php echo $price_session ?>$</td>
                                            <td class="qty">
                                                <div class="item-quantity"><input class="quantity-spinner" type="text"
                                                        id="qty<?php echo $i; ?>" value="<?php echo $qty_total_session ?>"
                                                        name="quantity">
                                                </div>
                                            </td>
                                            <td class="price"><?php echo $price_session * $qty_total_session; ?></td>
                                            <td>
                                                <a href="#" class="remove-btn"
                                                    onclick="delete_product('<?php echo $tb_id_session ?>')"><span
                                                        class="fa fa-times"></span></a>
                                                <a href="#" class="remove-btn" style="color: red"
                                                    onclick="update_product('<?php echo $tb_id_session ?>','<?php echo $i ?>')"><span
                                                        class="fa fa-refresh"></span></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>

                    <div class="row clearfix">

                        <div class="column col-lg-7 col-md-12 col-sm-12"></div>

                        <div class="column pull-right col-lg-5 col-md-12 col-sm-12">
                            <!--Totals Table-->
                            <ul class="totals-table">
                                <li>
                                    <h3>Payment Type</h3>
                                </li>
                                <li class="clearfix total"><input type="radio" name="r1" value="cod" checked><span class="col"
                                        style="text-align: left">Cash On Delivery (COD)</li>
                                <li class="clearfix total"><input type="radio" name="r1" value="momo"><span class="col"
                                        style="text-align: left">Momo banking</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row clearfix">

                        <div class="column col-lg-7 col-md-12 col-sm-12"></div>

                        <div class="column pull-right col-lg-5 col-md-12 col-sm-12">
                            <!--Totals Table-->
                            <ul class="totals-table">
                                <li>
                                    <h3>Cart Totals</h3>
                                </li>
                                <li class="clearfix total"><span class="col">Grand Total</span><span
                                        class="col price"><?php echo $grand_total ?> $</span>
                                </li>
                                <li class="text-right"><button type="submit" class="theme-btn btn-style-five proceed-btn"
                                        name="continue2"><span class="txt">Proceed to
                                            Checkout</span></button></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!--End Cart Section-->
    <?php
} else {
    echo "<div style='height: 200px; width: 100%; text-align: center'>";
    echo "<h3 style='margin-top: 50px'>Notthing In There</h3>";
    echo "</div>";
}
?>

<!-- function -->
<?php
if (isset($_POST["continue2"])) {
    $_SESSION["payment_type"] = $_POST["r1"];
    $_SESSION["cart_count"] = $count;
    $_SESSION["checkout"] = "yes";
    $_SESSION["sub_total"] = $grand_total;
    ?>
    <script type="text/javascript">
        window.location = "checkout.php";
    </script>
    <?php
}
?>

<?php
function load_cart_data()
{
    $count = 0;
    $max = 0;
    if (isset($_SESSION['cart'])) {
        $max = sizeof($_SESSION['cart']);
    }
    for ($i = 0; $i < $max; $i++) {
        if (isset($_SESSION['cart'][$i])) {
            $img1_session = "";
            foreach ($_SESSION['cart'][$i] as $key => $val) {
                if ($key == "img1") {
                    $img1_session = $val;
                }
            }

            if ($img1_session != "" && $img1_session != null) {
                $count = $count + 1;
            }
        }
    }
    return $count;
}

?>
<script type="text/javascript">
    function delete_product(tb_id) {
        var xmlhttp1 = new XMLHttpRequest();
        xmlhttp1.onreadystatechange = function () {
            if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
                window.location = "view_cart.php";
            }
        };
        xmlhttp1.open("GET", "delete_from_cart.php?tb_id=" + tb_id, true);
        xmlhttp1.send();
    }
    function update_product(tb_id, qtyid) {
        var qty = "qty" + qtyid; // Tạo ID của input quantity
        var qty1 = document.getElementById(qty).value; // Lấy giá trị quantity từ input

        var xmlhttp1 = new XMLHttpRequest();
        xmlhttp1.onreadystatechange = function () {
            if (xmlhttp1.readyState == 4) { // Kiểm tra khi request hoàn tất
                if (xmlhttp1.status == 200) { // Kiểm tra nếu request thành công
                    alert("Cart updated successfully!");
                    window.location = "view_cart.php"; // Chuyển hướng sau khi thành công
                } else {
                    alert("Error updating cart: " + xmlhttp1.status); // Thông báo lỗi nếu có
                }
            }
        };
        xmlhttp1.open("GET", "update_from_cart.php?id=" + tb_id + "&qty=" + qty1, true);
        xmlhttp1.send();
    }
</script>

<?php
include "./theme/footer.php";
?>


<!--Scroll to top-->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/parallax.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.fancybox.js"></script>
<script src="assets/js/owl.js"></script>
<script src="assets/js/wow.js"></script>
<script src="assets/js/jquery.bootstrap-touchspin.js"></script>
<script src="assets/js/appear.js"></script>
<script src="assets/js/script.js"></script>
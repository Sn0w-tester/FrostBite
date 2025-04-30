<?php
session_start();
echo "Please Wait.. We are generating your order..";
if(!isset($_SESSION["checkout"]))
{
    ?>
    <script type="text/javascript">
        window.location="index.php";
    </script>
    <?php

}
if(!isset($_SESSION['user_username']))
{
    ?>
    <script type="text/javascript">
        window.location="index.php";
    </script>
    <?php

}
$_SESSION["address_verify"]="yes";
include "../admin/conn.php";
//include "slider.php";

$firstname="";
$lastname="";
$email="";
$contact="";
$address="";

mysqli_query($link,"set names utf8");
$res=mysqli_query($link,"select * from user_registration where username='$_SESSION[user_username]'");
while($row=mysqli_fetch_array($res))
{
    $firstname=$row["firstname"];
    $lastname=$row["lastname"];
    $email=$row["email"];
    $contact=$row["contact"];
    $address=$row["address"];
}
?>

<section class="checkout-page">
    <div class="auto-container">
        <?php
        $order_id="";


        if(!isset($_SESSION["checkout"]) || !isset($_SESSION["orderno"]))
        {

            ?>
            <script type="text/javascript">
                window.location="index.php";
            </script>
        <?php
        }
        else if($_SESSION["orderno"]!=$_GET["orderno"])
        {

        ?>
            <script type="text/javascript">
                window.location="index.php";
            </script>
        <?php

        }
        else {
        $_SESSION["orderdone"] = "yes";

        ?>
        <?php

        $date = date("d-m-Y");
        $time = date("H:i:s");
        $read = "n";


        if(isset($_SESSION["user_username"]))
        {
            mysqli_query($link, "insert into order_main (id,order_number,order_username, order_date, order_time,order_status,order_address,user_firstname,user_lastname,user_email,user_contact,order_type) values(NULL,'$_GET[orderno]','$_SESSION[user_username]','$date','$time','Pending','$address','$firstname','$lastname','$email','$contact','$_SESSION[payment_type]')") or die(mysqli_error($link));
        }


        $res1 = mysqli_query($link, "select * from order_main order by id desc limit 1");
        while ($row1 = mysqli_fetch_array($res1)) {
            $order_id = $row1['id'];
        }
        /*  if (isset($_COOKIE['item'])) {
              foreach ($_COOKIE['item'] as $name => $value) {*/

        $max="";
        if (isset($_SESSION['cart']))
        {
            $max = sizeof($_SESSION['cart']);
        }


        for ($i = 0; $i < $max; $i++) {
            $img1_session = "";
            $nm_session = "";
            $product_price = "";
            $qty_total_session = "";
            $tb_id_session = "";

            if (isset($_SESSION['cart'][$i])) {
                foreach ($_SESSION['cart'][$i] as $key => $val) {
                    if ($key == "img1") {
                        $img1_session = $val;
                    } else if ($key == "nm") {
                        $nm_session = $val;
                    } else if ($key == "price") {
                        $product_price = $val;
                    } else if ($key == "qty_total") {
                        $qty_total_session = $val;
                    } else if ($key == "tb_id") {
                        $tb_id_session = $val;

                    }

                    if($tb_id_session!="") {

                        $img = $img1_session;
                        $title = $nm_session;
                        $price = $product_price;
                        $qty = $qty_total_session;
                        $sub_tot = round($price * $qty, 2);
                        $title = mysqli_real_escape_string($link, $title);

                        $food_name = "";
                        $food_category = "";
                        $food_description = "";
                        $food_original_price = "";
                        $food_discount_price = "";
                        $food_veg_nonveg = "";
                        $food_ingredients = "";
                        $food_image = "";

                        $res2 = mysqli_query($link, "select * from  food where id='$tb_id_session'");
                        while ($row2 = mysqli_fetch_array($res2)) {
                            $food_name = $row2["food_name"];
                            $food_category = $row2["food_category"];
                            $food_description = $row2["food_description"];
                            $food_original_price = $row2["food_original_price"];
                            $food_discount_price = $row2["food_discount_price"];
                            $food_veg_nonveg = $row2["food_veg_nonveg"];
                            $food_ingredients = $row2["food_ingredients"];
                            $food_image = $row2["food_image"];
                        }


                        mysqli_query($link, "insert into order_details (id,order_id,food_name, food_category, food_description, food_ingredients, food_original_price, food_discount_price,food_veg_nonveg,food_image,food_qty) values(NULL,'$order_id','$food_name','$food_category','$food_description','$food_ingredients','$food_original_price','$food_discount_price','$food_veg_nonveg','$food_image','$qty_total_session')") or die(mysqli_error($link));


                    }

                    /* }
                 }*/
                }
            }
        }



        unset($_SESSION["address_verify"]);
        unset($_SESSION["checkout"]);
        unset($_SESSION["orderno"]);
        unset($_SESSION['user_username']);
        unset($_SESSION["orderno"]);
        unset($_SESSION["cart"]);
        $_SESSION["order_complete_msg"]="yes";



        ?>
            <script type="text/javascript">
                window.location="order_complete.php";
            </script>
            <?php


        }
        ?>








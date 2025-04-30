<?php
session_start();
include "./theme/header.php";
//include "slider.php";
?>

<section class="products-section">
    <div class="auto-container">

        <?php
        if(!isset($_SESSION["order_complete_msg"]))
        {
            ?>
            <script type="text/javascript">
                window.location="index.php";
            </script>
        <?php
        }
        else {
        ?>
            <div class="col-xl-10 col-lg-12 m-auto" style="text-align: center">
                <img src="./assets/images/check.png">

                <h3 style="text-align: center; margin-top: 20px">Your Order Placed Successfully.</h3>
            </div>
            <?php
            unset($_SESSION["order_complete_msg"]);
        }
        ?>
    </div>
</section>



<?php
include "./pages/delivery.php";
include "./pages/service.php";
include "./theme/footer.php";
?>






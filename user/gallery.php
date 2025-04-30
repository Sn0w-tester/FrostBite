<?php
include "../admin/conn.php";
include "./theme/header.php";
?>

<title>FrostBite - Gallery</title>

<section class="page-title" style="background-image: url(assets/images/background/11.jpg)">
    <div class="auto-container">
        <h1>Gallery</h1>
    </div>
</section>

<!-- Products Section -->
<section class="products-section">
    <div class="auto-container">
        <!-- Gallery Page Section -->
        <section class="gallery-page-section">
            <div class="auto-container">
                <div class="row clearfix">

                    <!-- Gallery Block Two -->
                    <?php
                    $res = mysqli_query($link, "SELECT * FROM gallery");
                    while ($row = mysqli_fetch_array($res)) {
                        ?>
                        <div class="gallery-block-two col-lg-4 col-md-6 col-sm-12">
                            <div class="inner-box">
                                <div class="image">
                                    <img src="../admin/gallery/<?php echo $row['image'] ?>" alt="" />
                                    <!-- Overlay Box -->
                                    <div class="overlay-box">
                                        <div class="overlay-inner">
                                            <a href="../admin/gallery/<?php echo $row['image'] ?>" data-fancybox="gallery" data-caption=""
                                                class="link"><span class="icon flaticon-expand"></span></a>
                                            <h5><a href="#"><?php echo $row['title'] ?></a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
        <!-- End Gallery Page Section -->
    </div>
</section>
<!-- End Products Section -->


<?php
include "./pages/delivery.php";
include "./pages/service.php";
include "./theme/footer.php";
?>
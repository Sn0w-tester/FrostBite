<?php
include "../conn.php";
$id=$_GET["id"];
mysqli_query($link, "DELETE FROM food_ingredients WHERE id=$id");
?>
<script type="text/javascript">
        setTimeout(function () {
            window.location ="index.php";
        }, 1000);
</script>
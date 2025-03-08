<?php
include "../conn.php";
$id=$_GET["id"];
mysqli_query($link, "DELETE FROM food WHERE id=$id");
?>
<script type="text/javascript">
        setTimeout(function () {
            window.location ="display.php";
        }, 1000);
</script>x`
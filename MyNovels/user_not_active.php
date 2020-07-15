<?php
ob_start();
session_start();
include "includes/header.php";
include "includes/navbar.php";
if(isset($_SESSION['u_name'])){ // if user is log in hide add user
    ?>
    <script>
        document.getElementById("nav_a_add_user").style.display = "none";
        document.getElementById("nav_a_login_user").style.display = "none";
    </script>
    <?php
}else{
    ?>
    <script>
        document.getElementById("nav_a_user_edit").style.display = "none";
    </script>
    <?php
}
?>
<div class='container'>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-danger text-center">
            عفواً لا يمكن أضافة أى رواية أو التعديل على أى رواية ، يمكنك التواصل مع المشرفين لتفعيل حسابك
        </div>
        </div>
    </div>
</div>
<?php
include "includes/footer.php";
ob_end_flush();
?>

<?php
ob_start();
session_start();
include "includes/header.php";
echo "<div class='container'>";
if(isset($_SESSION['u_name'])){ // if user is log in go to home page
    header("Location: index.php");
}

if(!isset($_GET["do"])){ // do not found set add
    $do = "form";
}else{
    $do = $_GET["do"];
}


if($do == "form"){ // is do == add
    // add user code
    
    ?>
<form class="col-lg-6 col-md-7 col-sm-12 form_add_user" method="post" action="?do=login">
    <h1>تسجيل الدخول</h1>
    <table>

        <tr>
            <td colspan="2">
                <div>
                      <label for="u_name">اسم المستخدم</label><span style="color:red;">*</span>
                      <input type="text" class="form-control" name="u_name" id="u_name" placeholder="اسم المستخدم" required="required" minlength="6">
                </div>
            </td>

        </tr>

        <tr>
            <td colspan="2">
                <div>
                      <label for="pass">كلمة المرور</label><span style="color:red;">*</span>
                      <input type="password" class="form-control" name="password" id="pass" placeholder="كلمة المرور" required="required" minlength="6">
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <button type="submet" class="btn btn-info btn-lg btn-block"><i class="fas fa-sign-in-alt"></i>تسجيل الدخول</button>
            </td>
        </tr>
    </table><br>
    <div class="alert alert-danger" style="text-align: center; display:none;" id="div_erorr">
    عفواً هناك خطأ فى البيانات المدخلة يرجى التأكد من أسم المستخدم و كلمة المرور
    </div>
    <div class="text-center">
    <a href="users.php" >انشاء حساب جديد</a>
    </div>
</form>

    <?php

    
}else if($do == "login"){ // is do == login
    // login user code
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $u_name     = trim($_POST["u_name"]);
        $pass       = $_POST["password"];

        $errs = array();
        if(empty($u_name)){
            $errs[] = "عفواً ، اسم المستخدم فارغ";
        }
        if(strlen($u_name) < 6){
            $errs[] = "عفواً ، الحد الأدنى لأسم المستخدم 6 أحرف";
        }
        if(empty($pass)){
            $errs[] = "عفواً ، كلمة المرور فارغة";
        }
        if(strlen($pass) < 6){
            $errs[] = "عفواً ، الحد الأدنى لكلمة المرور 6 أحرف";
        }

        if(empty($errs)){
            
            include "includes/connect.php";
            
            $stmt = $con->prepare("SELECT * FROM users WHERE u_name = ? AND password = ?");
            $stmt->execute(array($u_name,sha1($pass)));

            if($stmt->rowCount() > 0){
                $_SESSION['u_name'] = $u_name;
                $r = $stmt->fetch();
                $_SESSION['f_name'] = $r['f_name'];
                $_SESSION['image'] = $r['image'];
                $_SESSION['l_name'] = $r['l_name'];
                $_SESSION['status'] = $r['status'];
                header("Location: index.php");
            }else{

                header("Location: login.php");

            }

        }else{
            echo '<br>';
            foreach ($errs as $err) {
                
                echo '<center>';
                echo '<div class="alert alert-danger">'.$err.'</div>';
                echo '</center>';
            }

        }
        


    }else{ // if not get with post method go to login user
        header("Location: index.php");
    }



}else{ // is do != form or login
    header("Location: 404.php");
}


echo "</div>";
include "includes/footer.php";
ob_end_flush();
?>
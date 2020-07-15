<?php
ob_start();
session_start();
include "includes/header.php";
echo "<div class='container'>";


if(!isset($_GET["do"])){ // do not found set add
    $do = "add";
}else{
    $do = $_GET["do"];
}


if($do == "add"){ // is do == add
    // add user code

    if(isset($_SESSION['u_name'])){ // if user is log in go to home page
        header("Location: index.php");
    }
    
?>
<form class=" col-lg-8 col-md-10 col-sm-12 form_add_user" method="post" action="?do=insert">
    <h1>انشاء حساب جديد</h1>
    <table>
        <tr>
            <td>
                <div>
                      <label for="f_name">الأسم الأول</label><span style="color:red;">*</span>
                      <input type="text" class="form-control" name="f_name" id="f_name" placeholder="اسمك" required="required">
                </div>
            </td>
            <td>
                <div>
                    <label for="l_name">الأسم الثانى</label><span style="color:red;">*</span>
                    <input type="text" class="form-control" name="l_name" id="l_name" placeholder="اسم الأب" required="required">
              </div>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <div>
                      <label for="u_name">اسم المستخدم</label><span style="color:red;">*</span>
                      <input type="text" class="form-control" name="u_name" id="u_name" placeholder="اسم المستخدم يجب ان يكون أكثر من 6 أحرف" required="required" minlength="6">
                </div>
            </td>

        </tr>

        <tr>
            <td>
                <div>
                      <label for="pass">كلمة المرور</label><span style="color:red;">*</span>
                      <input type="password" class="form-control" name="password" id="pass" placeholder="كلمة المرور يجب ان تكون اكبر من 6 احرف" required="required" minlength="6">
                </div>
            </td>
            <td>
                <div>
                    <label for="repass">تأكيد كلمة المرور</label><span style="color:red;">*</span>
                    <input type="password" class="form-control" name="repassword" id="repass" placeholder="تأكيد كلمة المرور" required="required" minlength="6">
              </div>
            </td>
        </tr>

        <tr>
            <td>
                <div>
                    <label>النوع <span style="color:red;">*</span> &nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label >
                        <input type="radio" name="gender" value="ذكر"  autocomplete="off" checked>
                        ذكر
                    </label>
                    <label >
                        <input type="radio" name="gender" value="انثى" autocomplete="off">
                        أنثى
                    </label>

                </div>
            </td>
            
        </tr>
        <tr>
            <td colspan="2">
                <button type="submet" class="btn btn-info btn-lg btn-block"><i class="fas fa-user-plus"></i> انشأء الحساب</button>
            </td>
        </tr>



    </table>
</form>

<?php

    
}else if($do == "insert"){ // is do == insert
    if(isset($_SESSION['u_name'])){ // if user is log in go to home page
        header("Location: index.php");
    }
    // insert user code
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include "includes/connect.php";
        $errs = array();
        $f_name     = trim($_POST["f_name"]);
        $l_name     = trim($_POST["l_name"]);
        $u_name     = trim($_POST["u_name"]);
        $pass       = $_POST["password"];
        $repass     = $_POST["repassword"];
        $gender = $_POST['gender'];


            $stmt1 = $con->prepare("SELECT * FROM users WHERE u_name = ?");
            $stmt1->execute(array($u_name));

            if($stmt1->rowCount() > 0){
                $errs[] = "عفواً ، اسم المستخدم موجود من قبل يرجى اختيار اسم اخر  ";
            }


        
        if(empty($f_name)){
            $errs[] = "عفواً ، الأسم الأول فارغ";
        }
        if(empty($l_name)){
            $errs[] = "عفواً ، الأسم الثانى فارغ";
        }
        if(empty($u_name)){
            $errs[] = "عفواً ، اسم المستخدم فارغ";
        }
        if(empty($pass)){
            $errs[] = "عفواً ، كلمة المرور فارغة";
        }
        if(empty($repass)){
            $errs[] = "عفواً ، إعادة كلمة المرور فارغة";
        }
        
        if(strlen($f_name) < 3){
            $errs[] = "عفواً ، الحد الأدنى لللأسم الأول 3 احرف";
        }

        if(strlen($l_name) < 3){
            $errs[] = "عفواً ، الحد الأدنى لللأسم الثانى 3 احرف";
        }

        if(strlen($u_name) < 6){
            $errs[] = "عفواً ، الحد الأدنى لأسم المستخدم 6 أحرف";
        }

        if(strlen($pass) < 6){
            $errs[] = "عفواً ، الحد الأدنى لكلمة المرور 6 أحرف";
        }

        if(strlen($repass) < 6){
            $errs[] = "عفواً ، الحد الأدنى لإعادة كلمة المرور 6 أحرف";
        }

        if($pass != $repass){
            $errs[] = "عفواً ، ان كلمتان السر غير متطابقتين";
        }

        if(empty($errs)){
            
            
            
            $stmt = $con->prepare("INSERT INTO users (f_name,l_name,u_name,password,add_date,gender,image,status)
                                 VALUES (:f_name,:l_name,:u_name,:password,:add_date,:gender,:image,:status)");
            

            $year = date('Y');
            $day = date('d');
            $manth = date('m');
            $date = $year."-".$manth."-".$day;
            
            $stmt->execute(array(
                "f_name"        => $f_name,
                "l_name"        => $l_name,
                "u_name"        => $u_name,
                "password"      => sha1($pass),
                "add_date"      => $date,
                "gender"        => $gender,
                "image"         => "user.png",
                "status"        => 1
            ));
            $_SESSION['u_name'] = $u_name;
            $_SESSION['f_name'] = $f_name;
            $_SESSION['l_name'] = $l_name;
            header("Location: index.php");

        }else{
            echo '<br>';
            foreach ($errs as $err) {
                
                echo '<center>';
                echo '<div class="alert alert-danger">'.$err.'</div>';
                echo '</center>';
            }

        }


    }else{ // if not get with post method go to add user
        header("Location: users.php?do=add");
    }



}else if($do == "editProfile"){ // is do = edit form
    include "includes/connect.php";
    $stmt = $con->prepare("SELECT * FROM users WHERE u_name = ?");
    $stmt->execute(array($_SESSION['u_name']));
    $r = $stmt->fetch();
?>
<form class=" col-lg-8 col-md-10 col-sm-12 form_add_user" method="post" action="?do=updateProfile" enctype="multipart/form-data">
    <h1>تعديل الحساب الشخصى</h1>
    <div style="text-align:center;">
        <?php
            if(!$r['image'] == ""){

                ?>
                    <img class="img-profile" src="uplouds/image_profile/<?php echo $r['image'] ?>" alt="uplouds/image_profile/<?php echo $r['image'] ?>" width="100px" />
                <?php

            }else{

                ?>
                    <img class="img-profile" src="uplouds/image_profile/user.png"  alt="uplouds/image_profile/<?php echo $r['image'] ?>" width="100px"  />
                <?php

            }
        ?>
    </div><br>
    <table>
        <tr>
            <td>
                <div>
                      <label for="f_name">الأسم الأول</label><span style="color:red;">*</span>
                      <input type="text" value="<?php echo $r['f_name'] ?>" class="form-control" name="f_name" id="f_name" placeholder="اسمك" required="required">
                </div>
            </td>
            <td>
                <div>
                    <label for="l_name">الأسم الثانى</label><span style="color:red;">*</span>
                    <input type="text" value="<?php echo $r['l_name'] ?>" class="form-control" name="l_name" id="l_name" placeholder="اسم الأب" required="required">
              </div>
            </td>
        </tr>


        <tr>
            <td colspan="2">
                <div>
                      <label for="pass">تغيير كلمة المرور</label>
                      <input type="hidden" value="<?php echo $r['password'] ?>" name="oldpass" />
                      <input type="password" class="form-control" name="newpass" id="pass" placeholder="كلمة المرور"  minlength="6">
                </div>
            </td>
            
        </tr>

        <tr>
            <td colspan="2">
                <div>
                      <label>وضع صورة شخصية</label><span style="color:red;">*</span><br>
                      <label for="image" class="btn btn-info btn-sm">اختيار صورة</label>
                      <input type="file" class="form-control" name="image" id="image" accept="image/png, image/jpeg" style="display:none">
                </div>
            </td>

        </tr>
        
        <tr>
            <td colspan="2">
            <br>
                <button type="submet" class="btn btn-info btn-lg btn-block"><i class="fas fa-save"></i> حفظ</button>
            </td>
        </tr>

        

    </table>
</form>

<?php


}else if($do == "updateProfile"){ // is do = update
    include "includes/connect.php";
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $errs = array();
        $f_name     = trim($_POST["f_name"]);
        $l_name     = trim($_POST["l_name"]);
        $img        =$_FILES['image'];
        
        // IMG INFO
        $img_name = $img['name'];
        $img_size = $img['size'];
        $img_temp = $img['tmp_name'];
        
        
        if(!$img['error'] == 4){

            $exe_name = explode('.',$img_name);
            $exe_name = strtolower(end($exe_name));
            $allwed = array('png','jpg');
            if(!in_array($exe_name,$allwed)){
                $errs[] = 'عفواً انت لم تختار صورة بعد';
            }
            if($img_size > 3000000){
                $errs[] = 'عفواً ان حجم الصورة كبير جداً';
            }
            if(empty($errs)){
                $new_name = uniqid('',false).'.'.$exe_name;
                echo $new_name;
                move_uploaded_file($img['tmp_name'],'uplouds/image_profile/'.$new_name);
            }else{
                foreach($errs as $err){
                    echo $err.'<br />';
                }
            }
        }else{
            $new_name = $_SESSION['image'];
        }


        // password hhhhhhhhhhhhhhhhhh
        if(empty($_POST['newpass'])){
            $pass =$_POST['oldpass'];
        }else{
            if(strlen($_POST['newpass']) < 6){
                $errs[] = "عفواً ، الحد الأدنى لكلمة المرور 6 احرف";
            }else{
                $pass =sha1($_POST['newpass']);
            }
            
        }

       
        if(empty($f_name)){
            $errs[] = "عفواً ، الأسم الأول فارغ";
        }
        if(empty($l_name)){
            $errs[] = "عفواً ، الأسم الثانى فارغ";
        }

        if(strlen($f_name) < 3){
            $errs[] = "عفواً ، الحد الأدنى لللأسم الأول 3 احرف";
        }

        if(strlen($l_name) < 3){
            $errs[] = "عفواً ، الحد الأدنى لللأسم الثانى 3 احرف";
        }

        


        if(empty($errs)){
            
            include "includes/connect.php";
            
            $stmt = $con->prepare("UPDATE users SET f_name=:f_name,l_name=:l_name,password=:password,image=:image WHERE u_name=:u_name");
            

            
            $stmt->execute(array(
                "f_name"        => $f_name,
                "l_name"        => $l_name,
                "password"      => $pass,
                "image"         => $new_name,
                "u_name"        => $_SESSION['u_name']

            ));
            $_SESSION['u_name'] = $_SESSION['u_name'];
            $_SESSION['f_name'] = $f_name;
            $_SESSION['l_name'] = $l_name;
            
            $stmt = $con->prepare("SELECT * FROM users WHERE u_name = ?");
            $stmt->execute(array($_SESSION['u_name']));
            $r = $stmt->fetch();
            $_SESSION['image'] = $r['image'];

            header("Location: index.php");

        }else{
            echo '<br>';
            foreach ($errs as $err) {
                
                echo '<center>';
                echo '<div class="alert alert-danger">'.$err.'</div>';
                echo '</center>';
            }

        }


    }else{ // if not get with post method go to add user
        header("Location: users.php?do=add");
    }

}else{ // is do != insert or add
    header("Location: 404.php");
}


echo "</div>";
include "includes/footer.php";
ob_end_flush();
?>
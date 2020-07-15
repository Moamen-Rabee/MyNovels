<?php
ob_start();
session_start();
include "includes/header.php";
include "includes/connect.php";
echo "<div class='container'>";


if(!isset($_GET["do"])){ // do not found set add
    $do = "add";
}else{
    $do = $_GET["do"];
}


if($do == "add"){ // is do == add
    // add user code

    if(!isset($_SESSION['u_name'])){ // if user is log in go to home page
        header("Location: index.php");
    }

    $stmt = $con->prepare("SELECT status FROM users WHERE u_name = ?");
    $stmt->execute(array($_SESSION['u_name']));
    $re = $stmt->fetch();
    if($re['status'] == "0"){
        header("Location: user_not_active.php");
    }
    
?>
<form style="max-width:100%;" class="col-lg-12 col-md-12 col-sm-12 form_add_user" method="post" action="?do=insert"  enctype="multipart/form-data">
    <h1>كتابة رواية جديدة</h1>
    <table>

        <tr>
            <td colspan="2">
                <div>
                      <label for="novel_title">عنوان الرواية</label><span style="color:red;">*</span>
                      <input type="text" class="form-control" name="novel_title" id="novel_title" placeholder="عنوان الرواية" required="required" minlength="3">
                </div>
            </td>

        </tr>

        <tr>
            <td>
                <div>
                      <label for="novel_contant">محتوى الرواية</label><span style="color:red;">*</span>
                      <!--<textarea  class="form-control" rows="10" name="novel_contant" id="novel_contant" placeholder="محتوى الرواية" required="required" minlength="10"></textarea>-->
                      <textarea  class="form-control jqte-test" name="novel_contant" id="novel_contant"  required="required" minlength="10"></textarea>
                      
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <div><br>
                      <label>اختيار صورة للرواية</label><span style="color:red;">*</span><br>
                      <label for="image" class="btn btn-info btn-sm">اختيار صورة</label>
                      <input type="file" class="form-control" name="novel_image" id="image" accept="image/png, image/jpeg" style="display:none">
                </div>
            </td>

        </tr>


        <tr>
            <td colspan="2">
                <button type="submet" class="btn btn-info btn-lg btn-block"><i class="fas fa-paper-plane"></i>نشر الرواية</button>
            </td>
        </tr>



    </table>
</form>

<?php

    
}else if($do == "insert"){ // is do == insert
    if(!isset($_SESSION['u_name'])){ // if user is log in go to home page
        header("Location: index.php");
    }
    // insert user code
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $errs = array();
        $novel_title    = trim(addslashes($_POST['novel_title']));
        $novel_contant  = trim(addslashes($_POST['novel_contant']));
        $img            = $_FILES['novel_image'];

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
                move_uploaded_file($img['tmp_name'],'uplouds/novels_images/'.$new_name);
            }
        }else{
            $new_name = "main_image.jpg";
        }

        $year = date('Y');
        $day = date('d');
        $manth = date('m');
        $date = $year."-".$manth."-".$day;


        if(empty($novel_title)){
            $errs[] = "عفواً ، لا يمكن حفظ الرواية بدون عنوان";
        }
        if(empty($novel_contant)){
            $errs[] = "عفواً ، لا يمكن حفظ الرواية بدون محتوى";
        }
        if(strlen($novel_title) < 3){
            $errs[] = "عفواً ، الحد الأدنى لعنوان الرواية 3 حروف";
        }
        if(strlen($novel_contant) < 10){
            $errs[] = "عفواً ، الحد الأدنى لحنوى الرواية 10 حروف";
        }

        if(empty($errs)){
            $stmt = $con->prepare("INSERT INTO novels (novel_title,novel_author,novel_image,novel_contant,novel_likes,novle_Published) 
                                VALUES (:novel_title,:novel_author,:novel_image,:novel_contant,:novel_likes,:novle_Published)");

            $stmt->execute(array(
                'novel_title'       => $novel_title,
                'novel_author'      => $_SESSION['u_name'],
                'novel_image'       => $new_name,
                'novel_contant'     => $novel_contant,
                'novel_likes'       => 0,
                'novle_Published'   => $date
            ));

            header("Location: index.php");
        }else{
            foreach($errs as $err){
                echo "<br><div class='alert alert-danger text-center'>".$err.'</div>';
            }
        }

        


    }else{ // if not get with post method go to add user
        header("Location: novels.php?do=add");
    }




}else if($do == "delete"){// is do== delete
        $id = $_GET['id'];
        $stmt=$con->prepare("SELECT * FROM novels WHERE id = ?");
        $stmt->execute(array($id));
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        
    if(is_numeric($_GET['id'])){
        
        if($count > 0){

            if(strtolower($result['novel_author']) == strtolower($_SESSION['u_name'])){
                
                // delete code 
                $stmt=$con->prepare("DELETE FROM novels WHERE id = ?");
                $stmt->execute(array($id));

                $stmt=$con->prepare("DELETE FROM likes WHERE novel_id = ?");
                $stmt->execute(array($id));

                $stmt=$con->prepare("DELETE FROM comments WHERE novel_id = ?");
                $stmt->execute(array($id));


                header("Location: mynovels.php");

                
                
            
            }else{
                header("Location: 404.php");
            }
            
        }else{
            header("Location: 404.php");
        }
    }else{
        header("Location: 404.php");
    }




}else if($do == "edit"){
    // edit form
    if(!isset($_SESSION['u_name'])){ // if user is log in go to home page
        header("Location: index.php");
    }

    $stmt = $con->prepare("SELECT status FROM users WHERE u_name = ?");
    $stmt->execute(array($_SESSION['u_name']));
    $re = $stmt->fetch();
    if($re['status'] == "0"){
        header("Location: user_not_active.php");
    }
    
    $id = $_GET['id'];
        $stmt=$con->prepare("SELECT * FROM novels WHERE id = ?");
        $stmt->execute(array($id));
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        
    if(is_numeric($_GET['id'])){
        
        if($count > 0){
            if(strtolower($result['novel_author']) == strtolower($_SESSION['u_name'])){
                
                
?>
<form style="max-width:100%;" class="col-lg-12 col-md-12 col-sm-12 form_add_user" method="post" action="?do=update&id=<?php echo $_GET['id']; ?>"  enctype="multipart/form-data">
    <h1>تعديل رواية </h1>
    <table>

        <tr>
            <td colspan="2">
                <div>
                      <label for="novel_title">عنوان الرواية</label><span style="color:red;">*</span>
                      <input type="text" value="<?php echo $result['novel_title']; ?>" class="form-control" name="novel_title" id="novel_title" placeholder="عنوان الرواية" required="required" minlength="3">
                </div>
            </td>

        </tr>

        <tr>
            <td>
                <div>
                      <label for="novel_contant">محتوى الرواية</label><span style="color:red;">*</span>
                      <!--<textarea  class="form-control" rows="10" name="novel_contant" id="novel_contant" placeholder="محتوى الرواية" required="required" minlength="10"></textarea>-->
                      <textarea  class="form-control jqte-test" name="novel_contant" id="novel_contant"  required="required" minlength="10">
                        <?php echo $result['novel_contant']; ?>
                      </textarea>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <div><br>
                      <label>اختيار صورة للرواية</label><span style="color:red;">*</span><br>
                      <label for="image" class="btn btn-info btn-sm">اختيار صورة</label>
                      <input type="hidden" value="<?php echo $result['novel_image']; ?>"  name="old_img" />
                      <input  type="file" class="form-control" name="novel_image" id="image" accept="image/png, image/jpeg" style="display:none">
                </div>
            </td>

        </tr>


        <tr>
            <td colspan="2">
                <button type="submet" class="btn btn-info btn-lg btn-block"><i class="fas fa-save"></i>حفظ الرواية</button>
            </td>
        </tr>



    </table>
</form>

<?php


            }else{
                header("Location: 404.php");
            }
            
        }else{
            header("Location: 404.php");
        }
    }else{
        header("Location: 404.php");
    }




}else if($do == "update"){






    if(!isset($_SESSION['u_name'])){ // if user is log in go to home page
        header("Location: index.php");
    }
    // insert user code
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(!isset($_SESSION['u_name'])){ // if user is log in go to home page
            header("Location: index.php");
        }
        
        $id = $_GET['id'];
            $stmt=$con->prepare("SELECT * FROM novels WHERE id = ?");
            $stmt->execute(array($id));
            $result = $stmt->fetch();
            $count = $stmt->rowCount();
            
        if(is_numeric($_GET['id'])){
            
            if($count > 0){
                if(strtolower($result['novel_author']) == strtolower($_SESSION['u_name'])){

                    $errs = array();
        $novel_title    = trim(addslashes($_POST['novel_title']));
        $novel_contant  = trim(addslashes($_POST['novel_contant']));
        $old_img  = $_POST['old_img'];
        $img            = $_FILES['novel_image'];

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
                move_uploaded_file($img['tmp_name'],'uplouds/novels_images/'.$new_name);
            }
        }else{
            $new_name = $old_img;
        }

        $year = date('Y');
        $day = date('d');
        $manth = date('m');
        $date = $year."-".$manth."-".$day;


        if(empty($novel_title)){
            $errs[] = "عفواً ، لا يمكن حفظ الرواية بدون عنوان";
        }
        if(empty($novel_contant)){
            $errs[] = "عفواً ، لا يمكن حفظ الرواية بدون محتوى";
        }
        if(strlen($novel_title) < 3){
            $errs[] = "عفواً ، الحد الأدنى لعنوان الرواية 3 حروف";
        }
        if(strlen($novel_contant) < 10){
            $errs[] = "عفواً ، الحد الأدنى لحنوى الرواية 10 حروف";
        }

        if(empty($errs)){
            $stmt = $con->prepare("UPDATE novels SET novel_title=:novel_title,novel_image=:novel_image,novel_contant=:novel_contant WHERE id=:id");

            $stmt->execute(array(
                'novel_title'       => $novel_title,
                'novel_image'       => $new_name,
                'novel_contant'     => $novel_contant,
                'id'                => $id
            ));

            header("Location: mynovels.php");
        }else{
            foreach($errs as $err){
                echo "<br><div class='alert alert-danger text-center'>".$err.'</div>';
            }
        }


                }
            }
        }

        
        

        


    }else{ // if not get with post method go to add user
        header("Location: novels.php?do=add");
    }




}else{ // is do != insert or add
    header("Location: 404.php");
}


echo "</div>";
include "includes/footer.php";
ob_end_flush();
?>
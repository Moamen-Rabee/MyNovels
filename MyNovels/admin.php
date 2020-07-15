<?php
ob_start();
session_start();
include "includes/header.php";

include "includes/connect.php";

if(!isset($_GET["do"])){ // do not found set add
    $do = "index";
}else{
    $do = $_GET["do"];
}

if($do == "index"){
    include "includes/admin_navbar.php";

    if(!isset($_SESSION['admin_u_name'])){
        header("Location: admin.php?do=login_form");
    }

    $stmt = $con->prepare('SELECT * FROM users');
    $stmt->execute(array());
    $count_users = $stmt->rowCount();

    $stmt = $con->prepare('SELECT * FROM novels');
    $stmt->execute(array());
    $count_novels = $stmt->rowCount();

    $stmt = $con->prepare('SELECT * FROM admins');
    $stmt->execute(array());
    $count_admins = $stmt->rowCount();

    ?>
      <!--Start Contant-->
    <div class='container'>
    <br>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                <div class="box_index_admin">
                    <h5>الروايات</h5>
                    <div class="logo_index_admin">
                    <i class="fas fas fa-book"></i>
                    </div>
                    <h4>
                        <?php echo $count_novels; ?>
                    </h4>
                </div>
            </div>


            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                <div class="box_index_admin">
                    <h5>المشرفين</h5>
                    <div class="logo_index_admin">
                    <i class="fas fas fa-users-cog"></i>
                    </div>
                    <h4>
                        <?php echo $count_admins-1; ?>
                    </h4>
                </div>
            </div>


            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
                <div class="box_index_admin">
                    <h5>المستخدمين</h5>
                    <div class="logo_index_admin">
                    <i class="fas fas fa-users"></i>
                    </div>
                    <h4>
                        <?php echo $count_users; ?>
                    </h4>
                </div>
            </div>
        </div> <!-- end row-->
    </div> <!--End Contaner-->
    <?php

}else if($do == "login_form"){
    
?>
<div class='container'>
    <form class="col-lg-6 col-md-7 col-sm-12 form_add_user" method="post" action="?do=login_proc">
        <h1>تسجيل الدخول</h1>
        <table>
    
            <tr>
                <td colspan="2">
                    <div>
                          <label for="u_name">اسم المستخدم</label><span style="color:red;">*</span>
                          <input type="text" class="form-control" name="admin_u_name" id="u_name" placeholder="اسم المستخدم" required="required" minlength="6">
                    </div>
                </td>
    
            </tr>
    
            <tr>
                <td colspan="2">
                    <div>
                          <label for="pass">كلمة المرور</label><span style="color:red;">*</span>
                          <input type="password" class="form-control" name="admin_password" id="pass" placeholder="كلمة المرور" required="required" minlength="6">
                    </div>
                </td>
            </tr>
    
            <tr>
                <td colspan="2">
                    <button type="submet" class="btn btn-danger btn-lg btn-block"><i class="fas fa-sign-in-alt"></i>تسجيل الدخول</button>
                </td>
            </tr>
        </table><br>
    </form>
</div>
<?php

}else if($do == "login_proc"){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $admin_u_name     = trim($_POST["admin_u_name"]);
        $admin_pass       = $_POST["admin_password"];

        $errs = array();
        if(empty($admin_u_name)){
            $errs[] = "عفواً ، اسم المستخدم فارغ";
        }
        if(strlen($admin_u_name) < 6){
            $errs[] = "عفواً ، الحد الأدنى لأسم المستخدم 6 أحرف";
        }
        if(empty($admin_pass)){
            $errs[] = "عفواً ، كلمة المرور فارغة";
        }
        if(strlen($admin_pass) < 6){
            $errs[] = "عفواً ، الحد الأدنى لكلمة المرور 6 أحرف";
        }

        if(empty($errs)){
            
            
            
            $stmt = $con->prepare("SELECT * FROM admins WHERE admin_u_name = ? AND admin_pass = ?");
            $stmt->execute(array($admin_u_name,sha1($admin_pass)));

            if($stmt->rowCount() > 0){
                $_SESSION['admin_u_name'] = $admin_u_name;
                $r = $stmt->fetch();
                $_SESSION['admin_f_name'] = $r['admin_f_name'];
                $_SESSION['admin_l_name'] = $r['admin_l_name'];
                $_SESSION['admin_all'] = $r['admin_all'];
                header("Location: admin.php");
            }else{

                header("Location: admin.php?do=login_form");

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

}else if($do == 'all_novels'){
        include "includes/admin_navbar.php";

        $stmt=$con->prepare("SELECT * FROM novels ORDER BY id DESC ");
        $stmt->execute();
        $novels = $stmt->fetchAll();
        
        ?>
        <div class='container'>
            <div id="novels_contant_admin">
        <h1 class="heading_all_novels">كل الروايات</h1>
        <div style="margin-bottom: 10px;">
        
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" class="form-control" id="search_q_novel" name="search_q_novel" placeholder="اكتب للبحث .." required="required">                 
                    </td>
                    <td>
                        <button onclick="search_for_novel('admin.php?do=search_novels&novel_name=','novels_contant_admin');" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
        </table>
        
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm text-center table_novels">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">الصورة</th>
                        <th scope="col">العنوان</th>
                        <th scope="col">المؤلف</th>
                        <th scope="col">عدد الأعجابات</th>
                        <th scope="col">تاريخ النشر</th>
                        <th scope="col">التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
        <?php
        foreach($novels as $novel){
            ?>
                        <tr>
                        <th style="vertical-align: middle;" scope="row"><?php echo $novel['id']; ?></th>
                        <td>
                            <img class="img_novels" src="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" alt="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" />
                        </td>
                        <td style="vertical-align: middle;"><?php echo $novel['novel_title']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $novel['novel_author']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $novel['novel_likes']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $novel['novle_Published']; ?></td>
                        <td style="vertical-align: middle;">
                        
                        <a href="view_novel.php?id=<?php echo $novel['id']; ?>" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-book"></i></a>
                        <button onclick="closedelete('admin.php?do=delete_novels&', <?php echo $novel['id']; ?>);" class="btn  btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </td>
                        </tr>

            <?php
        }
        ?>
                        </tbody>
                </table>
            </div>
            <div>
        </div>
                
    <?php
    
}else if($do == 'search_novels'){

    $word = $_GET['novel_name'];
    $word_s = filter_var($word, FILTER_SANITIZE_STRING);

    

        $stmt=$con->prepare("SELECT * FROM novels WHERE novel_title LIKE '%$word_s%' OR novel_author LIKE '%$word_s%' ORDER BY id DESC ");
        $stmt->execute();
        $novels = $stmt->fetchAll();
        
        ?>
        <div class='container'>
            <div id="novels_contant_admin">
        <h1 class="heading_all_novels">كل الروايات</h1>
        <div style="margin-bottom: 10px;">
        
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" class="form-control" id="search_q_novel" name="search_q_novel" placeholder="اكتب للبحث .." required="required">                 
                    </td>
                    <td>
                        <button onclick="search_for_novel();" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
        </table>
        
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm text-center table_novels">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">الصورة</th>
                        <th scope="col">العنوان</th>
                        <th scope="col">المؤلف</th>
                        <th scope="col">عدد الأعجابات</th>
                        <th scope="col">تاريخ النشر</th>
                        <th scope="col">التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
        <?php
        foreach($novels as $novel){
            ?>
                        <tr>
                        <th style="vertical-align: middle;" scope="row"><?php echo $novel['id']; ?></th>
                        <td>
                            <img class="img_novels" src="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" alt="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" />
                        </td>
                        <td style="vertical-align: middle;"><?php echo $novel['novel_title']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $novel['novel_author']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $novel['novel_likes']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $novel['novle_Published']; ?></td>
                        <td style="vertical-align: middle;">
                        
                        <a href="view_novel.php?id=<?php echo $novel['id']; ?>" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-book"></i></a>
                        <button onclick="closedelete('admin.php?do=delete_novels&', <?php echo $novel['id']; ?>);" class="btn  btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </td>
                        </tr>

            <?php
        }
        ?>
                        </tbody>
                </table>
            </div>
            <div>
        </div>
                
    <?php


    
}else if($do == 'delete_novels'){
    
    $id = $_GET['id'];
    $stmt=$con->prepare("SELECT * FROM novels WHERE id = ?");
    $stmt->execute(array($id));
    $count = $stmt->rowCount();
    
    if(is_numeric($_GET['id'])){
        
        if($count > 0){

            if(isset($_SESSION['admin_u_name'])){
                
                // delete code 
                $stmt=$con->prepare("DELETE FROM novels WHERE id = ?");
                $stmt->execute(array($id));

                $stmt=$con->prepare("DELETE FROM likes WHERE novel_id = ?");
                $stmt->execute(array($id));

                $stmt=$con->prepare("DELETE FROM comments WHERE novel_id = ?");
                $stmt->execute(array($id));
                header("Location: admin.php?do=all_novels");
            
            }else{
                header("Location: 404.php");
            }

        }else{
            header("Location: 404.php");
        }

    }else{
        header("Location: 404.php");
    }






}else if($do == 'all_admins'){
    if(!$_SESSION['admin_all'] == '1'){
        header("Location: admin.php");
    }

    include "includes/admin_navbar.php";

        $stmt=$con->prepare("SELECT * FROM admins WHERE admin_all = 0 ORDER BY admin_id DESC ");
        $stmt->execute();
        $admins = $stmt->fetchAll();
        
        ?>
        <div class='container'>
            <div id="admin_contant_admin">
        <h1 class="heading_all_novels">المشرفين</h1>
        <div style="margin-bottom: 10px;">
        
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" class="form-control" id="search_q_novel" name="search_q_novel" placeholder="اكتب للبحث .." required="required">                 
                    </td>
                    <td>
                        <button onclick="search_for_novel('admin.php?do=search_admin&name=','admin_contant_admin');" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
            </table><br>
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <a href="admin.php?do=add_new_admin" class="btn btn-success btn-block"><i class="fas fa-plus"></i>اضافة مشرف جديد</a>
                    </td>

                </tr>
            </table>
        
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm text-center table_novels">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">الأسم</th>
                        <th scope="col">اسم المستخدم</th>
                        <th scope="col">التحكم</th>
                        
                        </tr>
                    </thead>
                    <tbody>
        <?php
        foreach($admins as $admin){
            ?>
                        <tr>
                        <th style="vertical-align: middle;" scope="row"><?php echo $admin['admin_id']; ?></th>
                        <td style="vertical-align: middle;"><?php echo $admin['admin_f_name'].' '.$admin['admin_l_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $admin['admin_u_name']; ?></td>
                        <td style="vertical-align: middle;">
                        <button onclick="closedelete('admin.php?do=delete_admin&', <?php echo $admin['admin_id']; ?>);" class="btn  btn-sm btn-danger"><i class="fas fa-trash-alt"></i>حذف</button>
                        </td>
                        </tr>

            <?php
        }
        ?>
                        </tbody>
                </table>
            </div>
            <div>
        </div>
                
    <?php





}else if($do == 'search_admin'){

    $word = $_GET['name'];
    $word_s = filter_var($word, FILTER_SANITIZE_STRING);

    

        $stmt=$con->prepare("SELECT * FROM admins WHERE admin_f_name LIKE '%$word_s%' OR admin_l_name LIKE '%$word_s%' AND admin_all = 0 ORDER BY admin_id DESC ");
        $stmt->execute();
        $admins = $stmt->fetchAll();
        
        ?>
        
            <div id="admin_contant_admin">
        <h1 class="heading_all_novels">المشرفين</h1>
        <div style="margin-bottom: 10px;">
        
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" class="form-control" id="search_q_novel" name="search_q_novel" placeholder="اكتب للبحث .." required="required">                 
                    </td>
                    <td>
                        <button onclick="search_for_novel('admin.php?do=search_admin&name=','admin_contant_admin');" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
            </table><br>
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <a href="admin.php?do=add_new_admin" class="btn btn-success btn-block"><i class="fas fa-plus"></i>اضافة مشرف جديد</a>
                    </td>

                </tr>
            </table>
        
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm text-center table_novels">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">الأسم</th>
                        <th scope="col">اسم المستخدم</th>
                        <th scope="col">التحكم</th>
                        
                        </tr>
                    </thead>
                    <tbody>
        <?php
        foreach($admins as $admin){
            ?>
                        <tr>
                        <th style="vertical-align: middle;" scope="row"><?php echo $admin['admin_id']; ?></th>
                        <td style="vertical-align: middle;"><?php echo $admin['admin_f_name'].' '.$admin['admin_l_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $admin['admin_u_name']; ?></td>
                        <td style="vertical-align: middle;">
                        <button onclick="closedelete('admin.php?do=delete_admin&', <?php echo $admin['admin_id']; ?>);" class="btn  btn-sm btn-danger"><i class="fas fa-trash-alt"></i>حذف</button>
                        </td>
                        </tr>

            <?php
        }
        ?>
                        </tbody>
                </table>
            </div>
            <div>
        
                
    <?php





}else if($do == 'delete_admin'){
    
    $id = $_GET['id'];
    $stmt=$con->prepare("SELECT * FROM admins WHERE admin_id = ?");
    $stmt->execute(array($id));
    $count = $stmt->rowCount();
    
    if(is_numeric($_GET['id'])){
        
        if($count > 0){

            if(isset($_SESSION['admin_u_name'])){
                
                // delete code 
                $stmt=$con->prepare("DELETE FROM admins WHERE admin_id = ?");
                $stmt->execute(array($id));

                header("Location: admin.php?do=all_admins");
            
            }else{
                header("Location: 404.php");
            }

        }else{
            header("Location: 404.php");
        }

    }else{
        header("Location: 404.php");
    }


}else if($do == 'add_new_admin'){

    ?>
    <div class='container'>
        <form class="col-lg-6 col-md-8 col-sm-12 form_add_user" method="post" action="?do=insert_admin">
            <h1>اضافة مشرف جديد</h1>
            <table>
        
                <tr>
                    <td>
                        <div>
                              <label for="f_name">اسم الأول</label><span style="color:red;">*</span>
                              <input type="text" class="form-control" name="admin_f_name" id="f_name" placeholder="اسمك" required="required" minlength="2">
                        </div>
                    </td>

                    <td>
                        <div>
                              <label for="l_name">الأسم الثانى</label><span style="color:red;">*</span>
                              <input type="text" class="form-control" name="admin_l_name" id="l_name" placeholder="اسم الأب" required="required" minlength="2">
                        </div>
                    </td>
        
                </tr>

                <tr>
                    <td colspan="2">
                        <div>
                              <label for="u_name">اسم المستخدم</label><span style="color:red;">*</span>
                              <input type="text" class="form-control" name="admin_u_name" id="u_name" placeholder="اسم المستخدم" required="required" minlength="6">
                        </div>
                    </td>
        
                </tr>
        
                <tr>
                    <td colspan="2">
                        <div>
                              <label for="pass">كلمة المرور</label><span style="color:red;">*</span>
                              <input type="password" class="form-control" name="admin_password" id="pass" placeholder="كلمة المرور" required="required" minlength="6">
                        </div>
                    </td>
                </tr>
        
                <tr>
                    <td colspan="2">
                        <button type="submet" class="btn btn-danger btn-lg btn-block"><i class="fas fa-plus"></i>اضافة</button>
                    </td>
                </tr>
            </table><br>
        </form>
    </div>
    <?php


}else if($do == 'insert_admin'){

    echo "<div class='container'>";
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errs = array();
        $admin_f_name   = strip_tags($_POST['admin_f_name']);
        $admin_l_name   = strip_tags($_POST['admin_l_name']);
        $admin_u_name   = strip_tags($_POST['admin_u_name']);
        $admin_password = strip_tags($_POST['admin_password']);

        if(empty($admin_f_name)){
            $errs[] = "عفواً لا يمكن حفظ الأسم فارغ";
        }

        if(empty($admin_l_name)){
            $errs[] = "عفواً لا يمكن حفظ الأسم الثانى فارغ";
        }

        if(empty($admin_u_name)){
            $errs[] = "عفواً لا يمكن حفظ اسم المستخدم فارغ";
        }

        if(empty($admin_password)){
            $errs[] = "عفواً لا يمكن حفظ كلمة المرور فارغة";
        }

        if(strlen($admin_f_name) < 2){
            $errs[] = "عفواً الأسم الأول صغير جدا";
        }

        if(strlen($admin_l_name) < 2){
            $errs[] = "عفواً الأسم الثانى صغير جدا";
        }

        if(strlen($admin_u_name) < 6){
            $errs[] = "عفواً اسم المستخدم يجب أن يكون اكبر من 6 احرف";
        }

        if(strlen($admin_password) < 6){
            $errs[] = "عفواً يجب ان تكون كلمة المرور أكبر من 6 احرف";
        }


        if(empty($errs)){
            

            $stmt = $con->prepare("INSERT INTO admins (admin_f_name,admin_l_name,admin_u_name,admin_pass,admin_all) VALUES (:admin_f_name,:admin_l_name,:admin_u_name,:admin_pass,:admin_all)");
            $stmt->execute(array(
                'admin_f_name'  => $admin_f_name,
                'admin_l_name'  => $admin_l_name,
                'admin_u_name'  => $admin_u_name,
                'admin_pass'    => sha1($admin_password),
                'admin_all'     => false
            ));

            header("Location: admin.php?do=all_admins");



        }else{
            foreach($errs as $err){
                echo "<br><div class='alert alert-danger text-center'>".$err.'</div>';
            }
        }







    }else{
        header("Location: admin.php?do=add_new_admin");
    }
    echo "</div>";






}else if($do == "all_users"){

    include "includes/admin_navbar.php";

        $stmt=$con->prepare("SELECT * FROM users ORDER BY id DESC ");
        $stmt->execute();
        $users = $stmt->fetchAll();
        
        ?>
        <div class='container'>
            <div id="admin_contant_admin">
        <h1 class="heading_all_novels">المستخدمين</h1>
        <div style="margin-bottom: 10px;">
        
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" class="form-control" id="search_q_novel" name="search_q_novel" placeholder="اكتب للبحث .." required="required">                 
                    </td>
                    <td>
                        <button onclick="search_for_novel('admin.php?do=search_in_users&name=','admin_contant_admin');" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
            </table>
        
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm text-center table_novels">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">الصورة</th>
                        <th scope="col">الأسم</th>
                        <th scope="col">اسم المستخدم</th>
                        <th scope="col">تاريخ الأضافة</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التحكم</th>
                        
                        </tr>
                    </thead>
                    <tbody>
        <?php
        foreach($users as $user){
            ?>
                        <tr>
                        <th style="vertical-align: middle;" scope="row"><?php echo $user['id']; ?></th>
                        <td style="vertical-align: middle;">
                        
                        <img class="img_novels" src="uplouds/image_profile/<?php echo $user['image']; ?>" alt="uplouds/image_profile/<?php echo $user['image']; ?>" />
                        </td>
                        <td style="vertical-align: middle;"><?php echo $user['f_name'].' '.$user['l_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $user['u_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $user['add_date']; ?></td>
                        <td style="vertical-align: middle;"><?php 

                        if($user['status'] == true){
                            echo '<span style="color:green; font-weight: bold;">مفعل</span>';
                        }else{
                            echo '<span style="color:red; font-weight: bold;">غير مفعل</span>';
                        }
                        
                        ?></td>
                        <td style="vertical-align: middle;">
                        <?php
                        if($user['status'] == true){
                            ?>
                        <button onclick="active_user('admin.php?do=dis_active_user&id=<?php echo $user['id']; ?>','admin_contant_admin');" class="btn btn-sm btn-danger"><i class="fas fa-user-times"></i>الغاء التفعيل</button>
                            <?php
                        }else{
                            ?>
                        <button onclick="active_user('admin.php?do=active_user&id=<?php echo $user['id']; ?>','admin_contant_admin');" class="btn btn-sm btn-primary"><i class="fas fa-user"></i>التفعيل</button>
                            <?php
                        }
                        ?>
                        
                        </td>
                        
                        </tr>

            <?php
        }
        ?>
                        </tbody>
                </table>
            </div>
            <div>
        </div>
                
    <?php





}else if($do == "search_in_users"){

        $word = $_GET['name'];
        $word_s = filter_var($word, FILTER_SANITIZE_STRING);

        $stmt=$con->prepare("SELECT * FROM users WHERE f_name LIKE '%$word_s%' OR l_name LIKE '%$word_s%' OR u_name LIKE '%$word_s%'  ORDER BY id DESC ");
        $stmt->execute();
        $users = $stmt->fetchAll();
        
        ?>
        
            <div id="admin_contant_admin">
        <h1 class="heading_all_novels">المستخدمين</h1>
        <div style="margin-bottom: 10px;">
        
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" class="form-control" id="search_q_novel" name="search_q_novel" placeholder="اكتب للبحث .." required="required">                 
                    </td>
                    <td>
                        <button onclick="search_for_novel('admin.php?do=search_in_users&name=','admin_contant_admin');" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
            </table>
        
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm text-center table_novels">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">الصورة</th>
                        <th scope="col">الأسم</th>
                        <th scope="col">اسم المستخدم</th>
                        <th scope="col">تاريخ الأضافة</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التحكم</th>
                        
                        </tr>
                    </thead>
                    <tbody>
        <?php
        foreach($users as $user){
            ?>
                        <tr>
                        <th style="vertical-align: middle;" scope="row"><?php echo $user['id']; ?></th>
                        <td style="vertical-align: middle;">
                        
                        <img class="img_novels" src="uplouds/image_profile/<?php echo $user['image']; ?>" alt="uplouds/image_profile/<?php echo $user['image']; ?>" />
                        </td>
                        <td style="vertical-align: middle;"><?php echo $user['f_name'].' '.$user['l_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $user['u_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $user['add_date']; ?></td>
                        <td style="vertical-align: middle;"><?php 

                            if($user['status'] == true){
                                echo '<span style="color:green; font-weight: bold;">مفعل</span>';
                            }else{
                                echo '<span style="color:red; font-weight: bold;">غير مفعل</span>';
                            }
                        
                        ?></td>
                        <td style="vertical-align: middle;">
                        <?php
                        if($user['status'] == true){
                            ?>
                        <button onclick="active_user('admin.php?do=dis_active_user&id=<?php echo $user['id']; ?>','admin_contant_admin');" class="btn btn-sm btn-danger"><i class="fas fa-user-times"></i>الغاء التفعيل</button>
                            <?php
                        }else{
                            ?>
                        <button onclick="active_user('admin.php?do=active_user&id=<?php echo $user['id']; ?>','admin_contant_admin');" class="btn btn-sm btn-primary"><i class="fas fa-user"></i>التفعيل</button>
                            <?php
                        }
                        ?>
                        
                        </td>
                        
                        </tr>

            <?php
        }
        ?>
                        </tbody>
                </table>
            </div>
            <div>
        
                
    <?php









}else if($do == "active_user"){
    if($_SESSION['admin_all'] == '1'){
        if(is_numeric($_GET['id'])){

            $stmt = $con->prepare('UPDATE users SET status=1 WHERE id = ?');
            $stmt->execute(array($_GET['id']));

            $stmt=$con->prepare("SELECT * FROM users ORDER BY id DESC ");
            $stmt->execute();
            $users = $stmt->fetchAll();
            ?>




<h1 class="heading_all_novels">المستخدمين</h1>
        <div style="margin-bottom: 10px;">
        
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" class="form-control" id="search_q_novel" name="search_q_novel" placeholder="اكتب للبحث .." required="required">                 
                    </td>
                    <td>
                        <button onclick="search_for_novel('admin.php?do=search_in_users&name=','admin_contant_admin');" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
            </table>
        
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm text-center table_novels">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">الصورة</th>
                        <th scope="col">الأسم</th>
                        <th scope="col">اسم المستخدم</th>
                        <th scope="col">تاريخ الأضافة</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التحكم</th>
                        
                        </tr>
                    </thead>
                    <tbody>
        <?php
        foreach($users as $user){
            ?>
                        <tr>
                        <th style="vertical-align: middle;" scope="row"><?php echo $user['id']; ?></th>
                        <td style="vertical-align: middle;">
                        
                        <img class="img_novels" src="uplouds/image_profile/<?php echo $user['image']; ?>" alt="uplouds/image_profile/<?php echo $user['image']; ?>" />
                        </td>
                        <td style="vertical-align: middle;"><?php echo $user['f_name'].' '.$user['l_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $user['u_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $user['add_date']; ?></td>
                        <td style="vertical-align: middle;"><?php 

                        if($user['status'] == true){
                            echo '<span style="color:green; font-weight: bold;">مفعل</span>';
                        }else{
                            echo '<span style="color:red; font-weight: bold;">غير مفعل</span>';
                        }
                        
                        ?></td>
                        <td style="vertical-align: middle;">
                        <?php
                        if($user['status'] == true){
                            ?>
                        <button onclick="active_user('admin.php?do=dis_active_user&id=<?php echo $user['id']; ?>','admin_contant_admin');" class="btn btn-sm btn-danger"><i class="fas fa-user-times"></i>الغاء التفعيل</button>
                            <?php
                        }else{
                            ?>
                        <button onclick="active_user('admin.php?do=active_user&id=<?php echo $user['id']; ?>','admin_contant_admin');" class="btn btn-sm btn-primary"><i class="fas fa-user"></i>التفعيل</button>
                            <?php
                        }
                        ?>
                        
                        </td>
                        
                        </tr>

            <?php
        }
        ?>
                        </tbody>
                </table>
            </div>







            <?php
        }
    }else{
        header("Location: admin.php?do=all_users");
    }





}else if($do == "dis_active_user"){
    if($_SESSION['admin_all'] == '1'){
        if(is_numeric($_GET['id'])){

            $stmt = $con->prepare('UPDATE users SET status=0 WHERE id = ?');
            $stmt->execute(array($_GET['id']));

            $stmt=$con->prepare("SELECT * FROM users ORDER BY id DESC ");
            $stmt->execute();
            $users = $stmt->fetchAll();
            ?>




<h1 class="heading_all_novels">المستخدمين</h1>
        <div style="margin-bottom: 10px;">
        
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" class="form-control" id="search_q_novel" name="search_q_novel" placeholder="اكتب للبحث .." required="required">                 
                    </td>
                    <td>
                        <button onclick="search_for_novel('admin.php?do=search_in_users&name=','admin_contant_admin');" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                    </td>
                </tr>
            </table>
        
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm text-center table_novels">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">الصورة</th>
                        <th scope="col">الأسم</th>
                        <th scope="col">اسم المستخدم</th>
                        <th scope="col">تاريخ الأضافة</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">التحكم</th>
                        
                        </tr>
                    </thead>
                    <tbody>
        <?php
        foreach($users as $user){
            ?>
                        <tr>
                        <th style="vertical-align: middle;" scope="row"><?php echo $user['id']; ?></th>
                        <td style="vertical-align: middle;">
                        
                        <img class="img_novels" src="uplouds/image_profile/<?php echo $user['image']; ?>" alt="uplouds/image_profile/<?php echo $user['image']; ?>" />
                        </td>
                        <td style="vertical-align: middle;"><?php echo $user['f_name'].' '.$user['l_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $user['u_name']; ?></td>
                        <td style="vertical-align: middle;"><?php echo $user['add_date']; ?></td>
                        <td style="vertical-align: middle;"><?php 

                        if($user['status'] == true){
                            echo '<span style="color:green; font-weight: bold;">مفعل</span>';
                        }else{
                            echo '<span style="color:red; font-weight: bold;">غير مفعل</span>';
                        }
                        
                        ?></td>
                        <td style="vertical-align: middle;">
                        <?php
                        if($user['status'] == true){
                            ?>
                        <button onclick="active_user('admin.php?do=dis_active_user&id=<?php echo $user['id']; ?>','admin_contant_admin');" class="btn btn-sm btn-danger"><i class="fas fa-user-times"></i>الغاء التفعيل</button>
                            <?php
                        }else{
                            ?>
                        <button onclick="active_user('admin.php?do=active_user&id=<?php echo $user['id']; ?>','admin_contant_admin');" class="btn btn-sm btn-primary"><i class="fas fa-user"></i>التفعيل</button>
                            <?php
                        }
                        ?>
                        
                        </td>
                        
                        </tr>

            <?php
        }
        ?>
                        </tbody>
                </table>
            </div>







            <?php
        }
    }else{
        header("Location: admin.php?do=all_users");
    }


}else if($do == 'edit_admin'){



    $stmt = $con->prepare("SELECT * FROM admins WHERE admin_u_name = ?");
    $stmt->execute(array($_SESSION['admin_u_name']));
    $admin = $stmt->fetch();

    ?>
    <div class='container'>
        <form class="col-lg-6 col-md-8 col-sm-12 form_add_user" method="post" action="?do=update_admin">
            <h1>تعديل الحساب</h1>
            <table>
        
                <tr>
                    <td>
                        <div>
                              <label for="f_name">اسم الأول</label><span style="color:red;">*</span>
                              <input type="text" value="<?php echo $admin['admin_f_name'] ?>" class="form-control" name="admin_f_name" id="f_name" placeholder="اسمك" required="required" minlength="2">
                        </div>
                    </td>

                    <td>
                        <div>
                              <label for="l_name">الأسم الثانى</label><span style="color:red;">*</span>
                              <input type="text" value="<?php echo $admin['admin_l_name'] ?>" class="form-control" name="admin_l_name" id="l_name" placeholder="اسم الأب" required="required" minlength="2">
                        </div>
                    </td>
        
                </tr>

        
                <tr>
                    <td colspan="2">
                        <div>
                              <label for="pass">كلمة المرور</label><span style="color:red;">*</span>
                              <input type="hidden" value="<?php echo $admin['admin_pass'] ?>" name="old_pass" />
                              <input type="password" class="form-control" name="admin_password" id="pass" placeholder="كلمة المرور" >
                        </div>
                    </td>
                </tr>
        
                <tr>
                    <td colspan="2">
                        <button type="submet" class="btn btn-danger btn-lg btn-block"><i class="fas fa-save"></i>حفظ</button>
                    </td>
                </tr>
            </table><br>
        </form>
    </div>
    <?php




}else if($do == 'update_admin'){


    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errs = array();
        $admin_f_name   = strip_tags(trim($_POST['admin_f_name']));
        $admin_l_name   = strip_tags(trim($_POST['admin_l_name']));
        $admin_password = $_POST['admin_password'];
        $admin_old_pass = $_POST['old_pass'];


        if(empty($admin_f_name)){
            $errs[] = "عفواً لا يمكن حفظ الأسم فارغ";
        }

        if(empty($admin_l_name)){
            $errs[] = "عفواً لا يمكن حفظ الأسم الثانى فارغ";
        }


        if(strlen($admin_f_name) < 2){
            $errs[] = "عفواً الأسم الأول صغير جدا";
        }

        if(strlen($admin_l_name) < 2){
            $errs[] = "عفواً الأسم الثانى صغير جدا";
        }


        





        if(empty($admin_password)){
            $newpass = $admin_old_pass;
        }else{
            if(strlen($admin_password) < 6){
                $errs[] = "عفواً يجب ان تكون كلمة المرور أكبر من 6 احرف";
            }else{
                $newpass = sha1($admin_password);
            }
            
        }



        
        if(empty($errs)){
            

            $stmt = $con->prepare('UPDATE admins SET admin_f_name=:admin_f_name,admin_l_name=:admin_l_name,admin_pass=:admin_pass WHERE admin_u_name=:admin_u_name');
            $stmt->execute(array(
                'admin_f_name'  => $admin_f_name,
                'admin_l_name'  => $admin_l_name,
                'admin_pass'    => $newpass,
                'admin_u_name'  => $_SESSION['admin_u_name']
            ));

            $_SESSION['admin_f_name'] = $admin_f_name;
            $_SESSION['admin_l_name'] = $admin_l_name;
            header("Location: admin.php");



        }else{
            foreach($errs as $err){
                echo "<br><div class='alert alert-danger text-center'>".$err.'</div>';
            }
        }






    }





}else{
    header("Location: 404.php");
}



include "includes/footer.php";
ob_end_flush();
?>

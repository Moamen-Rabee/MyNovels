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

if(!is_numeric($_GET["id"])){
    header("Location: 404.php");
}

if($do == "add"){ // is do == add
    // add user code

    if(!isset($_SESSION['u_name'])){ // if user is log in go to home page
        header("Location: index.php");
    }
    
?>
<form style="max-width:100%;" class="col-lg-12 col-md-12 col-sm-12 form_add_user" method="post" action="?do=insert">
    <h1>اضافة تعليق</h1>
    <table>

        

        <tr>
            <td>
                <div>
                      <textarea  class="form-control" rows="7" name="comment" id="comment" placeholder="محتوى التعليق" required="required"></textarea>
                      <br>
                      <input type="hidden" value="<?php echo $_GET['id'] ?>" name="id_novel" />
                </div>
            </td>
        </tr>

        <br>

        <tr>
            <td colspan="2">
                <button type="submet" class="btn btn-info btn-lg btn-block"><i class="fas fa-paper-plane"></i>ارسال</button>
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
        include "includes/connect.php";
        $errs = array();
        
        $comment = $_POST['comment'];
        $id_novel = $_POST['id_novel'];
        $year = date('Y');
        $day = date('d');
        $manth = date('m');
        $date = $year."-".$manth."-".$day;

        

        if(empty($comment)){
            $errs[] = "عفواً ، لا يمكن حفظ التعليق بدون محتوى";
        }
        
        if(empty($errs)){
            $stmt = $con->prepare("INSERT INTO comments (novel_id,comment_user,comment,comment_date)
                             VALUES (:novel_id,:comment_user,:comment,:comment_date)");

            $stmt->execute(array(
                'novel_id'          => $id_novel,
                'comment_user'      => $_SESSION['u_name'],
                'comment'           => $comment,
                'comment_date'      => $date
            ));

            header("Location: view_novel.php?id=$id_novel");
        }

        


    }else{ // if not get with post method go to add user
        header("Location: comment.php?do=add");
    }




}else{ // is do != insert or add
    header("Location: 404.php");
}


echo "</div>";
include "includes/footer.php";
ob_end_flush();
?>
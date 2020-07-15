<?php
ob_start();
session_start();
include "includes/header.php";
include "includes/navbar.php";
include "includes/connect.php";

if(!isset($_SESSION['u_name'])){ // if user is log in go to home page
    header("Location: index.php");
}


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

      <!--Start Contant-->
    <div class="container">
    <div class="row">

        <?php
            $stmt=$con->prepare("SELECT * FROM novels WHERE novel_author = ? ORDER BY id DESC LIMIT 50");
            $stmt->execute(array($_SESSION['u_name']));
            $novels = $stmt->fetchAll();
            $novels_count = $stmt->rowCount();
            if($novels_count <= 0){
                echo "<br><div class='col-lg-12 alert alert-warning text-center'>"."عفواً انت لا تمتلك أى رواية"."</div>";
            }
            foreach($novels as $novel){


        ?>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 text-center box-mynovels mb-10">
            <div class="mynovel">
                <div class="div_img">
                    <img src="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" alt="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" />
                </div>
                <div>
                    <h6 class="mt-2" style="font-family: FontBold, Helvetica, sans-serif;">
                    <?php echo stripslashes($novel['novel_title']); ?>
                    </h6>
                    <hr>
                </div>
                <div>
                    <div><i class="far fa-calendar-alt"></i><span>
                    <?php echo $novel['novle_Published']; ?>
                    </div>

                    <div><i class="fas fa-thumbs-up"></i>
                    
                    <?php
                        
                        $stmt = $con->prepare('SELECT * FROM likes WHERE novel_id = ?');
                        $stmt->execute(array($novel['id']));
                        $count_likes = $stmt->rowCount();
                        echo $count_likes;
                        
                    ?>

                    </div>

                    <div><i class="far fa-comment"></i>
                        <?php
                            $stmt = $con->prepare('SELECT * FROM comments WHERE novel_id = ?');
                            $stmt->execute(array($novel['id']));
                            $comment_count = $stmt->rowCount();
                            echo $comment_count;
                        ?>
                    </div>
                    
                    <br>
                    <a href="view_novel.php?id=<?php echo $novel['id']; ?>" class="btn btn-block btn-sm btn-info"><i class="fas fa-eye"></i>عرض</a>
                    <a href="novels.php?do=edit&id=<?php echo $novel['id']; ?>" class="btn btn-block btn-sm btn-success"><i class="fas fa-edit"></i>تعديل</a>
                    <button onclick="closedelete('novels.php?do=delete&', <?php echo $novel['id']; ?>);" class="btn btn-block btn-sm btn-danger"><i class="fas fa-trash-alt"></i>حذف</button>
                    

                </div>
            </div>
            </div>
        <?php
            }
        ?>


        </div> <!-- end row-->
    </div> <!--End Contaner-->


<?php

include "includes/footer.php";
ob_end_flush();
?>
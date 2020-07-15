<?php
ob_start();
session_start();
include "includes/header.php";
include "includes/navbar.php";
include "includes/connect.php";


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



if(isset($_GET['id'])){

    if(is_numeric($_GET['id'])){

        $id = $_GET['id'];

        $stmt=$con->prepare("SELECT * FROM novels WHERE id = ?");
        $stmt->execute(array($id));
        $novel = $stmt->fetch();
        
        ?>
<div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!--edit -->
            
                        <div class="novel">
                            <h3><?php echo stripslashes($novel['novel_title']); ?></h3>
                            <div class="img">
                                <img src="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" alt="uplouds/novels_images/<?php echo $novel['novel_image']; ?>"/>
                            </div>
                            <div class="noval-text" style="text-align:right;">
                                <?php

                                    $text = stripslashes($novel['novel_contant']);
                                 
                                    echo trim($text); 
                                 
                                ?>
                            </div><hr>
                            <div class="noval-info">

                                <i class="fas fa-pencil-alt"></i><span>
                                    <?php
                                    $stmt=$con->prepare("SELECT * FROM users WHERE u_name = ?");
                                    $stmt->execute(array($novel['novel_author']));
                                    $userinfo = $stmt->fetch();
                                    echo $userinfo['f_name']." ".$userinfo['l_name'];
                                    ?> 
                                </span> | 
                                <i class="far fa-calendar-alt"></i><span><?php echo $novel['novle_Published']; ?></span> | 
                                <i class="far fa-user"></i><span><?php echo $novel['novel_author']; ?></span>
                            </div>
                            <div class="buttons">
                                
                                <?php 
                                if(isset($_SESSION['u_name'])){
                                    ?>
                                        <hr>
                                        <?php
                                            $stmt = $con->prepare('SELECT * FROM likes WHERE novel_id = ? AND u_name = ?');
                                            $stmt->execute(array($novel['id'],$_SESSION['u_name']));
                                            $count = $stmt->rowCount();
                                            if($count > 0){
                                                ?>
                                                    <div style="display:inline" id="<?php echo $novel['id'] ?>"><a href="javascript:void(0)" onclick="likes('deslike.php?novel_id=<?php echo $novel['id'] ?>&userid=<?php echo $_SESSION['u_name'] ?>',<?php echo $novel['id'] ?>)" class="link"><i class="fas fa-thumbs-up"></i>اعجبنى</a> </div>
                                                <?php
                                            }else{
                                                ?>
                                                    <div style="display:inline" id="<?php echo $novel['id'] ?>"><a href="javascript:void(0)" onclick="likes('like.php?novel_id=<?php echo $novel['id'] ?>&userid=<?php echo $_SESSION['u_name'] ?>',<?php echo $novel['id'] ?>)" class="link"><i class="far fa-thumbs-up"></i>اعجبنى</a> </div>
                                                <?php
                                            }
                                        ?>
                                        
                                        <div style="display:inline"><a href="comment.php?do=add&id=<?php echo $novel['id'] ?>" class="link"><i class="far fa-comment"></i>تعليق</a> </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>

                <div class="div_comments">
                <h4> التعليقات </h4>

                    <?php
                        $stmt = $con->prepare('SELECT * FROM comments WHERE novel_id = ? ORDER BY id DESC');
                        $stmt->execute(array($novel['id']));
                        $comments = $stmt->fetchAll();
                        $comment_count = $stmt->rowCount();
                        
                            foreach($comments as $comment){
                                ?>
                                    <table>
                                        <tr>
                                            <td class="image_comment">
                                                <div class="img_comment">
                                                <?php
                                                    $stmt=$con->prepare("SELECT * FROM users WHERE u_name = ?");
                                                    $stmt->execute(array($comment['comment_user']));
                                                    $r = $stmt->fetch();
                                                    

                                                ?>
                                                <img src="uplouds/image_profile/<?php echo $r['image']; ?>"  alt="uplouds/image_profile/<?php echo $r['image']; ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                            <div class="comment">
                                                <div>
                                                <h6>
                                                <?php
                                                    $stmt=$con->prepare("SELECT * FROM users WHERE u_name = ? ");
                                                    $stmt->execute(array($comment['comment_user']));
                                                    $r = $stmt->fetch();
                                                    echo $r['f_name'] . " " . $r['l_name'];

                                                ?>
                                                </h6> | <h6>
                                                <?php echo $comment['comment_date']; ?>
                                                </h6>
                                            </div>
                                                <?php echo $comment['comment']; ?>
                                            </div>
                                                
                                            

                                            </td>
                                        </tr>
                                    </table>

                                <?php
                            }
                        
                    ?>

                    

                    

                </div>


                    

            </div> <!--end novels-->




            <div class="col-lg-4">

                        <div class="latest-novel">
                            <h4> معلومات عن الكاتب</h4>

                            
                                <div class="l-novel">
                                <div style="text-align:center;">
                                    

                                <?php
                                    if(!$userinfo['image'] == ""){

                                        ?>
                                            <img class="img-profile" src="uplouds/image_profile/<?php echo $userinfo['image'] ?>" alt="uplouds/image_profile/<?php echo $userinfo['image'] ?>" width="100px" />
                                        <?php

                                    }else{

                                        ?>
                                            <img class="img-profile" src="uplouds/image_profile/user.png"  alt="uplouds/image_profile/user.png" width="100px"  />
                                        <?php

                                    }
                                ?>

                                    <br><br>
                                        <?php echo $novel['novel_author']; ?>
                                </div><br>
                                <table class="tbl_info_user">
                                    <tr>
                                        <td >
                                            اسم الـكاتــــب : 
                                        </td>
                                        <td>
                                            <?php
                                            echo $userinfo['f_name']." ".$userinfo['l_name'];
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            عدد الروايــــات : 
                                        </td>
                                        <td>
                                            <?php
                                                $stmt=$con->prepare("SELECT * FROM novels WHERE novel_author = ? LIMIT 5");
                                                $stmt->execute(array($novel['novel_author']));
                                                $count = $stmt->rowCount();
                                                echo $count;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            عدد الاعجابـات : 
                                        </td>
                                        <td>
                                            <?php
                                            $stmt = $con->prepare('SELECT * FROM likes WHERE novel_id = ?');
                                            $stmt->execute(array($novel['id']));
                                            $count_likes = $stmt->rowCount();
                                            echo $count_likes;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            عدد التعليقات : 
                                        </td>
                                        <td>
                                            <?php echo $comment_count; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            الحــــــــــــــــــــالة : 
                                        </td>
                                        <td>
                                            <?php
                                                if($userinfo['status'] == 1){
                                                    ?>
                                                        <span style="color:green">مفعل</span>
                                                    <?php
                                                }else{
                                                    ?>
                                                        <span style="color:red">غير مفعل</span>
                                                    <?php
                                                }

                                            ?>
                                        </td>
                                    </tr>
                                </table>

                                    <div class="clearfix"></div>
                                </div>
                        </div>

                    </div> <!--end latest novel-->

                </div> <!-- end row-->
                    
            </div> <!--End Contaner-->

            <?php



    }else{
        header("Location: 404.php");
    }

}else{
    header("Location: index.php");
}

include "includes/footer.php";
ob_end_flush();
?>
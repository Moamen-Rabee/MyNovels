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


if(isset($_GET['search_q'])){
    $word = $_GET['search_q'];
    $word_s = filter_var($word, FILTER_SANITIZE_STRING);

    ?>

        <!--Start Contant-->
        <div class="container">
            <div class="row">

            <div class="col-lg-4">

                <div class="latest-novel">
                    <h4> مكان البحث </h4>
                    <div class="l-novel">
                    <form method="get" action="index.php">
                        <table width="100%">
                            <tr>
                                <td colspan="2">
                                    <input type="text" class="form-control" name="search_q" id="search_q" placeholder="اكتب للبحث .." required="required">
                                </td>
                            </tr>
                            <tr>
                            <td colspan="2">
                            <br><button type="submet" class="btn btn-primary btn-sm btn-block"><i class="fas fa-search"></i>بحث</button>
                            </td>
                            </tr>
                        </table>
                    </form>    
                        
                    </div>
                </div>


                <div class="latest-novel">
                    <h4> احدث 5 الروايات </h4>

                    <?php
                    
                        $stmt=$con->prepare("SELECT * FROM novels ORDER BY id DESC LIMIT 5");
                        $stmt->execute();
                        $novels = $stmt->fetchAll();
                        foreach($novels as $novel){
                            ?>
                                <div class="l-novel">
                                    <div class="l-img" >
                                        <a href="view_novel.php?id=<?php echo $novel['id']; ?>">
                                        <img src="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" alt="uplouds/novels_images/<?php echo $novel['novel_image']; ?>"/>
                                        </a>
                                    </div>
                                    <div class="l-noval-title">
                                        <a href="view_novel.php?id=<?php echo $novel['id']; ?>">
                                            <?php echo $novel['novel_title']; ?>
                                        </a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>


                            <?php 
                        }
                    
                    ?>


                </div>

                </div> <!--end latest novel-->




                <div class="col-lg-8">
            <!--edit -->
                <?php
                    $stmt=$con->prepare("SELECT * FROM novels ORDER BY id ASC");
                    $stmt->execute();
                    $novels = $stmt->fetchAll();
                    foreach($novels as $novel){
                        $stmt = $con->prepare('SELECT * FROM likes WHERE novel_id = ?');
                        $stmt->execute(array($novel['id']));
                        $count_likes = $stmt->rowCount();

                        $stmt = $con->prepare('UPDATE novels SET novel_likes = ? WHERE id = ?');
                        $stmt->execute(array($count_likes,$novel['id']));
                    }
                    



                    
                    $stmt=$con->prepare("SELECT * FROM novels WHERE novel_title LIKE '%$word_s%' OR novel_author LIKE '%$word_s%' ORDER BY novel_likes DESC");
                    $stmt->execute();
                    $novels = $stmt->fetchAll();
                    $novels_count = $stmt->rowCount();
                    if($novels_count <= 0){
                        ?>
                            <div class="alert alert-info text-center" role="alert">
                                عفواً لا يوجد نتائج للبحث لكلمة <b><?php echo $word; ?></b> حاول ب كلمة اخرى
                            </div>
                        <?php
                    }

                    foreach($novels as $novel){
                        ?>
                            <div class="novel">
                                <h3><?php echo stripslashes($novel['novel_title']); ?></h3>
                                
                                <div class="img">
                                    
                                    <img src="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" alt="uplouds/novels_images/<?php echo $novel['novel_image']; ?>"/>
                                    
                                </div>
                                <h6>
                                <div class="noval-text">
                                    <?php
                                        $text = stripslashes($novel['novel_contant']);
                                    
                                        echo substr(trim($text),0,301-1); 
                                    
                                    
                                    ?>
                                </div>
                                <h6>
                                <div class="noval-info">
                                    <i class="fas fa-pencil-alt"></i><span>
                                    <?php
                                    $stmt=$con->prepare("SELECT * FROM users WHERE u_name = ?");
                                    $stmt->execute(array($novel['novel_author']));
                                    $userfullname = $stmt->fetch();
                                    echo $userfullname['f_name']." ".$userfullname['l_name'];
                                    ?>
                                    </span> | 
                                    <i class="far fa-calendar-alt"></i><span><?php echo $novel['novle_Published']; ?></span> | 
                                    <i class="far fa-user"></i><span><?php echo $novel['novel_author']; ?></span>
                                </div><br>
                                <div class="btn-novel-info">
                                    <a href="view_novel.php?id=<?php echo $novel['id']; ?>" >إقرأ المزيد</a>
                                </div><br>
                            </div>
                        <?php
                    }
                ?>
                

                </div> <!--end novels-->

                

            </div> <!-- end row-->
                
        </div> <!--End Contaner-->

    <?php
    

}else{


?>

    <!--Start Contant-->
    <div class="container">
        <div class="row">

        <div class="col-lg-4">

            <div class="latest-novel">
                <h4> مكان البحث </h4>
                <div class="l-novel">
                <form method="get" action="index.php">
                    <table width="100%">
                        <tr>
                            <td colspan="2">
                                <input type="text" class="form-control" name="search_q" id="search_q" placeholder="اكتب للبحث .." required="required">
                            </td>
                        </tr>
                        <tr>
                        <td colspan="2">
                        <br><button type="submet" class="btn btn-primary btn-sm btn-block"><i class="fas fa-search"></i>بحث</button>
                        </td>
                        </tr>
                    </table>
                </form>    
                    
                </div>
            </div>


            <div class="latest-novel">
                <h4> احدث 5 الروايات </h4>

                <?php
                
                    $stmt=$con->prepare("SELECT * FROM novels ORDER BY id DESC LIMIT 5");
                    $stmt->execute();
                    $novels = $stmt->fetchAll();
                    foreach($novels as $novel){
                        ?>
                            <div class="l-novel">
                                <div class="l-img" >
                                    <a href="view_novel.php?id=<?php echo $novel['id']; ?>">
                                    <img src="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" alt="uplouds/novels_images/<?php echo $novel['novel_image']; ?>"/>
                                    </a>
                                </div>
                                <div class="l-noval-title">
                                    <a href="view_novel.php?id=<?php echo $novel['id']; ?>">
                                        <?php echo $novel['novel_title']; ?>
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>


                        <?php 
                    }
                
                ?>


            </div>

            </div> <!--end latest novel-->




            <div class="col-lg-8">
        <!--edit -->
            <?php
                $stmt=$con->prepare("SELECT * FROM novels ORDER BY id ASC");
                $stmt->execute();
                $novels = $stmt->fetchAll();
                foreach($novels as $novel){
                    $stmt = $con->prepare('SELECT * FROM likes WHERE novel_id = ?');
                    $stmt->execute(array($novel['id']));
                    $count_likes = $stmt->rowCount();

                    $stmt = $con->prepare('UPDATE novels SET novel_likes = ? WHERE id = ?');
                    $stmt->execute(array($count_likes,$novel['id']));
                }
                



        
                $stmt=$con->prepare("SELECT * FROM novels ORDER BY novel_likes DESC LIMIT 50");
                $stmt->execute();
                $novels = $stmt->fetchAll();

                foreach($novels as $novel){
                    ?>
                        <div class="novel">
                            <h3><?php echo stripslashes($novel['novel_title']); ?></h3>
                            
                            <div class="img">
                                
                                <img src="uplouds/novels_images/<?php echo $novel['novel_image']; ?>" alt="uplouds/novels_images/<?php echo $novel['novel_image']; ?>"/>
                                
                            </div>
                            <h6>
                            <div class="noval-text">
                                <?php
                                    $text = stripslashes($novel['novel_contant']);
                                 
                                    echo substr(trim($text),0,301-1); 
                                 
                                 
                                 ?>
                            </div>
                            <h6>
                            <div class="noval-info">
                                <i class="fas fa-pencil-alt"></i><span>
                                <?php
                                $stmt=$con->prepare("SELECT * FROM users WHERE u_name = ?");
                                $stmt->execute(array($novel['novel_author']));
                                $userfullname = $stmt->fetch();
                                echo $userfullname['f_name']." ".$userfullname['l_name'];
                                ?>
                                </span> | 
                                <i class="far fa-calendar-alt"></i><span><?php echo $novel['novle_Published']; ?></span> | 
                                <i class="far fa-user"></i><span><?php echo $novel['novel_author']; ?></span>
                            </div><br>
                            <div class="btn-novel-info">
                                <a href="view_novel.php?id=<?php echo $novel['id']; ?>" >إقرأ المزيد</a>
                            </div><br>
                        </div>
                    <?php
                }
            ?>
            

            </div> <!--end novels-->

            

        </div> <!-- end row-->
            
    </div> <!--End Contaner-->

<?php
}
?>

    
    
    <div class="footer">
        <span>
        جميع الحقوق محفوظة لــ مؤمن ربيع 2020 ©
        </span>
    </div>



<?php

include "includes/footer.php";
ob_end_flush();
?>
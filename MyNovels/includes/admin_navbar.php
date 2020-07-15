 <!--Start navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="admin.php">لوحة التحكم</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                
                <!--
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-address-card"></i>عن الموقع<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-phone-alt"></i>تواصل معنا<span class="sr-only">(current)</span></a>
                </li>

                
                -->
               

                <li class="nav-item">
                    <a class="nav-link" href="admin.php?do=all_novels"><i class="fas fas fa-book"></i>الروايات<span class="sr-only">(current)</span></a>
                </li>

                <?php
                    if($_SESSION['admin_all'] == '1'){
                        ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php?do=all_admins"><i class="fas fas fa-users-cog"></i>المشرفين<span class="sr-only">(current)</span></a>
                </li>
                        <?php
                    }
                ?>
                

                <li class="nav-item">
                    <a class="nav-link" href="admin.php?do=all_users"><i class="fas fas fa-users"></i>المستخدمين<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item dropdown" id="nav_a_user_edit">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i>
                    <?php
                        if(isset($_SESSION['admin_u_name'])){
                            echo $_SESSION['admin_f_name']." ".$_SESSION['admin_l_name'];
                        }else{
                            echo "حسابى";
                        }
                    ?>
                </a>

               

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="admin.php?do=edit_admin"><i class="fas fa-edit"></i>تعديل الحساب</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout_admin.php"><i class="fas fa-sign-out-alt"></i>تسجيل الخروج</a>
                </div>
                
                </li>


                
                
            </ul>

            
            
            </div>
        </div>
    </nav>
      <!--End navbar-->
     
 <!--Start navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">روايتى</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                
                <a class="nav-link" href="index.php"><i class="fas fa-home"></i>الصفحة الرئيسية<span class="sr-only">(current)</span></a>
                </li>
                <!--
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-address-card"></i>عن الموقع<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-phone-alt"></i>تواصل معنا<span class="sr-only">(current)</span></a>
                </li>

                
                -->
               

                

                
                <li class="nav-item" id="nav_a_add_user">
                    <a class="nav-link" href="users.php?do=add"><i class="fas fa-user-plus"></i>انشاء حساب<span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item" id="nav_a_login_user">
                    <a class="nav-link" href="login.php?do=form"><i class="fas fa-sign-in-alt"></i>تسجيل الدخول<span class="sr-only">(current)</span></a>
                </li>
                
                
                <li class="nav-item dropdown" id="nav_a_user_edit">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i>
                    <?php
                        if(isset($_SESSION['u_name'])){
                            echo $_SESSION['f_name']." ".$_SESSION['l_name'];
                        }else{
                            echo "حسابى";
                        }
                    ?>
                </a>

               

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item mb-1" href="novels.php?do=add"><i class="fas fa-plus"></i>رواية جديدة</a>
                    <a class="dropdown-item mb-1" href="mynovels.php"><i class="fas fas fa-book"></i>كتاباتى</a>
                    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="users.php?do=editProfile"><i class="fas fa-edit"></i>تعديل الحساب</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i>تسجيل الخروج</a>
                </div>
                

                </li>
                
            </ul>

            
            
            </div>
        </div>
    </nav>
      <!--End navbar-->
     
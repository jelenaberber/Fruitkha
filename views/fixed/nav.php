
<!-- header -->
<div class="top-header-area" id="sticker">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12 text-center">
                <div class="main-menu-wrap">
                    <!-- logo -->
                    <div class="site-logo">
                        <a href="index.php?page=home">
                            <img src="assets/img/logo.png" alt="">
                        </a>
                    </div>
                    <!-- logo -->

                    <!-- menu start -->
                    <nav class="main-menu">

                        <ul>
                            <?php
                            global $conn;
                            $pages = $conn->query("SELECT path, name FROM page")->fetchAll();
                            foreach ($pages as $p) :
                                ?>
                                <?php
                                if($p->name=="Home"||$p->name=="Contact"||$p->name=="Shop"||$p->name=="Author"):
                                    ?>
                                    <li><a class="nav-link active link" aria-current="page" href="index.php?page=<?= $p->path?>"><?= $p->name ?></a></li>
                            <?php endif;?>
                            <?php endforeach;?>
                            <li>
                                <div class="header-icons">
                                    <a class="shopping-cart" href="index.php?page=basket"><i class="fas fa-shopping-cart"></i></a>
                                    <?php
                                        if(isset($_SESSION['user'])){
                                            echo "<a href='models/user/logout.php'>Log out</a>";
                                            if($_SESSION['user']->role == 2){
                                                echo "<a href='index.php?page=admin'>Admin panel</a>";
                                            }
                                        }
                                        else{
                                            echo "<a href='index.php?page=login'>Log in</a>";
                                            echo "<a href='index.php?page=registration'>Registration</a>";
                                        }
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
                    <div class="mobile-menu"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end header -->



<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <?php
                    global $conn;
                    if(isset($_GET['page'])){
                        $currentPage = $_GET['page'];
                        $description = queryFunction("SELECT description FROM page WHERE path = '".$currentPage."'", false);
                        echo "<h1>$description->description</h1>";
                    }
                    else{
                        echo "<h1>Delicious Seasonal Fruits</h1>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->
<?php
if(isset($_SESSION['user'])){
    echo '<script>window.location.replace("http://localhost/sajtPraktikumPhp/index.php?page=home");</script>';
}
?>
<div class="col-12 pl-0 d-flex justify-content-around mb-5">
    <form name="login-form" id="login-form" class="col-8 col-md-4 purple mt-5 pt-5 pb-3 bg-light" method="POST" action="models/user/login.php">
        <div class="container-fluid col-12">
            <input type="email" name="email" id="email" class="container border-bottom-green  py-3 mt-2" placeholder="Email"/>
            <input type="password" name="password" id="password" class="container border-bottom-green py-3 mt-2" placeholder="Password"/>
            <div class="d-flex align-items-center flex-column py-3">
                <input type="submit" name="login-button" id="login-button" class="btn btn-dark p-2 rounded-0" value="Log in">
            </div>
        </div>
        <?php
        if(isset($_GET['error'])){
            $error = $_GET['error'];
            echo "<div class='col-12 bg-danger text-light text-center py-2'>$error</div>";
        }
        ?>
    </form>
</div>
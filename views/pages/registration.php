<?php
if(isset($_SESSION['user'])){
    echo '<script>window.location.replace("http://localhost/sajtPraktikumPhp/index.php?page=home");</script>';
}
?>
<div class="col-12 pl-0 d-flex justify-content-around mb-5">
    <form name="register-form" id="register-form" class="col-8 col-md-4 purple mt-5 pt-5 pb-3 bg-light" method="POST" action="models/user/registration.php">
        <h2 class="col-12 mb-5 text-center">Register now</h2>
        <div class="container-fluid col-12">

            <p class='text-danger'><small>*Every field is required.</small></p>
            <input type="text" name="firstName" id="firstName" class="container border-bottom-green  py-3" placeholder="First name"/><br/>
            <label id="firstNameMessage"></label>
            <input type="text" name="lastName" id="lastName" class="container border-bottom-green  py-3 mt-2" placeholder="Last name"/><br/>
            <label id="lastNameMessage"></label>
            <input type="email" name="email" id="email" class="container border-bottom-green  py-3 mt-2" placeholder="Email"/>
            <label id="emailMessage"></label>
            <input type="password" name="password" id="password" class="container border-bottom-green py-3 mt-2" placeholder="Password"/>
            <label id="passwordMessage"></label>
            <input type="password" name="confirm-password" id="confirm-password" class="container border-bottom-green py-3 mt-2" placeholder="Confirm password"/>

            <div class="d-flex align-items-center flex-column py-3">
                <input type="submit" name="register-button" id="register-button" class="btn btn-dark p-2 rounded-0" value="Register">
            </div>
        </div>
        <?php
            if(isset($_GET['error'])){
                $error = $_GET['error'];
                echo "<div class='col-12 bg-danger text-light text-center py-2'>$error</div>";
            }
        ?>
        <p class='text-center text-muted'>Already have account? <a href="index.php?page=login" class='ml-2 text-danger transpBgHover td-u'>Log in</a></p>
    </form>
</div>
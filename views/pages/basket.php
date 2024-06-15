<?php
    if(!isset($_SESSION['user'])){
        echo '<script>window.location.replace("http://localhost/sajtPraktikumPhp/index.php?page=login");</script>';
    }
?>
<div class="cart-section mt-150 mb-5" id="cart">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="cart-table-wrap" id="cart-content">
                </div>
            </div>

            <div class="col-lg-4">
                <div class="total-section" id="bill">
                    <!--ispis-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- check out section -->
<?php
global $user;
?>
<div class="checkout-section mb-150" id="order-form">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card-body">
                    <div class="billing-address-form">
                        <form action="models/cart/orderProducts.php" method="POST">
                            <p><input type="text" class="fullName" name="fullName" value="<?= $user->first_name.' '.$user->last_name ?>"></p>
                            <p><input type="email" class="email" name="email" value="<?= $user->email ?>"></p>
                            <p><input type="text" class="address" name="address" placeholder="City and address"></p>
                            <p><input type="text" class="phone" name="phone" placeholder="Phone: +381638874385"></p>
                            <input type="submit" class="cart-btn" name="orderBtn" id="order" value="Order"/>
                        </form>
                        <?php
                        if(isset($_GET['error'])){
                            echo  '<p class="form-message alert alert-danger mt-3">'.$_GET['error'].'</p>';
                        }
                        ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
if(isset($_GET['success'])){
    echo  '<p class="form-message alert alert-success mt-3 text-center">'.$_GET['success'].'</p>';
}
?>
<div class="container" id="emptyCart">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-12 d-flex justify-content-center">
            <img src="assets/img/empty_cart.jpg" alt="" class="col-8">
        </div>
        <button class="col-2 cart-btn my-3">Go back to shopping</button>
    </div>
</div>



<?php
if (!isset($_SESSION['user'])|| $_SESSION['user']->role==1) {
    echo '<script>window.location.replace("http://localhost/sajtPraktikumPhp/index.php?page=home");</script>';
}
$product_id = $_GET['id'];
$product = queryFunction("SELECT * FROM product WHERE id_product = ".$product_id, false);
$categories = queryFunction("SELECT * FROM category", true);
?>
<div class="single-product mt-150 mb-150">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="single-product-img">
                    <img src="assets/img/<?=$product->picture_src_big?>" alt="">
                </div>
            </div>
            <div class="col-md-5 mt-5">
                <div class="single-product-content">
                    <form action="models/admin/editProduct.php" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="id" value="<?=$product->id_product?>">
                        <div class="col-12">
                            <input type="text" name="name" id="name" class="container brd-none  py-3" value="<?= $product->name ?>"/>
                            <span id="spanName" class="span"></span>
                        </div>
                        <div class="col-12">
                            <input type="text" id="price" name="price" class="container brd-none py-3 mt-2" value="<?= $product->price ?>.00$"/>
                            <span id="spanAddress" class="span"></span>
                        </div>
                        <div class="col-12">
                            <select name="cat_id" id="category" class="container brd-none py-3 mt-2"">
                            <?php foreach ($categories as $cat): ?>
                                <option <?= $cat->id_category == $product->id_category ? 'selected' : '' ?> value="<?= $cat->id_category ?>"><?= $cat->name ?></option>
                            <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <label>Nova slika: </label>
                            <input type="file" name="slika-proizvod" class="mb-3" />
                        </div>
                        <div class="d-flex justify-content-center py-3 mt-5 col-12">
                            <button class="button cart-btn mx-3" name="edit">Save</button>
                            <button class="button rounded-pill bg-light p-3 mx-3 text-dark" id="cancel"><a href="index.php?page=admin">Cancel</a></button>
                        </div>
                        <?php
                        if (isset($_GET['message'])){
                            echo '<p class="form-message alert alert-danger mt-3">'.$_GET['message'].'</p>';
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
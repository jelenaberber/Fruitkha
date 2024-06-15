<?php
global $user;
if(!isset($_SESSION['user'])){
    echo '<script>window.location.replace("http://localhost/sajtPraktikumPhp/index.php?page=home");</script>';
}
else{
    $user = $_SESSION['user'];
    if($user->role == 1) {
        echo '<script>window.location.replace("http://localhost/sajtPraktikumPhp/index.php?page=home");</script>';
    }
}
require_once "models/functions.php";
?>
<div class="container-fluid">
    <div class="row d-flex justify-content-center">
        <div class="col-8 d-flex justify-content-around mt-3">
            <a href="#poll">Statistic</a>
            <a href="#users">User management</a>
            <a href="#products-admin">Products</a>
            <a href="#messages">Message management</a>
        </div>
    </div>
</div>
<?php
if (isset($_GET['message'])){
    echo '<p class="form-message alert alert-success mt-3 text-center">'.$_GET['message'].'</p>';
}
if (isset($_GET['error'])){
    echo '<p class="form-message alert alert-danger mt-3 text-center">'.$_GET['error'].'</p>';
}
?>
<div class="container my-5">
    <div class="row d-flex justify-content-center">
        <?php
            $statistic = get_statistic("all");
            $statistic_today = get_statistic("today");
        ?>
        <h1 class="text-center">Statistika pristupa u poslednja 24h</h1>
        <div class="col-8 mt-3">
            <?php
                $i = 1;
                foreach($statistic["page_percentages"] as $key=>$value):
                    $value = intval($value);
            ?>
            <div class="col-12 py-3">
                <h2 class="fs-4"><?= $key?></h2>
                <div class="progress mt-3" role="progressbar" id="stat" aria-label="Success striped example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-striped bg-success fs-4" style="width: <?= $value?>%"><?= $value?>%</div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="col-12 my-3 border-bottom py-5">
        <h3 class="col-12 text-center">Users who visited site today: (<?=count($statistic_today["users"])?>):
            <?php
            $count = count($statistic_today["users"]);
            $html = "";
            for ($i=0; $i < $count; $i++) {
                ($i != $count-1) ? $html .= $statistic_today["users"][$i].", " : $html .= $statistic_today["users"][$i];
            }
            echo $html;
            ?>
        </h3>
    </div>
</div>

<!--USERS-->
<div class="container" id="users">
    <!-- ispis   -->
</div>

<!--Products-->
<div class="container" id="products-admin">
    <!--Ispis-->
</div>
<div class="d-flex align-items-center flex-column mt-5">
    <button class="button cart-btn" id="addProduct">New product</button>
</div>


<!--Messages-->
<div class="container" id="messages">
    <!--ispis-->
</div>

<!--Modal add-->
<div class="container-fluid" id="modal-2">
    <div class="col-lg-4 m-cont">
        <div class="m-header d-flex justify-content-around">
            <h1>Add new product</h1>
            <button id="cancel" class="btn"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="m-body">
            <table class="col-12 d-flex justify-content-center">
                <tbody>
                <form  enctype="multipart/form-data" method="POST" action="models/admin/addProduct.php">
                    <tr>
                        <td><lable>Product name</lable></td>
                        <td><input type="text" name="prName" id="prName"></td>
                    </tr>
                    <tr>
                        <td><lable>Price</lable></td>
                        <td><input type="text" name="prPrice" id="prPrice"></td>
                    </tr>
                    <tr>
                        <td><lable>Category</lable></td>
                        <td>
                            <?php
                            $categories = queryFunction("SELECT * FROM category", true);
                            ?>
                            <select name="cat_id" id="ddlCategory">
                                <option value="0">Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->id_category ?>"><?= $cat->name ?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Nova slika: </label>
                        </td>
                        <td>
                            <input type="file" name="slika-proizvod" class="mb-3" />
                        </td>
                    </tr>
                    <tr class="d-flex justify-content-center mt-3">
                        <td class="d-flex justify-content-center"><input type="submit" name="addProduct" id="addProduct" class="btn button"></td>
                        <td class="d-flex justify-content-center"><input type="button" class="button rounded-pill bg-light mx-3 text-dark" id="close-modal" value="CANCEL"/></td>
                    </tr>
                </form>
                </tbody>
            </table>
        </div>
    </div>
</div>
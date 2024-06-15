<!-- products -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex flex-column align-items-center">
                <div class="product-filters">
                    <ul id="filter-list">
                        <li class="active" data-all="0" id="allProducts">All</li>
                        <?php
                        $categories = queryFunction("SELECT * FROM category", true);
                        foreach ($categories as $c):
                        ?>
                        <li data-filter="<?= $c->id_category ?>" class="filter-cat"><?= $c->name ?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <select class="form-select mb-5 col-3" id="sort">
                        <option value="0">Sort</option>
                        <option value="1">By price asc</option>
                        <option value="2">By price desc</option>
                    </select>
                    <input type="search" id="search" name="search" placeholder="Search" class="input-group col-3 mx-3 form-control">
                </div>
            </div>
        </div>

        <div class="row product-lists" id="products">
            <!--Ispis proizvoda-->
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="pagination-wrap">
                    <ul>
                        <?php
                            $brojStranica = returnNumberOfPages();
                            for($i = 0; $i < $brojStranica; $i++):
                        ?>
                                <li><a class="active product-pagination" href="#" data-limit="<?= $i?>"><?=$i + 1?></a></li>
                        <?php
                            endfor;
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end products -->


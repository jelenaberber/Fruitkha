window.onload = () => {

    if ($('#products').length) {
        loadProducts();
        sortProducts();
    }
    if ($('#cart').length) {
        loadCart();
    }
    if ($('#users').length) {
        usersAdminPanel();
        messagesAdminPanel();
        productsAdminPanel();
    }

    function ajaxCallBack(url, method, result, data={}) {
        $.ajax({
            url: url,
            method: method,
            data: data,
            dataType: "json",
            success: result,
            error: function (xhr) {
                // console.log(xhr);
            }
        })
    }

// shop
    $(document).on("click",".purchase",function(){
        let id = $(this).data('id');
        var data = {
            id_product: id
        }
        ajaxCallBack('models/addProductsToCart.php', 'get', function (data){
            window.location.replace('http://localhost/sajtPraktikumPhp/index.php?page=login')
            }, data)
    });

    function loadProducts(data = {}){
        ajaxCallBack('models/shop/getProducts.php', 'get', function (data){
            printProducts(data)
        }, data )
    }

    function printProducts (data){
        var html = "";
        console.log(data.products)
        for(let product of data.products){
            html += `<div class="col-lg-4 col-md-6 text-center strawberry">
                        <div class="single-product-item">
                            <div class="product-image">
                                <img src="assets/img/${product.picture_src_big}" alt="${product.name}">
                            </div>
                            <h3>${product.name}</h3>
                            <p class="product-price"><span>Per Kg</span> ${product.price}$</p>
                            <button class="border-0 addToCart cart-btn" data-id="${product.id_product}"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                        </div>
                    </div>`
        }
        $("#products").html(html)
    }

    function sortProducts(){
        $("#search").on('keyup', function () {
            checkFilter()
        });
        $('#sort').change(function (){
            checkFilter()
        })
        $('.filter-cat').click(function (){
            let filter = $(this).data("filter");
            setItemToLocalStorage('cat_id', filter)
            checkFilter()
        })
        $('#allProducts').click(function (){
            let filter = $(this).data("all");
            setItemToLocalStorage('cat_id', filter)
            checkFilter()
        })
    }
    function checkFilter(lim = 0){
        let search = $('#search').val();
        let sort = $('#sort').val();
        let filter = getItemsFromLocalStorage("cat_id");
        let limit = lim
        filterChange(sort, search, filter, limit)
    }
    function filterChange(val, search, filter, limit){
        var data = {
            "sortBy" : val,
            "search" : search,
            "filter" : filter,
            "limit" : limit
        }
        loadProducts(data);
    }

    // localStorage
    function setItemToLocalStorage(itemKey, itemValue){
        localStorage.setItem(itemKey, JSON.stringify(itemValue))
    }
    function getItemsFromLocalStorage(itemKey){
        return JSON.parse(localStorage.getItem(itemKey));
    }

    $(document).on("click",".addToCart",function(){
        let id = $(this).data('id');
        var data = {
            id_product: id
        }
        ajaxCallBack('models/shop/addProductsToCart.php', 'post', function (data){
            if(!data.response){
                // window.location ='http://localhost/sajtPraktikumPhp/index.php?page=registration'
            }

        }, data)
    });

//    cart content
    function loadCart(data = {}){
        ajaxCallBack('models/cart/productsInCart.php', 'get', function (data){
            printCartContent(data)
        }, data )
    }

    function printCartContent (data){
        console.log(data.products)
        if(data.products.length==0){
            $("#cart").hide();
            $("#order-form").hide()
            $("#emptyCart").show()
        }
        else{
            $("#emptyCart").hide()
            $("#cart").show()
            var html = ` <table class="cart-table">
                        <thead class="cart-table-head">
                            <tr class="table-head-row">
                                <th class="product-remove"></th>
                                <th class="product-image">Product Image</th>
                                <th class="product-name">Name</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-total">Manage</th>
                            </tr>
                        </thead>
                        <tbody>`;
            let subtotal = 0;
            let rb = 1;
            for(let product of data.products){
                subtotal += Number(product.price)
                html += `<tr class="table-body-row">
                            <td class="product-remove">${rb++}</td>
                            <td class="product-image"><img src="assets/img/${product.picture_src_small}" alt="${product.name}"></td>
                            <td class="product-name">${product.name}</td>
                            <td class="product-price">${product.price}$/kg</td>
                            <td class="product-quantity"><button class="cart-btn amount mx-1 amount" data-change="plus" data-id="${product.id_order_detail}">+</button>${product.amount} kg<button data-change="minus" data-id="${product.id_order_detail}" class="mx-2 cart-btn amount ">-</button></td>
                            <td class="product-total"><button class="p-2 cart-btn deleteItem" data-id="${product.id_order_detail}">Delete from cart</button></td>
                        </tr>`
            }
            html += `</tbody>
               </table>`
            $("#cart-content").html(html).show()

            let bill = `<table class="total-table">
                        <thead class="total-table-head">
                        <tr class="table-total-row">
                            <th>Total</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="total-data">
                            <td><strong>Subtotal: </strong></td>
                            <td>${subtotal}$</td>
                        </tr>
                        <tr class="total-data">
                            <td><strong>Shipping: </strong></td>
                            <td>5$</td>
                        </tr>
                        <tr class="total-data">
                            <td><strong>Total: </strong></td>
                            <td>${subtotal+5}$</td>
                        </tr>
                        </tbody>
                    </table>`
            $("#bill").html(bill).show()
            $("#order-form").show()
        }
    }

    $(document).on("click",".amount",function(){
        let id = $(this).data('id');
        let change = $(this).data('change');
        var data = {
            id: id,
            change: change
        }
        ajaxCallBack('models/cart/increaseAmountInCart.php', 'post', function (data){
            loadCart();
        }, data)
    });
    $(document).on("click",".deleteItem",function(){
        let id = $(this).data('id');
        var data = {
            id: id
        }
        ajaxCallBack('models/cart/deleteFromCart.php', 'post', function (data){
            loadCart();
        }, data)
    });

//stranicenje
$(document).on("click", ".product-pagination", function(e){
    e.preventDefault();
    let limit = $(this).data("limit");
    checkFilter(limit)
})

//Admin panel
    function usersAdminPanel(data = {}){
        ajaxCallBack('models/admin/getUsers.php', 'get', function (data){
            printUsers(data)
        }, data )
    }
    function printUsers(data){
        let html = '<div class="col-12 d-flex flex-column align-items-center">\n' +
            '        <h2 class="text-center fs-3 mt-5">Users'+'('+ data.users.length+')' +'</h2>\n        <table class="col-12 text-center mt-3">\n            <thead>\n                <tr>\n                    <th scope="col"></th>\n                    <th scope="col">Name</th>\n                    <th scope="col">Email</th>\n                    <th scope="col">Manage</th>\n                </tr>\n            </thead>\n            <tbody>';
        let rb = 1;
        for(let el of data.users){
            html += `
                <tr>
                    <th scope="row">${rb++}</th>
                    <td>${el.first_name} ${el.last_name}</td>
                    <td>${el.email}</td>`
            if(el.role == 2){
                html += `<td class="text-dark">Admin</td>`;
            }
            else{
                if(el.active == 0){
                    html += `<td><button id="activeUser" data-id="${el.id}" data-status="${el.active}" class="btn btn-success">Activate</button></td>`
                }
                else{
                    html += `<td><button id="activeUser" data-id="${el.id}" data-status="${el.active}" class="btn btn-danger">Deactivate</button></td>`
                }
            }
        }
        html +=`</tr>
                    </tbody>
                </table>`
        $("#users").html(html);
    }
    $(document).on("click","#activeUser",function (){
        let id = $(this).data('id');
        let status = $(this).data('status');
        let data = {
            'id' : id,
            'status' : status
        }
        ajaxCallBack('models/admin/statusUser.php', 'get', function(data){
            usersAdminPanel();
        }, data)
    });
    function messagesAdminPanel(data = {}){
        ajaxCallBack('models/admin/getMessages.php', 'get', function (data){
            printMessages(data)
        }, data )
    }
    function printMessages(data){
        let html = `<div class="col-12 d-flex flex-column align-items-center">

        <h2 class="text-center fs-3 mt-5 mb-3">Messages (`+data.messages.length+`)</h2>
        <table class="col-12 text-center">
            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Name</th>
                <th scope="col">E-mail</th>
                <th scope="col">Message</th>
                <th scope="col">Time</th>
                <th scope="col">Manage</th>
            </tr>
            </thead>
            <tbody class="text-center">`
        let rb = 1;
        for(let el of data.messages){
            html += `<tr>
                    <th scope="row">${rb++}</th>
                    <td>${el.full_name}</td>
                    <td>${el.email}</td>
                    <td>${el.content}</td>
                    <td>${el.time}</td>
                    <td><button id="deleteMessage" data-id="${el.id_message}" class="btn button">Delete</button></td>
                </tr>`
        }
        html +=` </tbody>
                </table>
            </div>`
        $("#messages").html(html);
    }
    $(document).on("click","#deleteMessage",function (){
        let id = $(this).data('id');
        let data = {
            'id' : id
        }
        ajaxCallBack('models/admin/deleteMessage.php', 'get', function(data){
            messagesAdminPanel();
        }, data)
    });
    function productsAdminPanel(data = {}){
        ajaxCallBack('models/admin/getProducts.php', 'get', function (data){
            printProductsAdminPanel(data)
        }, data )
    }
    function printProductsAdminPanel(data){
        let html = `<div class="col-12 d-flex flex-column align-items-center">
                        <h2 class="text-center fs-3 mt-5 mb-4">Products (`+ data.products.length +`)</h2>
                        <table class="col-12 text-center">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Picture</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Category</th>
                                <th scope="col">Manage</th>
                            </tr>
                            </thead>
                            <tbody class="text-center">`
        let rb = 1;
        for(let el of data.products){
            html += `<tr>
                        <th scope="row">${rb++}</th>
                        <td class="col-2"><img src="assets/img/${el.picture_src_small}" alt="${el.name}" class="col-6"/></td>
                        <td>${el.name}</td>
                        <td>${el.price}$</td>
                        <td>${el.category}</td>`
            if(el.active == 0){
                html += `<td><button id="activeProduct" data-id="${el.id_product}" data-status="${el.active}" class="btn btn-success">Activate</button></td>`
            }
            else{
                html += `<td><button id="activeProduct" data-id="${el.id_product}" data-status="${el.active}" class="btn btn-danger">Deactivate</button></td>`
            }
            html += `<td><button class="button btn editProduct"><a href="index.php?page=editProduct&id=${el.id_product}">Edit</a></button></td>
                    </tr>`
        }
        html +=` </tbody>
                </table>
                </div>`
        $("#products-admin").html(html);
    }

    $(document).on("click","#activeProduct",function (){
        let id = $(this).data('id');
        let status = $(this).data('status');
        let data = {
            'id' : id,
            'status' : status
        }
        ajaxCallBack('models/admin/statusProduct.php', 'get', function(data){
            productsAdminPanel();
        }, data)
    });

    $("#addProduct").click(function (){
        $("#modal-2").show()
    })
    $("#close-modal").click(function (){
        $("#modal-2").hide()
    })

//    provera logovanja i registracije
    $("#register-button").submit(function(event){
        let firstName, lastName, email, password, confPassword;
        firstName = $('#firstName');
        lastName = $('#lastName');
        email = $('#email');
        password = $('#password');
        confPassword = $('#confirm-password');

        var errorCount = 0;
        var regexForName = /^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15})?$/;
        var regexForEmail = /^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i;
        var regexForPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
        errorCount += check(firstName, regexForName, "Ime nije u dobrom formatu.", '#firstNameMessage');
        errorCount += check(lastName, regexForName, "Prezime nije u dobrom formatu.", '#lastNameMessage');
        errorCount += check(email, regexForEmail, "Email nije u dobrom formatu.", '#emailMessage');
        errorCount += check(password, regexForPassword, "Lozinka mora imati bar jedno malo slovo, jedno veliko slovo i bar jedan broj.", '#passwordMessage');



        if (password.val() == '') {
            $('#confPasswordMessage').html('Lozinke se ne podudaraju.');
        } else if (password.val() != confPassword.val()) {
            $('#confPasswordMessage').html('Lozinke se ne podudaraju.');
        } else {
            errorCount++
        }
        if(errorCount != 5){
            event.preventDefault();
        }
    })
    function check(variable, regex, message, labelId) {
        let value = variable.val();
        console.log(regex.test(value))
        if (value == '') {
            variable.addClass('form-error');
            return false;
        } else if (!regex.test(value)) {
            $(labelId).html(message);
            return false;
        } else {
            variable.removeClass('form-error');
            $(labelId).html('');
            return true;
        }
    }

    $("#contactForm").submit(function (event) {
        let email, name, text;
        email = $('#email');
        name = $('#name');
        text = $('#text');
        var errorCount = 0;
        var regexForEmail = /^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i;
        var regexFullName = /^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}){0,2}$/;
        errorCount += check(email, regexForEmail, "Invalid e-mail", '#emailMessage');
        errorCount += check(name, regexFullName, "Invalid name.", '#nameMessage');
        if(text.val() == ''){
            text.addClass('error');
        }
        else {
            text.removeClass('error');
            errorCount++
        }
        if (errorCount != 3) {
            event.preventDefault();
        }
    })

    $("#contactForm").submit(function (event) {
        let email, name, text;
        email = $('#email');
        name = $('#name');
        text = $('#text');
        var errorCount = 0;
        var regexForEmail = /^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i;
        var regexFullName = /^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}){0,2}$/;
        errorCount += check(email, regexForEmail, "Invalid e-mail", '#emailMessage');
        errorCount += check(name, regexFullName, "Invalid name.", '#nameMessage');
        if(text.val() == ''){
            text.addClass('form-error');
        }
        else {
            text.removeClass('form-error');
            errorCount++
        }
        if (errorCount != 3) {
            event.preventDefault();
        }
    })

    $("#order").submit(function (event) {
        let email, name, address;
        event.preventDefault();
        email = $('#email');
        name = $('#name');
        address = $('#address')
        var errorCount = 0;
        var regexForName = /^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15})?$/;
        var regexForEmail = /^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i;
        let regexForAddress = /^(([A-ZŠĐČĆŽ][a-zščćđž\d]+)|([0-9][1-9]*\.?))(\s[A-Za-zŠĐŽĆČščćđž\d]+){0,7}\s(([1-9][0-9]{0,5}[\/-]?[A-Z])|([1-9][0-9]{0,5})|(BB))\.?$/;
        errorCount += check(email, regexForEmail, "Invalid e-mail", '#spanEmail');
        errorCount += check(name, regexForName, "Invalid name.", '#spanName');
        errorCount += check(address, regexForAddress, "Invalid address.", '#spanAddress');
        if (errorCount != 3) {
            event.preventDefault();
        }
        //todo modal sa uspesnom porukom
    })
}
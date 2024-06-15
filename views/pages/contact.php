<div class="container mt-5">
    <h1 class="text-center blueLetters">Contact</h1>
    <span class="dot mb-5"></span>
    <div class="col-12 d-flex flex-wrap justify-content-center">
        <div class="col-11 d-flex flex-wrap justify-content-between">
            <div class="col-5 mt-5">
                <p class="fs-6">For all B2B/Wholesale enquiries please email: <a href="mailto:michael@mykahove.com">michael@mykahove.com</a></p>
                <p class="fs-6">Tel: <a href="tel:+1316084774">0131 608 4774</a> (9-5GMT)</p>
                <p class="fs-6">E-mail <a href="mailto:hi@mykahove.com">hi@mykahove.com</a></p>
                <p class="fs-6">50 Princes Street, IP1 1RJ, United Kingdom, IP1 1RJ</p>
                <h2 class="fs-4 mt-3 blueLetters">We’re here to help. Need a hand? You got it</h2>
                <h3 class="fs-5 blueLetters">We aim to reply to all enquiries within 48 working hours.</h3>
                <form action="models/contact/contactMessage.php" method="post" class="mt-5" id="contactForm">
                    <input type="text" name="name" id="name" placeholder="Name" class="container py-2"/>
                    <label id="nameMessage"></label>
                    <input type="email" name="email" id="email" placeholder="E-mail" class="container py-2 mt-2"/>
                    <label id="emailMessage"></label>
                    <textarea name="text" id="text" cols="22" maxlength="200" rows="4" class="container py-2 mt-2" placeholder="Your message"></textarea>
                    <label id="textMessage"></label>
                    <input type="submit" id="contact-button" name="contact-button" class="button btn mt-1" value="Send">
                    <?php
                    if (isset($_GET['error'])){
                        echo '<p class="form-error alert alert-danger">'.$_GET['error'].'</p>';
                    }
                    ?>
                    <!--    poruka u slucaju uspeha slanja    -->
                    <?php
                    if (isset($_GET['message'])){
                        echo '<p class="form-message alert alert-success mt-3">'.$_GET['message'].'</p>';
                    }
                    ?>
                </form>
                <div class="errorMessage mt-4 text-center">

                </div>
            </div>
            <div class="col-5 d-flex align-items-center">
                <img src="assets/img/contact.jpg" alt="Costumer Service" class="col-12">
            </div>
        </div>
    </div>
    <div class="col-6">

    </div>


</div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/listingStyles.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>
<body>
    <div class="top">
        <nav>
            <img src="image/testlogo.png" class="logo">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Table</a></li>
                <li><a href="#">Chair</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <span class="cart" onclick="redirectToCart()">cart</span>
            <img src="image/testprofile.png" class="userprofile" onclick="toggleMenu()">

            <div class="allsubmenu" id="subMenu">
                <div class="submenu">
                    <div class="userinfo">
                        <h2>Ong</h2> <!-- take name from db-->
                    </div>
                    <hr>

                    <a href="#" class="submenulink">
                        <p>Edit Profile</p>
                        <span></span>
                    </a>

                    <a href="#" class="submenulink">
                        <p>Edit Profile</p>
                        <span></span>
                    </a>

                    <a href="#" class="submenulink">
                        <p>Log out</p>
                        <span></span>
                    </a>

                </div>
            </div>
        </nav>
    </div>

<script>
    let subMenu = document.getElementById("subMenu");

    function toggleMenu(){
        subMenu.classList.toggle("open-menu");
    }
    
    // open cart page
    function redirectToCart(){
        window.location.href = 'cart.php';
    }
</script>
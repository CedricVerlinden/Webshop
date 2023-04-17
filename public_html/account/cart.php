<?php
session_start();

include "../../includes/data.inc.php";

if (!(isset($_SESSION["userid"]))) {
    header("Location: ../index.php");
    return;
}

if (isset($_POST["add-product"])) {
    addProductToCart($connection, $_SESSION["userid"], $_POST["product_id"]);
}

if (isset($_POST["remove-product"])) {
    removeProductFromCart($connection, $_SESSION["userid"], $_POST["product_id"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0489e35579.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/account/cart.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/buttons.css">
    <title>Cart - Store</title>
</head>
<body>
    <div class="container">
        <div class="navigation">
            <div class="navigation-left">
                <h1><a href="../">Store</a></h1>
            </div>
            <div class="categories">
                <ul class="category-list">
                    <li><a href="./">Account</a></li>
                    <li><a href="./orders.php">Orders</a></li>
                </ul>
            </div>
            <div class="navigation-right">
                <?php
                if (isset($_SESSION["userid"])) {
                    if ($_SESSION["admin"] == 1) {
                        echo '
                        <a href="../admin/">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                            </svg>
                        </a>';
                    }
                    echo '
                        <a href="./cart.php">
                            <div class="shopping-cart">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-basket3-fill shopping-cart" viewBox="0 0 16 16">
                                    <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM2.468 15.426.943 9h14.114l-1.525 6.426a.75.75 0 0 1-.729.574H3.197a.75.75 0 0 1-.73-.574z"/>
                                </svg>
                                <p>' . getCartTotalProducts($_SESSION["userid"]) . '</p>
                            </div>
                        </a>
                        <a href="./">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </a>
                        <a href="./logout.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                        </a>
                    ';
                } else {
                    echo '
                    <form action="login.php" method="post">
                        <button>Log in</button>
                    </form>
                    ';
                }
                ?>
            </div>
        </div>

        <div class="content">
            <div class="cart-wrapper">
                <div class="top">
                    <h1>Cart</h1>
                    <h1>Payment Details</h1>
                </div>
                <div class="cart">
                    <div class="left">
                        <?php displayProducts($connection); ?>
                    </div>
                    <div class="right">
                        <form action="./checkout.php" method="post">
                            <div class="input">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="<?php echo getUserEmail($connection, $_SESSION["userid"]) ?>">

                                <div class="sub">
                                    <p>Subtotal</p>
                                    <p>€<?php echo getCartTotal($connection, $_SESSION["userid"]) ?></p>
                                </div>

                                <?php $btw =  getCartTotal($connection, $_SESSION["userid"]) * 0.21; ?>

                                <div class="sub">
                                    <p>BTW (21%)</p>
                                    <p>€<?php echo $btw ?></p>
                                </div>

                                <div class="sub total">
                                    <p>Total</p>
                                    <p>€<?php echo (getCartTotal($connection, $_SESSION["userid"]) + $btw) ?></p>
                                </div>
                            </div>
                            <input type="hidden" name="subtotal" value="<?php echo getCartTotal($connection, $_SESSION["userid"]) ?>">
                            <input type="hidden" name="btw" value="<?php echo $btw ?>">
                            <input type="hidden" name="total" value="<?php echo getCartTotal($connection, $_SESSION["userid"]) + $btw ?>">
                            <?php if (!(getAllCartProducts($connection, $_SESSION["userid"]) == null)) {
                                echo '<input class="button" type="submit" name="checkout" value="Checkout">';
                            }?>
                        </form>
                        <?php if (!(getAllCartProducts($connection, $_SESSION["userid"]) == null)) {
                            echo '<div style="margin-top:50px" id="paypal-button-container"></div>';
                        }?>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="footer-top">
                <div class="footer-left">
                    <h2>Store</h2>
                    <p>This is WILL be the successor of Steam with much greater UI design.</p>
                </div>
                <div class="footer-right">
                    <div class="footer-column">
                        <h3>Company</h3>
                        <ul>
                            <li><a href="#">About Store</a></li>
                            <li><a href="#">Jobs</a></li>
                            <li><a href="#">Support</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h3>Website</h3>
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="categories/games.php">Games</a></li>
                            <li><a href="categories/components.php">Components</a></li>
                            <li><a href="categories/peripherals.php">Peripherals</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h3>Legal</h3>
                        <ul>
                            <li><a href="#">Terms</a></li>
                            <li><a href="#">Privacy</a></li>
                            <li><a href="#">Cookies</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2023 Cédric Verlinden. All rights reserved.</p>
                <div class="footer-socials">
                    <a href="https://www.twitter.com/mightyxxd" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/cedricverlinden" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://www.github.com/cedricverlinden" target="_blank"><i class="fa-brands fa-github"></i></a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
<?php if (!(getAllCartProducts($connection, $_SESSION["userid"]) == null)) {?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=AQrRas305h9M9WVMLU8T95AxvQV6jTsO5Ywp43qHW1ylJecrpuc5jtIGX5svHeSRryW6sT9zvP5Rlg3w&currency=EUR"></script>
<script>
    paypal.Buttons({
        createOrder: (data, actions) => {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: <?php echo (getCartTotal($connection, $_SESSION["userid"]) + (getCartTotal($connection, $_SESSION["userid"]) * 0.21)) ?>
                    }
                }]
            });
        },

        onApprove: (data, actions) => {
            return actions.order.capture().then(function(orderData) {
                const transaction = orderData.purchase_units[0].payments.captures[0];

                var data = {
                    'paypal': true,
                    'customerEmail': '<?php echo getUserEmail($connection, $_SESSION["userid"]) ?>',
                    'customerAddress': '<?php echo getUserStreet($connection, $_SESSION["userid"]) . ' ' . getUserHouseNumber($connection, $_SESSION["userid"]) . ', ' . getUserState($connection, $_SESSION["userid"]) . ' ' . getUserCountry($connection, $_SESSION["userid"]) ?>',
                    'invoiceNumber': transaction.id, 
                    'subtotal': <?php echo getCartTotal($connection, $_SESSION["userid"]) ?>,
                    'tax': <?php echo getCartTotal($connection, $_SESSION["userid"]) * 0.21 ?>,
                    'total': <?php echo (getCartTotal($connection, $_SESSION["userid"]) + (getCartTotal($connection, $_SESSION["userid"]) * 0.21)) ?>
                }

                $.ajax({
                    method: "POST",
                    url: "./checkout.php",
                    data: data,
                    success: function (response) {
                        console.log("Request succeeded: " + response);
                        window.location.href = "./checkout.php";
                    },
                    error: function(xhr, status, error) {
                        console.log("An error occurred: " + error);
                        console.log("Status: " + status);
                        console.log("Response: " + xhr.responseText);
                    }
                });
            })
        }
    }).render("#paypal-button-container");
</script>
<?php } ?> 

<?php
function displayProducts($connection) {
    if (!(getAllCartProducts($connection, $_SESSION["userid"]))) {
        noData("cart");
        return;
    }

    $products = explode(",", getAllCartProducts($connection, $_SESSION["userid"]));
    $productsCount = array_count_values($products);

    echo '
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Amount</th>
            <th></th>
            <th></th>
        </tr>
    ';
    foreach($productsCount as $product => $quantity) {
        echo '
        <tr>
            <td class="product">
                <img src="' . getProductImage($connection, $product) . '">
                <p>' . getProductName($connection, $product) . '</p>
            </td>
            <td>€' . getProductPrice($connection, $product) . '</td>
            <td>
                <span class="product-count">' . $quantity . '</span>
            </td>
            <td style="text-align:right; width:1%">
                <form action="./cart.php" method="post">
                    <input type="hidden" name="product_id" value="' . $product . '">
                    <button style="margin-right:20px" name="add-product" value="' . $product . '">+</button>
                </form>
            </td>
            <td style="text-align:right; width:1%">
                <form action="./cart.php" method="post">
                    <input type="hidden" name="product_id" value="' . $product . '">
                    <button name="remove-product" value="' . $product . '">-</button>
                </form>
            </td>
        </tr>
        ';
    }
    echo '</table>';
}
?>
<!-- Copyright (c) 2023 Cédric Verlinden. All rights reserved. -->
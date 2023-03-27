<?php
include 'connect.inc.php';

function noData($data) {
    echo '
    <div class="no-data">
        <p>There is no ' . $data . ' data.</p>
    </div>
    ';
}


// Addresses data
function getAllAddresses($connection) {
    $sql = "SELECT * FROM addresses;";
    $resultSet = $connection->query($sql);

    return ($resultSet->num_rows == null) ? noData("addresses") : $resultSet->fetch_all(MYSQLI_ASSOC);
}

function getUserAddress($connection, $userId) {
    $sql = "SELECT * FROM addresses WHERE user=?;";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("MySQL Statement Error: " . $connection->error);
    }

    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();
    return $result->fetch_assoc();
}

function getUserCountry($connection, $userId) {
    return (getUserAddress($connection, $userId) == null) ? false : getUserAddress($connection, $userId)["country"];
}

function getUserState($connection, $userId) {
    return (getUserAddress($connection, $userId) == null) ? false : getUserAddress($connection, $userId)["state"];
}

function getUserStreet($connection, $userId) {
    return (getUserAddress($connection, $userId) == null) ? false : getUserAddress($connection, $userId)["street"];
}

function getUserHouseNumber($connection, $userId) {
    return (getUserAddress($connection, $userId) == null) ? false : getUserAddress($connection, $userId)["number"];
}


// Categories data
function getAllCategories($connection) {
    $sql = "SELECT * FROM categories;";
    $resultSet = $connection->query($sql);

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_all(MYSQLI_ASSOC);
}

function getCategoryId($connection, $categoryName) {
    $sql = "SELECT * FROM categories WHERE name=?;";
    
    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $categoryName);
    $statement->execute();
    $resultSet = $statement->get_result();

    return $resultSet->num_rows > 0 ? $resultSet->fetch_assoc()["id"] : false;
}

function getCategoryName($connection, $categoryId) {
    $sql = "SELECT * FROM categories WHERE id=?;";
    
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $categoryId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_assoc()["name"];
}

function doesCategoryExist($connection, $categoryName) {
    $sql = "SELECT * FROM categories WHERE name=?;";
    
    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $categoryName);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows != 0);
}

function createCategory($connection, $categoryName) {
    $sql = "INSERT INTO categories (name) VALUES (?);";
    
    if (doesCategoryExist($connection, $categoryName)) {
        return false;
    }

    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $categoryName);
    $statement->execute();
    return true;
}

function updateCategory($connection, $categoryId, $categoryName) {
    $sql = "UPDATE categories SET name=? WHERE id=?;";
    
    if (!(getCategoryName($connection, $categoryName))) {
        return false;
    }

    $statement = $connection->prepare($sql);
    $statement->bind_param("si", $categoryName, $categoryId);
    $statement->execute();
    return true;
}

function deleteCategory($connection, $categoryId) {
    $sql = "DELETE FROM categories WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $categoryId);
    $statement->execute();
    return true;
}


// Orders data
function getAllOrders($connection) {
    $sql = "SELECT * FROM orders;";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '
        <div class="no-data">
            <p>There is no orders data.</p>
        </div>
        ';
        return false;
    }

    return $resultSet->fetch_all(MYSQLI_ASSOC);
}

function getUserOrders($connection, $userId) {
    $sql = "SELECT * FROM orders WHERE user=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_all(MYSQLI_ASSOC);
}

function getUserOrdersByStatus($connection, $userId, $status) {
    $sql = "SELECT * FROM orders WHERE user=? AND status=?;";
    
    $statement = $connection->prepare($sql);
    $statement->bind_param("ii", $userId, $status);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_all(MYSQLI_ASSOC);
}


// Platforms data
function getAllPlatforms($connection) {
    $sql = "SELECT * FROM platforms;";
    $resultSet = $connection->query($sql);

    return ($resultSet->num_rows == null) ? noData("platforms") : $resultSet->fetch_all(MYSQLI_ASSOC);
}

function getPlatformName($connection, $platformId) {
    $sql = "SELECT * FROM platforms WHERE id=?;";
    
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $platformId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_assoc()["name"];
}


// Products data
function getAllProducts($connection) {
    $sql = "SELECT * FROM products;";
    $resultSet = $connection->query($sql);

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_all(MYSQLI_ASSOC);
}

function getAllProductsByCategory($connection, $category) {
    $sql = "SELECT * FROM products where category=?;";
    
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $category);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_all(MYSQLI_ASSOC);
}

function getProductName($connection, $productId) {
    $sql = "SELECT * FROM products WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $productId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_assoc()["name"];
}

function getProductPrice($connection, $productId) {
    $sql = "SELECT * FROM products WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $productId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_assoc()["price"];
}

function getProductImage($connection, $productId) {
    $sql = "SELECT * FROM products WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $productId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_assoc()["image"];
}

function doesProductExist($connection, $productId) {
    $sql = "SELECT * FROM products WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $productId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : true;
}

function createProduct($connection, $categoryId, $productName, $productPlatform, $productPrice, $productImage) {
    $sql = "INSERT INTO products (category, name, platform, price, image) VALUES (?, ?, ?, ?,?);";

    $statement = $connection->prepare($sql);
    $statement->bind_param("isiis", $categoryId, $productName, $productPlatform, $productPrice, $productImage);
    $statement->execute();
}

function updateProduct($connection, $categoryId, $productName, $productPlatform, $productPrice, $productImage) {
    $sql = "UPDATE products SET category=?, name=?, platform=?, price=?, image=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("isiis", $categoryId, $productName, $productPlatform, $productPrice, $productImage);
    $statement->execute();
}

function deleteProduct($connection, $productId) {
    $sql = "DELETE FROM products WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $productId);
    $statement->execute();
}


// Users data
function getAllUsers($connection) {
    $sql = "SELECT * FROM users;";
    $resultSet = $connection->query($sql);

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_all(MYSQLI_ASSOC);
}

function getUserEmail($connection, $userId) {
    $sql = "SELECT * FROM users WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_assoc()["email"];
}

function doesUserExistById($connection, $userId) {
    $sql = "SELECT * FROM users WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows != 0);
}

function doesUserExistByEmail($connection, $email) {
    $sql = "SELECT * FROM users WHERE email=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $email);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows != 0);
}

function isUserAdmin($connection, $userId) {
    $sql = "SELECT * FROM users WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->fetch_assoc()["admin"] == 1) ? true : false;
}

function createUser($connection, $email, $password, $admin) {
    $sql = "INSERT INTO users (email, password, admin) VALUES (?, ?, ?);";

    if (doesUserExistByEmail($connection, $email)) {
        return false;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $statement = $connection->prepare($sql);
    $statement->bind_param("ssi", $email, $hashedPassword, $admin);
    $statement->execute();
    return true;
    
}

function updateUser($connection, $userId, $email, $password, $admin, $country, $state, $street, $number) {
    $addressSql = "INSERT INTO addresses (user, country, state, street, number) VALUES (?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE country=?, state=?, street=?, number=?";

    if (!(empty($password))) {
        $userSql = "UPDATE users SET email=?, password=?, admin=? WHERE id=?;";

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userStatement = $connection->prepare($userSql);
        $userStatement->bind_param("ssii", $email, $hashedPassword, $admin, $userId);
    } else {
        $userSql = "UPDATE users SET email=?, admin=? WHERE id=?;";

        $userStatement = $connection->prepare($userSql);
        $userStatement->bind_param("sii", $email, $admin, $userId);
    }

    $userStatement->execute();

    if (!(empty($country) && empty($state) && empty($street) && empty($number))) {
        $addressStatement = $connection->prepare($addressSql);
        $addressStatement->bind_param("isssisssi", $userId, $country, $state, $street, $number, $country, $state, $street, $number);
        $addressStatement->execute();
    }
}

function deleteUser($connection, $userId) {
    $sql = "DELETE FROM users WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
}


// Cart data
function getAllCartProducts($connection, $userId) {
    $sql = "SELECT cart FROM users WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $resultSet = $statement->get_result();

    return ($resultSet->num_rows == 0) ? false : $resultSet->fetch_assoc()["cart"];
}

function getCartTotal($connection, $userId) {
    $cart = getAllCartProducts($connection, $userId);
    $products = explode(",", $cart);
    $cartPrice = 0;

    foreach ($products as $product) {
        $cartPrice += getProductPrice($connection, $product);
    }

    return $cartPrice;
}

function addProductToCart($connection, $userId, $productId) {
    $newCart = $productId;
    if ((getAllCartProducts($connection, $userId))) {
        $currentCart = trim(getAllCartProducts($connection, $userId), ",");
        
        $cartProducts = explode(",", $currentCart);
        $cartProducts[] = $productId;

        $newCart = implode(",", $cartProducts);
    }

    $sql = "UPDATE users SET cart=? WHERE id=?;";
    $statement = $connection->prepare($sql);
    $statement->bind_param("si", $newCart, $userId);
    $statement->execute();
}

function removeProductFromCart($connection, $userId, $productId) {
    $currentCart = trim(getAllCartProducts($connection, $userId), ",");
    $cartProducts = explode(",", $currentCart);

    $productIndex = array_search($productId, $cartProducts);
    unset($cartProducts[$productIndex]);

    $newCart = implode(",", $cartProducts);	

    $sql = "UPDATE users SET cart=? WHERE id=?;";
    $statement = $connection->prepare($sql);
    $statement->bind_param("si", $newCart, $userId);
    $statement->execute();
}

function emptyCart($connection, $userId) {
    $sql = "UPDATE users SET cart='' WHERE id=?;";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
}

// Product data
function getMostPopularProducts() {
    global $connection;

    $sql = "SELECT products FROM orders WHERE date_added >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
    $resultSet = $connection->query($sql);

    $product_counts = array();
    while ($row = $resultSet->fetch_assoc()) {
        $product_ids = explode(',', $row['products']);
        foreach ($product_ids as $product_id) {
            if (isset($product_counts[$product_id])) {
                $product_counts[$product_id] += 1;
            } else {
                $product_counts[$product_id] = 1;
            }
        }
    }

    if (empty($product_counts)) {
        echo '<p>No popular products :(</p>';
        return;
    }

    // Get the most popular products by sorting the array in descending order of count.
    arsort($product_counts);

    echo '
    <div class="most-popular-products">
        <table>
        <tr>
            <th>Product</th>
            <th>Amount Sold</th>
            <th>Price</th>
            <th>Revenue</th>
        </tr>
    ';

    // Loop through the product counts and display the product details.
    foreach ($product_counts as $product_id => $count) {
        $product_sql = "SELECT price, image, name FROM products WHERE id = $product_id";
        $product_result = $connection->query($product_sql);

        if ($product_result->num_rows > 0) {
            $product_row = $product_result->fetch_assoc();
            $total_revenue = $count * $product_row['price'];

            echo '
            <tr>
                <td>
                    <div class="product">
                        <img src="' . $product_row["image"] . '">
                        <p>' . $product_row["name"] . '</p>
                    </div>
                </td>
                <td>' . $count . '</td>
                <td>€' . $product_row["price"] . '</td>
                <td>€' . $total_revenue . '</td>
            </tr>
            ';
        }
    }

    echo '
        </table>
    </div>
    ';
}

function getTotalProductsPerCategory($category) {
    global $connection;

    $sql = "SELECT COUNT(*) AS totalProducts FROM products WHERE category=?";
    $statement = $connection->prepare($sql);
    
    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $category);
    $statement->execute();

    $resultSet = $statement->get_result();
    if (mysqli_num_rows($resultSet) == null) {
        echo '<p>No products in this category</p>';
        return;
    }

    $row = $resultSet->fetch_assoc();
    return $row['totalProducts'];
}


// Order data
function getTotalOrderOfLastDay() {
    global $connection;

    $sql = "SELECT COUNT(*) AS totalOrdersInLast24Hours FROM orders WHERE date_added>=DATE_SUB(NOW(), INTERVAL 1 DAY);";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == 0) {
        echo '<p>No recent orders</p>';
        return;
    }
    
    $row = $resultSet->fetch_assoc();
    $totalOrders = $row['totalOrdersInLast24Hours'];

    echo '<p>' . $totalOrders . '</p>';
}

function totalRevenueOfLastDay() {
    global $connection;

    $sql = "SELECT products.id, products.price,
    COUNT(*) AS order_count,
    SUM(products.price) AS total_revenue
    FROM orders
    INNER JOIN products ON FIND_IN_SET(products.id, orders.products)
    WHERE orders.date_added >= DATE_SUB(NOW(), INTERVAL 1 DAY)
    GROUP BY products.id";
    
    $resultSet = $connection->query($sql);
    $totalRevenue = 0;
    while ($row = $resultSet->fetch_assoc()) {
        $totalRevenue += $row['total_revenue'];
    }

    if (mysqli_num_rows($resultSet) == 0) {
        echo '<p>No revenue</p>';
        return;
    }
    
    echo '<p>€' . $totalRevenue . '</p>';
}

function getLatestPaymentsDone() {
    global $connection;

    $sql = "SELECT orders.id, GROUP_CONCAT(products.name SEPARATOR ', ') as product_names, SUM(products.price) as total_price, orders.status, DATE_FORMAT(orders.date_added, '%b %e, %Y') AS formatted_date, users.email, products.image
    FROM orders
    JOIN users ON orders.user = users.id
    JOIN products ON FIND_IN_SET(products.id, orders.products)
    WHERE orders.status = 1 AND orders.date_added >= DATE_SUB(NOW(), INTERVAL 1 DAY)
    GROUP BY orders.id
    ORDER BY orders.date_added DESC
    LIMIT 3";

    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == 0) {
        echo '<p class="no-recent-payments">No recent payments with status "Done"</p>';
        return;
    }


    while ($row = $resultSet->fetch_assoc()) {
        echo '
        <div class="card">
            <div class="left">
                <img src="' . $row["image"] . '">
                <div class="buyer-information">
                    <p>' . $row["email"] . '</p>
                    <p class="date">' . $row["formatted_date"] . '</p>
                </div>
            </div>
            <p>€' . getTotalPriceOfOrder($row["id"]) . '</p>
            <div class="status green">
                <p>Done</p>
            </div>
        </div>
        ';
    }
}

function getLatestPaymentsPending() {
    global $connection;

    $sql = "SELECT orders.id, orders.products, products.price, orders.status, DATE_FORMAT(orders.date_added, '%b %e, %Y') AS formatted_date, users.email, products.name, products.image
    FROM orders
    JOIN users ON orders.user = users.id
    JOIN products ON orders.products = products.id
    WHERE orders.status = 0 AND orders.date_added >= DATE_SUB(NOW(), INTERVAL 1 DAY)
    ORDER BY orders.date_added DESC
    LIMIT 3";
    
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '<p class="no-recent-payments">No recent payments with status "Pending"</p>';
        return;
    }

    while ($row = $resultSet->fetch_assoc()) {
        echo '
        <div class="card">
            <div class="left">
                <img src="' . $row["image"] . '">
                <div class="buyer-information">
                    <p>' . $row["email"] . '</p>
                    <p class="date">' . $row["formatted_date"] . '</p>
                </div>
            </div>
            <p>€' . getTotalPriceOfOrder($row["id"]) . '</p>
            <div class="status orange">
                <p>Pending</p>
            </div>
        </div>
        ';
    }
}

// get price of order by order id - products are seperated by comma in the orders table
function getTotalPriceOfOrder($orderId) {
    global $connection;

    $sql = "SELECT products FROM orders WHERE id=?";
    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $orderId);
    $statement->execute();
    $resultSet = $statement->get_result();

    $row = $resultSet->fetch_assoc();
    $product_ids = explode(',', $row['products']);
    $total_price = 0;

    foreach ($product_ids as $product_id) {
        $product_sql = "SELECT price FROM products WHERE id = $product_id";
        $product_result = $connection->query($product_sql);
  
        if ($product_result->num_rows > 0) {
          $product_row = $product_result->fetch_assoc();
          $total_price += $product_row['price'];
        }
      }
  
    return $total_price;
}

function getTotalPriceOfLastDayOrders() {
    global $connection;

    $sql = "SELECT products FROM orders WHERE date_added >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
    $resultSet = $connection->query($sql);
    $total_price = 0;

    while ($row = $resultSet->fetch_assoc()) {
        $product_ids = explode(',', $row['products']);

        foreach ($product_ids as $product_id) {
            $product_sql = "SELECT price FROM products WHERE id = $product_id";
            $product_result = $connection->query($product_sql);

            if ($product_result->num_rows > 0) {
                $product_row = $product_result->fetch_assoc();
                $total_price += $product_row['price'];
            }
        }
    }

    if (mysqli_num_rows($resultSet) == 0) {
        echo '<p>No orders</p>';
        return;
    }

    echo '<p>€' . $total_price . '</p>';
}


function checkIfOrderIdExists($id) {
    global $connection;

    $sql = "SELECT * FROM orders WHERE id = $id";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == 0) {
        return false;
    }

    return true;
}

function getProductIdsFromOrders($id) {
    global $connection;

    $sql = "SELECT products FROM orders WHERE id=?";
    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $id);
    $statement->execute();
    $resultSet = $statement->get_result();

    $productIds = array();
    while ($row = $resultSet->fetch_assoc()) {
        array_push($productIds, $row["products"]);
    }

    return $productIds;
}

function getProductNamesFromOrders($id) {
    global $connection;

    $productIds = getProductIdsFromOrders($id);
    $productIdsString = implode(',', $productIds);
    $productNames = array();

    $sql = "SELECT name FROM products WHERE id IN ($productIdsString)";
    $resultSet = $connection->query($sql);


    while ($row = $resultSet->fetch_assoc()) {
        array_push($productNames, $row["name"]);
    }

    return $productNames;
}

function printProductNamesFromOrders($id) {
    $productNames = getProductNamesFromOrders($id);

    $productNamesString = "";
    foreach ($productNames as $productName) {
        $productNamesString .= $productName . ", ";
    }

    $productNamesString = rtrim($productNamesString, ", ");

    echo $productNamesString;
}

function downloadPdf($id) {
    global $connection;

    $sql = "SELECT pdf FROM orders WHERE id='$id'";

    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pdf_data = $row['pdf'];

        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $id . '.pdf"');

        echo $pdf_data;
    } else {
        echo "PDF not found.";
    }
}


// Cart data
function getCartProductsInfo($id) {
    global $connection;

    $sql = "SELECT cart FROM users WHERE id = $id";
    $resultSet = $connection->query($sql);
    
    if (mysqli_num_rows($resultSet) == null) {
        return;
    }

    $row = $resultSet->fetch_assoc();

    if (empty($row["cart"])) {
        return;
    }
    $products = explode(",", $row["cart"]);
    $products_count = array_count_values($products);

    $products_info = array();

    foreach ($products_count as $product => $quantity) {
        $product_info = array(
            "id" => $product,
            "name" => getProductName($connection, $product),
            "image" => getProductImage($connection, $product),
            "price" => getProductPrice($connection, $product),
            "quantity" => $quantity
        );

        array_push($products_info, $product_info);
    }

    return $products_info;
}

function getCartTotalProducts($id) {
    global $connection;

    $sql = "SELECT cart FROM users WHERE id=?;";
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $id);
    $statement->execute();
    $resultSet = $statement->get_result();

    $row = $resultSet->fetch_assoc();
    if ($resultSet->num_rows == null || $row["cart"] == null) {
        return 0;
    }

    $cart = $row["cart"];

    $cart = trim($cart, ',');

    $products = explode(",", $cart);

    return count($products);
}
/* Copyright (c) 2023 Cédric Verlinden. All rights reserved. */
<?php
include 'connect.inc.php';

// User data
function createUser($email, $password) {
    global $connection;

    if (doesUserExistByEmail($email)) {
        return false;
    }

    $sql = "INSERT INTO users (email, password, admin) VALUES (?, ?, 0);";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $statement->bind_param("ss", $email, $hashedPassword);
    $statement->execute();
    return true;
}

function updateUser($id, $email, $password, $admin) {
    global $connection;

    if (empty($password)) {
        $sql = "UPDATE users SET email=?, admin=? WHERE id=?;";
        $statement = $connection->prepare($sql);

        if (!$statement) {
            die("Error: " . $connection->error);
        }

        $statement->bind_param("sii", $email, $admin, $id);
        $statement->execute();
    } else {
        $sql = "UPDATE users SET email=?, password=?, admin=? WHERE id=?;";
        $statement = $connection->prepare($sql);

        if (!$statement) {
            die("Error: " . $connection->error);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $statement->bind_param("ssii", $email, $hashedPassword, $admin, $id);
        $statement->execute();
    }
}

function updateUserAccount($id, $email, $password, $country, $state, $street, $number) {
    global $connection;

    if (empty($password)) {
        $sql = "UPDATE users SET email=? WHERE id=?;";
        $statement = $connection->prepare($sql);

        if (!$statement) {
            die("Error: " . $connection->error);
        }

        $statement->bind_param("si", $email, $id);
        $statement->execute();
    } else {
        $sql = "UPDATE users SET email=?, password=? WHERE id=?;";
        $statement = $connection->prepare($sql);

        if (!$statement) {
            die("Error: " . $connection->error);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $statement->bind_param("ssi", $email, $hashedPassword, $id);
        $statement->execute();
    }

    $sql = "INSERT INTO addresses(user, country, state, street, number) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE country=?, state=?, street=?, number=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("issssssss", $id, $country, $state, $street, $number, $country, $state, $street, $number);
    $statement->execute();
}

function deleteUser($id) {
    global $connection;

    $sql = "DELETE FROM users WHERE id=?;";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();
}

function getAllUsers() {
    global $connection;

    $sql = "SELECT * FROM users";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '
        <div class="no-users">
            <p>There are no users available.</p>
        </div>
        ';
        return;
    }
    
    echo '
    <div class="customers">
        <table>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Admin</th>
                <th></th>
                <th></th>
            </tr>
    ';
    while ($row = $resultSet->fetch_assoc()) {
        echo '
            <tr>
                <td>' . $row["id"] . '</td>
                <td>' . $row["email"] . '</td>
                <td>' . ($row["admin"] == 1 ? 'Yes' : 'No') . '</td>
                <td style="text-align:right; width:1%">
                    <form action="./edit.php?type=edit&user=' . $row["id"] . '" method="post">
                        <button style="margin-right:20px" class="edit" name="edit">Edit</button>
                    </form>
                </td>
                <td style="text-align:right; width:1%">
                    <form action="./edit.php?type=delete&user=' . $row["id"] . '" method="post">
                        <button class="delete" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        ';
    }
    echo '
        </table>
    </div>
    ';
}

function getUserEmail($id) {
    global $connection;

    $sql = "SELECT * FROM users WHERE id=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();


    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    return $row["email"];
}

function doesUserExistByEmail($email) {
    global $connection;

    $sql = "SELECT * FROM users WHERE email=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("s", $email);
    $statement->execute();

    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    return $row != null;
}

function doesUserExistById($id) {
    global $connection;

    $sql = "SELECT * FROM users WHERE id=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    return $row != null;
}

// Adress data
function getUserCountry($id) {
    global $connection;

    $sql = "SELECT * FROM addresses WHERE user=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    if ($row == null) {
        return false;
    }

    return $row["country"];
}

function getUserState($id) {
    global $connection;

    $sql = "SELECT * FROM addresses WHERE user=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    if ($row == null) {
        return false;
    }

    return $row["state"];
}

function getUserStreet($id) {
    global $connection;

    $sql = "SELECT * FROM addresses WHERE user=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    if ($row == null) {
        return false;
    }

    return $row["street"];
}

function getUserNumber($id) {
    global $connection;

    $sql = "SELECT * FROM addresses WHERE user=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    if ($row == null) {
        return false;
    }

    return $row["number"];
}


// Category data
function createCategory($name) {
    global $connection;

    if (doesCategoryExistByName($name)) {
        return false;
    }

    $sql = "INSERT INTO categories (name) VALUES (?);";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("s", $name);
    $statement->execute();
    return true;
}

function updateCategory($id, $name) {
    global $connection;

    $sql = "UPDATE categories SET name=? WHERE id=?;";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("si", $name, $id);
    $statement->execute();
}

function deleteCategory($id) {
    global $connection;

    $sql = "DELETE FROM categories WHERE id=?;";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();
}

function getAllCategories() {
    global $connection;

    $sql = "SELECT * FROM categories";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '<li><a>No available categories</a></li>';
        return;
    }
    
    while ($row = $resultSet->fetch_assoc()) {
        echo '<li><a href="?category=' . $row["name"] . '">' . $row["name"] . '</a></li>';
    }
}

function getAllCategoriesForAdmin() {
    global $connection;

    $sql = "SELECT * FROM categories";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '
        <div class="no-categories">
            <p>There are no categories available.</p>
        </div>
        ';
        return;
    }
    
    echo '
    <div class="categories">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Products</th>
                <th></th>
                <th></th>
            </tr>
    ';
    while ($row = $resultSet->fetch_assoc()) {
        echo '
            <tr>
                <td>' . $row["id"] . '</td>
                <td>' . $row["name"] . '</td>
                <td>' . getTotalProductsPerCategory($row["id"]) . '</td>
                <td style="text-align:right; width:1%">
                    <form action="./edit.php?type=edit&category=' . $row["id"] . '" method="post">
                        <button style="margin-right:20px" class="edit" name="edit">Edit</button>
                    </form>
                </td>
                <td style="text-align:right; width:1%">
                    <form action="./edit.php?type=delete&category=' . $row["id"] . '" method="post">
                        <button class="delete" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        ';
    }
    echo '
        </table>
    </div>
    ';
}

function getCategoryId($name) {
    global $connection;

    $sql = "SELECT * FROM categories WHERE name=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("s", $name);
    $statement->execute();

    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    return $row["id"];
}

function getCategoryName($id) {
    global $connection;

    $sql = "SELECT * FROM categories WHERE id=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    $row = $resultSet->fetch_assoc();

    return $row["name"];
}

function doesCategoryExistByName($name) {
    global $connection;

    $sql = "SELECT * FROM categories WHERE name=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("s", $name);
    $statement->execute();

    $resultSet = $statement->get_result();

    if (mysqli_num_rows($resultSet) == null) {
        return false;
    }

    return true;
}

function doesCategoryExistById($id) {
    global $connection;

    $sql = "SELECT * FROM categories WHERE id=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();

    if (mysqli_num_rows($resultSet) == null) {
        return false;
    }

    return true;
}

function getCategoryList() {
    global $connection;

    $sql = "SELECT * FROM categories";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '<p style="margin-bottom:50px">No available categories, please create one first</p>';
        return;
    }
    
    echo '<select name="category" id="category">';
    while ($row = $resultSet->fetch_assoc()) {
        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
    }
    echo '</select>';
}


// Platform data
function getPlatformName($id) {
    global $connection;
    $sql = "SELECT * FROM platforms WHERE id=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }
    
    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();

    if (mysqli_num_rows($resultSet) == null) {
        return "Unknown platform";
    }

    $row = $resultSet->fetch_assoc();
    
    return $row["name"];
}

function getPlatformId($name) {
    global $connection;
    $sql = "SELECT * FROM platforms WHERE name=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }
    
    $statement->bind_param("s", $name);
    $statement->execute();

    $resultSet = $statement->get_result();

    if (mysqli_num_rows($resultSet) == null) {
        return "Unknown platform";
    }

    $row = $resultSet->fetch_assoc();
    
    return $row["id"];
}

function getPlatformList() {
    global $connection;

    $sql = "SELECT * FROM platforms";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '<p style="margin-bottom:50px">No available platforms, please create one first</p>';
        return;
    }
    
    echo '<select style="text-transform:uppercase" name="platform" id="platform">';
    while ($row = $resultSet->fetch_assoc()) {
        echo '<option value="' . $row["id"] . '">' . strtoupper($row["name"]) . '</option>';
    }
    echo '</select>';
}


// Product data
function createProduct($category, $name, $platform, $price, $image) {
    global $connection;

    $sql = "INSERT INTO products (category, name, platform, price, image) VALUES (?, ?, ?, ?, ?)";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("isiis", $category, $name, $platform, $price, $image);
    $statement->execute();
}

function updateProduct($id, $category, $name, $platform, $price, $image) {
    global $connection;

    $sql = "UPDATE products SET category=?, name=?, platform=?, price=?, image=? WHERE id=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("isiisi", $category, $name, $platform, $price, $image, $id);
    $statement->execute();
}

function deleteProduct($id) {
    global $connection;

    $sql = "DELETE FROM products WHERE id=?";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();
}

function getAllProducts() {
    global $connection;

    $sql = "SELECT * FROM products";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '
        <div class="no-products">
            <p>There are no products available.</p>
        </div>
        ';
        return;
    }
    
    while ($row = $resultSet->fetch_assoc()) {
        echo '
            <div class="product">
                <div class="product-top">
                    <img src="' . $row["image"] . '">
                    <p>' . $row["name"] . '</p>
                    <p class="platform">' . getPlatformName($row["platform"]) . '</p>
                </div>
                <div class="product-bottom">
                    <p class="price">€' . $row["price"] . '</p>
                    <form action="./index.php" method="post">
                        <input type="hidden" name="id" value="' . $row["id"] . '">
                        <button name="add-item">Buy</button>
                    </form>
                </div>
            </div>
        ';
    }
}

function getProductsByCategory($id) {
    global $connection;

    $sql = "SELECT * FROM products WHERE category=?";
    $statement = $connection->prepare($sql);
    
    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    if (mysqli_num_rows($resultSet) == null) {
        echo '
        <div class="no-products">
            <p>There are no products available in this category.</p>
        </div>
        ';
        return;
    }

    while ($row = $resultSet->fetch_assoc()) {
        echo '
            <div class="product">
                <div class="product-top">
                    <img src="' . $row["image"] . '">
                    <p>' . $row["name"] . '</p>
                    <p class="platform">' . getPlatformName($row["platform"]) . '</p>
                </div>
                <div class="product-bottom">
                    <p class="price">€' . $row["price"] . '</p>
                    <form action="./index.php" method="post">
                        <input type="hidden" name="id" value="' . $row["id"] . '">
                        <button name="add-item">Buy</button>
                    </form>
                </div>
            </div>
        ';
    }
}

function getAllProductsForAdmin() {
    global $connection;

    $sql = "SELECT * FROM products";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '
        <div class="no-products">
            <p>There are no products available.</p>
        </div>
        ';
        return;
    }
    
    echo '
    <div class="products">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Platform</th>
                <th></th>
                <th></th>
            </tr>
    ';
    while ($row = $resultSet->fetch_assoc()) {
        echo '
            <tr>
                <td>
                    <div class="product">
                        <p>' . $row["id"] . '</p>
                        <img src="' . $row["image"] . '">
                    </div>
                </td>
                <td>' . $row["name"] . '</td>
                <td>€' . $row["price"] . '</td>
                <td>' . getCategoryName($row["category"]) . '</td>
                <td style="text-transform:uppercase">' . getPlatformName($row["platform"]) . '</td>
                <td style="text-align:right; width:1%">
                    <form action="./edit.php?type=edit&product=' . $row["id"] . '" method="post">
                        <button style="margin-right:20px" class="edit" name="edit">Edit</button>
                    </form>
                </td>
                <td style="text-align:right; width:1%">
                    <form action="./edit.php?type=delete&product=' . $row["id"] . '" method="post">
                        <button class="delete" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        ';
    }
    echo '
        </table>
    </div>
    ';
}

function getMostPopularProducts() {
    global $connection;

    $sql = "SELECT products.id, products.price, products.image, products.name,
            COUNT(FIND_IN_SET(products.id, orders.products)) AS order_count,
            SUM(products.price) AS total_revenue
            FROM orders
            INNER JOIN products
            ON FIND_IN_SET(products.id, orders.products)
            WHERE orders.date_added >= DATE_SUB(NOW(), INTERVAL 1 DAY)
            GROUP BY products.id
            ORDER BY order_count DESC";

    $resultSet = $connection->query($sql);
    if (mysqli_num_rows($resultSet) == null) {
        echo '<p>No popular products :(</p>';
        return;
    }

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
    while ($row = $resultSet->fetch_assoc()) {
        echo '
        <tr>
            <td>
                <div class="product">
                    <img src="' . $row["image"] . '">
                    <p>' . $row["name"] . '</p>
                </div>
            </td>
            <td>' . $row["order_count"] . '</td>
            <td>€' . $row["price"] . '</td>
            <td>€' . $row["total_revenue"] . '</td>
        </tr>
        ';
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

function getProductName($id) {
    global $connection;

    $sql = "SELECT name FROM products WHERE id=?";
    $statement = $connection->prepare($sql);
    
    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    if (mysqli_num_rows($resultSet) == null) {
        echo '<p>No products in this category - name</p>';
        return;
    }

    $row = $resultSet->fetch_assoc();
    return $row['name'];
}

function doesProductExistById($id) {
    global $connection;

    $sql = "SELECT * FROM products WHERE id=?";
    $statement = $connection->prepare($sql);
    
    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    if (mysqli_num_rows($resultSet) == null) {
        return false;
    }

    return true;
}

function getProductImage($id) {
    global $connection;

    $sql = "SELECT image FROM products WHERE id=?";
    $statement = $connection->prepare($sql);
    
    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    if (mysqli_num_rows($resultSet) == null) {
        echo '<p>Not a valid product - image</p>';
        return;
    }

    $row = $resultSet->fetch_assoc();
    return $row['image'];
}

function getProductPrice($id) {
    global $connection;

    $sql = "SELECT price FROM products WHERE id=?";
    $statement = $connection->prepare($sql);
    
    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    if (mysqli_num_rows($resultSet) == null) {
        echo '<p>Not a valid product - price</p>';
        return;
    }

    $row = $resultSet->fetch_assoc();
    return $row['price'];
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
            <p>€' . $row["total_price"] . '</p>
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
            <p>€' . $row["price"] . '</p>
            <div class="status orange">
                <p>Pending</p>
            </div>
        </div>
        ';
    }
}

// get all orders of a user with status 0
function getPendingOrdersOfUser($id) {
    global $connection;

    $sql = "SELECT * FROM orders WHERE user=? AND status=0";
    $statement = $connection->prepare($sql);
    
    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    if (mysqli_num_rows($resultSet) == null) {
        echo '<p class="no-orders">You have no orders</p>';
        return;
    }

    echo '
    <div class="orders">
        <table>
            <tr>
                <th>ID</th>
                <th>Products</th>
                <th>Purchased</th>
                <th>Price</th>
            </tr>
    ';
    while ($row = $resultSet->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row["id"] ?></td>
            <td>
                <div class="product">
                    <img src="<?php echo getProductImage($row["products"]) ?>">
                    <p><?php echo printProductNamesFromOrders($row["id"]) ?></p>
                </div>
            </td>
            <td><?php echo $row["date_added"] ?></td>
            <td>€<?php echo getTotalPriceOfOrder($row["id"]) ?></td>
        </tr>
        <?php
    }
    echo '
        </table>
    </div>
    ';
}

// get all orders of a user with status 1
function getDoneOrdersOfUser($id) {
    global $connection;

    $sql = "SELECT * FROM orders WHERE user=? AND status=1";
    $statement = $connection->prepare($sql);
    
    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    if (mysqli_num_rows($resultSet) == null) {
        echo '<p class="no-orders">You have no orders</p>';
        return;
    }

    echo '
    <div class="orders">
        <table>
            <tr>
                <th>Products</th>
                <th>Purchased</th>
                <th>Price</th>
                <th>Invoice</th>
            </tr>
    ';
    while ($row = $resultSet->fetch_assoc()) {
        ?>
        <tr>
            <td>
                <div class="product">
                    <img src="<?php echo getProductImage($row["products"]) ?>">
                    <p><?php echo printProductNamesFromOrders($row["id"]) ?></p>
                </div>
            </td>
            <td><?php echo $row["date_added"] ?></td>
            <td>€<?php echo getTotalPriceOfOrder($row["id"]) ?></td>
            <td>
                <form action="orders.php" method="post">
                    <input type="hidden" name="order-id" value="<?php echo $row["id"] ?>">
                    <button name="download-invoice">Download Invoice</button>
                </form>
            </td>
        </tr>
        <?php
    }
    echo '
        </table>
    </div>
    ';
}



// get price of order by order id - products are seperated by comma in the orders table
function getTotalPriceOfOrder($id) {
    global $connection;

    $sql = "SELECT products.price
    FROM orders
    JOIN products ON FIND_IN_SET(products.id, orders.products)
    WHERE orders.id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $id);
    $statement->execute();

    $resultSet = $statement->get_result();
    $totalPrice = 0;
    while ($row = $resultSet->fetch_assoc()) {
        $totalPrice += $row["price"];
    }

    return $totalPrice;
}



function getAllOrdersForAdmin() {
    global $connection;

    $sql = "SELECT * FROM orders";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        echo '<p class="no-orders">There are not orders</p>';
        return;
    }

    echo '
    <div class="orders">
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Products</th>
                <th></th>
            </tr>
    ';
    while ($row = $resultSet->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row["id"] ?></td>
            <td><?php echo getUserEmail($row["user"]) ?></td>
            <td><?php echo printProductNamesFromOrders($row["id"]) ?></td>
            <td style="text-align:right; width:20%">
                <form action="orders.php" method="post">
                    <input type="hidden" name="order-id" value="<?php echo $row["id"] ?>">
                    <button name="download-invoice">Download Invoice</button>
                </form>
            </td>
        </tr>
        <?php
    }
        
    echo '
        </table>
    </div>
    ';
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

    $sql = "SELECT products FROM orders";
    $resultSet = $connection->query($sql);

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
function getCartProducts($id) {
    global $connection;

    $sql = "SELECT cart FROM users WHERE id = $id";
    $resultSet = $connection->query($sql);
    
    if (mysqli_num_rows($resultSet) == null) {
        echo '<p class="no-products">There are no products in your cart</p>';
        return;
    }

    $row = $resultSet->fetch_assoc();

    if (empty($row["cart"])) {
        echo '<p class="no-products">There are no products in your cart</p>';
        return;
    }
    $products = explode(",", $row["cart"]);
    $products_count = array_count_values($products);

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
    foreach ($products_count as $product => $quantity) {
        echo '
        <tr>
            <td class="product">
                <img src="' . getProductImage($product) . '">
                <p>' . getProductName($product) . '</p>
            </td>
            <td>€' . getProductPrice($product) . '</td>
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
            "name" => getProductName($product),
            "image" => getProductImage($product),
            "price" => getProductPrice($product),
            "quantity" => $quantity
        );

        array_push($products_info, $product_info);
    }

    return $products_info;
}

function removeProductFromCart($userId, $productId) {
    global $connection;

    $sql = "SELECT cart FROM users WHERE id = $userId";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        return;
    }

    $row = $resultSet->fetch_assoc();
    $cart = $row["cart"];

    $cart = trim($cart, ',');

    $products = explode(",", $cart);

    $index = array_search($productId, $products);

    if ($index !== false) {
        unset($products[$index]);

        $updatedCart = implode(",", $products);

        $sql = "UPDATE users SET cart = '$updatedCart' WHERE id = $userId";
        $connection->query($sql);
    }
}

function addProductToCart($userId, $productId) {
    global $connection;

    $sql = "SELECT cart FROM users WHERE id = $userId";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        return;
    }

    $row = $resultSet->fetch_assoc();
    $cart = $row["cart"];

    if (empty($cart)) {
        $updatedCart = $productId;
    } else {
        $cart = trim($cart, ',');

        $products = explode(",", $cart);
        $products[] = $productId;
        
        $updatedCart = implode(",", $products);
    }

    $sql = "UPDATE users SET cart = '$updatedCart' WHERE id = $userId";
    $connection->query($sql);
}

function getCartTotal($id) {
    global $connection;

    $sql = "SELECT cart FROM users WHERE id = $id";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        return;
    }

    $row = $resultSet->fetch_assoc();
    $cart = $row["cart"];

    if (empty($cart)) {
        return 0;
    }

    $cart = trim($cart, ',');

    $products = explode(",", $cart);

    $total = 0;

    foreach ($products as $product) {
        $total += getProductPrice($product);
    }

    return $total;
}

function getCartTotalProducts($id) {
    global $connection;

    $sql = "SELECT cart FROM users WHERE id = $id";
    $resultSet = $connection->query($sql);

    if (mysqli_num_rows($resultSet) == null) {
        return;
    }

    $row = $resultSet->fetch_assoc();
    $cart = $row["cart"];

    if (empty($cart)) {
        return 0;
    }

    $cart = trim($cart, ',');

    $products = explode(",", $cart);

    return count($products);
}

// empty order column in users table
function emptyCart($id) {
    global $connection;

    $sql = "UPDATE users SET cart = '' WHERE id = $id";
    $connection->query($sql);
}
/* Copyright (c) 2023 Cédric Verlinden. All rights reserved. */
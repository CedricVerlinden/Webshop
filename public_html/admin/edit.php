<?php
session_start();
include '../../includes/data.inc.php';

// check if user is logged in as admin
if (!(isset($_SESSION["admin"]) && $_SESSION["admin"] == 1)) {
    header("Location: ../index.php");
    return;
}

// check if user has type set to create
if (!((isset($_GET["type"]) && $_GET["type"] == "create") || (isset($_GET["type"]) && $_GET["type"] == "edit") || (isset($_GET["type"]) && $_GET["type"] == "delete"))) {
    // header("Location: ./");
    return;
}

$type = $_GET["type"];

if ($type == "create") {
    if (!((isset($_GET["product"]) && $_GET["product"] == "new") || (isset($_GET["category"]) && $_GET["category"] == "new") || (isset($_GET["user"]) && $_GET["user"] == "new"))) {
        header("Location: ./");
        return;
    }
}

if ($type == "edit" || $type == "delete") {
    if (isset($_GET["product"])) {
        if (!(($type == "edit" || $type == "delete") && doesProductExistById($_GET["product"]))) {
            header("Location: ./");
            return;
        }
    }
    
    if (isset($_GET["category"])) {
        if (!(($type == "edit" || $type == "delete") && doesCategoryExistById($_GET["category"]))) {
            header("Location: ./");
            return;
        }
    }

    if (isset($_GET["user"])) {
        if (!(($type == "edit" || $type == "delete") && doesUserExistById($_GET["user"]))) {
            header("Location: ./");
            return;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0489e35579.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/admin/edit.css">
    <link rel="stylesheet" href="../../assets/css/buttons.css">
    <title>Editing - Store</title>
</head>
<body>
    <div class="container">
        <?php
        global $connection;
        $type = $_GET["type"];

        if (isset($_POST["create-product"])) {
            createProduct($_POST["category"], $_POST["name"], $_POST["platform"], $_POST["price"], $_POST["image"]);
            header("Location: ./products.php"); 
            exit();
        }

        if (isset($_POST["edit-product"])) {
            updateProduct($_POST["id"], $_POST["category"], $_POST["name"], $_POST["platform"], $_POST["price"], $_POST["image"]);
            header("Location: ./products.php"); 
            exit();
        }

        if (isset($_POST["delete-product"])) {
            deleteProduct($_POST["id"]);
            header("Location: ./products.php");
            exit();
        }

        if (isset($_POST["keep-product"])) {
            header("Location: ./products.php");
            exit();
        }

        if (isset($_POST["create-category"])) {
            if (createCategory($_POST["name"])) {
                header("Location: ./categories.php");
                exit();
            } else {
                echo "Category already exists!";
            }
            // header("Location: ./categories.php");
            exit();
        }

        if (isset($_POST["edit-category"])) {
            updateCategory($_POST["id"], $_POST["name"]);
            header("Location: ./categories.php");
            exit();
        }

        if (isset($_POST["delete-category"])) {
            deleteCategory($_POST["id"]);
            header("Location: ./categories.php");
            exit();
        }

        if (isset($_POST["keep-category"])) {
            header("Location: ./categories.php");
            exit();
        }

        if (isset($_POST["create-user"])) {
            if (createUser($_POST["name"], $_POST["password"])) {
                header("Location: ./customers.php");
                exit();
            } else {
                echo "User already exists!";
            }
            // header("Location: ./customers.php");
            exit();
        }

        if (isset($_POST["edit-user"])) {
            updateUser($_POST["id"], $_POST["name"], $_POST["password"], $_POST["admin"]);
            header("Location: ./customers.php");
            exit();
        }

        if (isset($_POST["delete-user"])) {
            deleteUser($_POST["id"]);
            header("Location: ./customers.php");
            exit();
        }

        if (isset($_POST["keep-user"])) {
            header("Location: ./customers.php");
            exit();
        }
        
        if ($type == "create") {
            // add this to header
            if (isset($_GET["product"]) && $_GET["product"] == "new") {
                ?>
                <div class="navigation">
                <div class="navigation-left">
                    <a href="./products.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Creating new Product</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                    <div class="left">
                        <form action="./edit.php?type=create&product=new" method="post">
                            <label for="name">What will the product be called?</label>
                            <input type="text" name="name" id="name" required>

                            <label for="price">How much will it cost?</label>
                            <input type="text" name="price" id="price" required>

                            <label for="image">What image should be displayed?</label>
                            <input type="text" name="image" id="image" required>

                            <label for="platform">For which platform is this?</label>
                            <?php echo getPlatformList() ?>

                            <label for="category">In which category should it be placed?</label>
                            <?php echo getCategoryList() ?>
        
                            <input type="submit" name="create-product" value="Create Product">
                        </form>
                    </div>
                </div>
                <?php
            }

            if (isset($_GET["category"]) && $_GET["category"] == "new") {
                echo '
                <div class="navigation">
                <div class="navigation-left">
                    <a href="./categories.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Creating new Category</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                    <div class="left">
                        <form action="./edit.php?type=create&category=new" method="post">
                            <label for="name">What will the category be called?</label>
                            <input type="text" name="name" id="name" required>
        
                            <input type="submit" name="create-category" value="Create Category">
                        </form>
                    </div>
                </div>
                ';
            }

            if (isset($_GET["user"]) && $_GET["user"] == "new") {
                echo '
                <div class="navigation">
                <div class="navigation-left">
                    <a href="./customers.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Creating new User</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                    <div class="left">
                        <form action="./edit.php?type=create&user=new" method="post">
                            <label for="email">What is the email of the user?</label>
                            <input type="email" name="email" id="email" required>

                            <label for="admin">Is this user an administrator?</label> <!-- TODO -->
                            <input type="text" name="admin" id="admin" required>

                            <label for="password">What will the temporary password be?</label>
                            <input type="password" name="password" id="password" required>
        
                            <input type="submit" name="create-user" value="Create User">
                        </form>
                    </div>
                </div>
                ';
            }
        }

        if ($type == "edit") {
            if (isset($_GET["product"])) {
                $product = $_GET["product"];

                $sql = "SELECT * FROM products WHERE id=?";
                $statement = $connection->prepare($sql);
        
                if (!$statement) {
                    die("Error: " . $connection->error);
                }
        
                $statement->bind_param("i", $product);
                $statement->execute();
        
                $resultSet = $statement->get_result();
                $row = $resultSet->fetch_assoc();
        
                ?>
                <div class="navigation">
                <div class="navigation-left">
                    <a href="./products.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Editing "<?php echo getProductName($product) ?>"</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                    <div class="left">
                        <form action="./edit.php?type=edit&product= <?php echo $product ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $product ?>">

                            <label for="name">What will the product be called?</label>
                            <input type="text" name="name" id="name" value="<?php echo $row["name"] ?>" required>
        
                            <label for="price">How much wil it cost?</label>
                            <input type="text" name="price" id="price" value="<?php echo $row["price"] ?>" required>
        
                            <label for="image">What image should be displayed?</label>
                            <input type="text" name="image" id="image" value="<?php echo $row["image"] ?>" required>
        
                            <label for="platform">For which platform is this?</label>
                            <?php echo getPlatformList() ?>
        
                            <label for="category">In which category should it be placed?</label>
                            <?php echo getCategoryList() ?>
        
                            <input type="submit" name="edit-product" value="Update Product">
                        </form>
                    </div>
                </div>
                <?php
                exit();
            }

            if (isset($_GET["category"])) {
                $category = $_GET["category"];

                $sql = "SELECT * FROM categories WHERE id=?";
                $statement = $connection->prepare($sql);
        
                if (!$statement) {
                    die("Error: " . $connection->error);
                }
        
                $statement->bind_param("i", $category);
                $statement->execute();
        
                $resultSet = $statement->get_result();
                $row = $resultSet->fetch_assoc();
        
                echo '
                <div class="navigation">
                <div class="navigation-left">
                    <a href="categories.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Editing "' . getCategoryName($category) . '"</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                    <div class="left">
                        <form action="./edit.php?type=edit&category=' . $category . '" method="post">
                            <input type="hidden" name="id" value="' . $category . '">
                            <label for="name">What will the category be called?</label>
                            <input type="text" name="name" id="name" value="' . $row["name"] . '" required>
        
                            <input type="submit" name="edit-category" value="Update Category">
                        </form>
                    </div>
                </div>
                ';
                exit();
            }

            if (isset($_GET["user"])) {
                $user = $_GET["user"];

                $sql = "SELECT * FROM users WHERE id=?";
                $statement = $connection->prepare($sql);
        
                if (!$statement) {
                    die("Error: " . $connection->error);
                }
        
                $statement->bind_param("i", $user);
                $statement->execute();
        
                $resultSet = $statement->get_result();
                $row = $resultSet->fetch_assoc();
        
                ?>
                <div class="navigation">
                <div class="navigation-left">
                    <a href="./customers.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Editing "<?php echo getUserEmail($user) ?>"</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                    <div class="left">
                        <form action="./edit.php?type=edit&user=<?php echo $user ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $user ?>">
                            <label for="name">What is the email of the user?</label>
                            <input type="text" name="name" id="name" value="<?php echo $row["email"] ?>">

                            <label for="admin">Is this user an administrator?</label>
                            <select name="admin" id="admin">
                                <?php if ($_SESSION["admin"] == 1) {
                                    echo '
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                    ';
                                } else {
                                    echo '
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                    ';
                                }?>
                            </select>

                            <label for="password">What will the temporary password be?</label>
                            <input type="text" name="password" id="password">
        
                            <input type="submit" name="edit-user" value="Update User">
                        </form>
                    </div>
                </div>
                <?php
                exit();
            }
            exit();
        }

        if ($type == "delete") {
            if (isset($_GET["product"])) {
                $product = $_GET["product"];

                $sql = "SELECT * FROM products WHERE id=?";
                $statement = $connection->prepare($sql);
        
                if (!$statement) {
                    die("Error: " . $connection->error);
                }
        
                $statement->bind_param("i", $product);
                $statement->execute();
        
                $resultSet = $statement->get_result();
                $row = $resultSet->fetch_assoc();
        
                echo '
                <div class="navigation">
                <div class="navigation-left">
                    <a href="./products.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Editing "' . getProductName($product) . '"</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                <h2>Are you sure you want to delete this product?</h2>
                    <div class="delete">
                        <form action="./edit.php?type=edit&product=' . $product . '" method="post">
                            <input type="hidden" name="id" value="' . $product . '">
                            <input class="button" type="submit" name="delete-product" value="Yes">
                            <input class="button" type="submit" name="keep-product" value="No">
                        </form>
                    </div>
                </div>
                ';
                exit();
            }

            if (isset($_GET["category"])) {
                $category = $_GET["category"];

                $sql = "SELECT * FROM categories WHERE id=?";
                $statement = $connection->prepare($sql);
        
                if (!$statement) {
                    die("Error: " . $connection->error);
                }
        
                $statement->bind_param("i", $category);
                $statement->execute();
        
                $resultSet = $statement->get_result();
                $row = $resultSet->fetch_assoc();
        
                echo '
                <div class="navigation">
                <div class="navigation-left">
                    <a href="./categories.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Editing "' . getCategoryName($category) . '"</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                <h2>Are you sure you want to delete this category?</h2>
                    <div class="delete">
                        <form action="./edit.php?type=edit&category=' . $category . '" method="post">
                            <input type="hidden" name="id" value="' . $category . '">
                            <input class="button" type="submit" name="delete-category" value="Yes">
                            <input class="button" type="submit" name="keep-category" value="No">
                        </form>
                    </div>
                </div>
                ';
                exit();
            }

            if (isset($_GET["user"])) {
                $user = $_GET["user"];

                $sql = "SELECT * FROM users WHERE id=?";
                $statement = $connection->prepare($sql);
        
                if (!$statement) {
                    die("Error: " . $connection->error);
                }
        
                $statement->bind_param("i", $user);
                $statement->execute();
        
                $resultSet = $statement->get_result();
                $row = $resultSet->fetch_assoc();
        
                echo '
                <div class="navigation">
                <div class="navigation-left">
                    <a href="./customers.php"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <div class="navigation-center">
                    <h1>Editing "' . getUserEmail($user) . '"</h1>
                </div>
                <div style="flex:1"></div>
                </div>
        
                <div class="content">
                <h2>Are you sure you want to delete this user?</h2>
                    <div class="delete">
                        <form action="./edit.php?type=edit&user=' . $user . '" method="post">
                            <input type="hidden" name="id" value="' . $user . '">
                            <input class="button" type="submit" name="delete-user" value="Yes">
                            <input class="button" type="submit" name="keep-user" value="No">
                        </form>
                    </div>
                </div>
                ';
                exit();
            }
            exit();
        }
        /* End editing */
        ?>
    </div>
</body>
</html>
<!-- Copyright (c) 2023 Cédric Verlinden. All rights reserved. -->
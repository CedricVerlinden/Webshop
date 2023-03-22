<?php

include 'connect.inc.php';

// cart column is product ids separated by commas
function updateCart($userId, $productId) {
    global $connection;

    $sql = "UPDATE users SET cart=CONCAT(cart, ?) WHERE id=?;";
    $statement = $connection->prepare($sql);

    if (!$statement) {
        die("Error: " . $connection->error);
    }

    $statement->bind_param("si", $productId, $userId);
    $statement->execute();
}
/* Copyright (c) 2023 Cédric Verlinden. All rights reserved. */
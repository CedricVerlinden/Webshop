<?php

session_start();
session_unset();
session_destroy();
header("Location: ../public_html/index.php");
exit();
/* Copyright (c) 2023 Cédric Verlinden. All rights reserved. */
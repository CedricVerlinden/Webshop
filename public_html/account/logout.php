<?php

session_start();
session_unset();
session_destroy();
header("Location: ../");
exit();
/* Copyright (c) 2023 Cédric Verlinden. All rights reserved. */
<?php
$id = isset ($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : null;

//管理者資訊
$row = $pdo->query("SELECT * FROM `midterm`.admin WHERE id=$id")->fetch();

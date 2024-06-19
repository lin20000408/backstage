<?php
session_start();
unset($_SESSION['admin']);

header("Location: ./parts/html-main.php");
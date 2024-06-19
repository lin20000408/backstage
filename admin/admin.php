<?php
session_start();

if(isset($_SESSION["admin"])){
  include __DIR__. '/admin-login.php';
}else{
  include __DIR__. '/index.php';
}
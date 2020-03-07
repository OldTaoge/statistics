<!-- File:conn.php -->
<!-- Copyright(C) OldTaoge 2020.All rights reserved.-->
<!-- By GPL v3.0 -->
<?php
$conn=mysqli_connect("MySQL服务器地址" ,"用户名" ,"密码" ,"数据库");
mysqli_query($conn,"set names utf8");
?>
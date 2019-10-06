<?php

if (empty($_GET["id"])) {
    exit('<h1>必须传入指定参数</h1>');
}

$conn = mysqli_connect('127.0.0.1', 'root', '123456', 'test');

if (!$conn) {
    exit('<h1>连接数据库失败</h1>');
}

$query = mysqli_query($conn, 'delete from users where id = ' . $_GET["id"] . ';');

if (!$query) {
    exit('<h1>查询数据失败</h1>');
}

$affect = mysqli_affected_rows($conn);

if ($affect <= 0) {
    exit('<h1>删除失败</h1>');
}

header('Location: index.php');

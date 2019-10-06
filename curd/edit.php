<?php

if (empty($_GET['id'])) {
    exit('<h1>必须传入指定参数</h1>');
}
$id   = $_GET['id'];
$conn = mysqli_connect('127.0.0.1', 'root', '123456', 'test');

if (!$conn) {
    exit('<h1>数据库连接失败</h1>');
}

$query = mysqli_query($conn, "select * from users where id = {$id} limit 1;");

if (!$query) {
    exit('<h1>数据查询失败</h1>');
}

$base = mysqli_fetch_assoc($query);

if (!$base) {
    exit('<h1>查询数据失败</h1>');
}

function edit()
{

    if (empty($_POST['name'])) {
        $GLOBALS['error_message'] = "请输入姓名";
        return;
    }
    if (empty($_POST['birthday'])) {
        $GLOBALS['error_message'] = "请选择生日";
        return;
    }
    if (!(isset($_POST['gender']) && $_POST['gender'] !== '-1')) {
        $GLOBALS['error_message'] = "请输入姓名";
        return;
    }

    global $base;

    $base['name'] = $_POST['name'];

    $base['gender'] = $_POST['gender'];

    $base['birthday'] = $_POST['birthday'];

    $p_id = $_GET['id'];
    //
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $suffix = '../upload/avatar-' . uniqid() . '.' . pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $suffix)) {
            $GLOBALS['error_message'] = '文件上传失败';
            return;
        }

        $base['avatar'] = substr($suffix, 2);
    // } else {
    //     $address = $_FILES['avatar'];
    //     var_dump($address);
    //     if ($address['error'] !== UPLOAD_ERR_OK) {
    //         $GLOBALS['error_message'] = '文件上传失败';
    //         return;
    //     }

    //     if ($address['size'] > 1 * 1024 * 1024) {
    //         $GLOBALS['error_message'] = '文件上传过大';
    //         return;
    //     }
    //     if (strpos($address['type'], 'image/') !== 0) {
    //         $GLOBALS['error_message'] = '文件格式不符';
    //         return;
    //     }
    //     $suffix = '../upload/avatar-' . uniqid() . '.' . pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    //     if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $suffix)) {
    //         $GLOBALS['error_message'] = '文件上传失败';
    //         return;
    //     }

    //     $base['avatar'] = substr($suffix, 2);
    }

    $conn = mysqli_connect('127.0.0.1', 'root', '123456', 'test');
    if (!$conn) {
        $GLOBALS['error_message'] = '服务器连接失败';
        return;
    }
    //var_dump("update users set name = '{$base['name']}', gender = {$base['gender']}, birthday = '{$base['birthday']}', avatar = '{$base['avatar']}' where id = {$p_id};");
    $query = mysqli_query($conn, "update users set name = '{$base['name']}', gender = {$base['gender']}, birthday = '{$base['birthday']}', avatar = '{$base['avatar']}' where id = {$p_id};");

    if (!$query) {
        $GLOBALS['error_message'] = '数据查询失败';
        return;
    }


    header('Location: index.php');

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    edit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>XXX管理系统</title>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">XXX管理系统</a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.html">用户管理</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">商品管理</a>
            </li>
        </ul>
    </nav>
    <main class="container">
            <?php if (isset($error_message)): ?>
    <div class="alert alert-warning"><?php echo $error_message ?></div>
  <?php endif ?>
        <h1 class="heading">编辑<?php echo $base['name'] ?> </h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $base['id'] ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="avatar">头像</label>
                <input type="file" class="form-control" id="avatar" name="avatar">
            </div>
            <div class="form-group">
                <label for="name">姓名</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($base['name']) ? $base['name'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="gender">性别</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="-1">请选择性别</option>
                    <option value="1"<?php echo $base['gender'] === '1' ? ' selected' : '' ?>>男</option>
                    <option value="0"<?php echo $base['gender'] === '0' ? ' selected' : '' ?>>女</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthday">生日</label>
                <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo isset($base['birthday']) ? $base['birthday'] : '' ?>">
            </div>

            <button class="btn btn-primary">保存</button>

        </form>
    </main>
</body>

</html>
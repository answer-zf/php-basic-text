<?php

function add()
{
    if (empty($_POST['name'])) {
        $GLOBALS['error_message'] = '请输入用户名';
        return;
    }
    if (!(isset($_POST['gender']) && $_POST['gender'] !== '-1')) {
        $GLOBALS['error_message'] = '选择性别';
        return;
    }
    if (empty($_POST['birthday'])) {
        $GLOBALS['error_message'] = '请选择日期';
        return;
    }

   	$name = $_POST['name']; 

   	$gender = $_POST['gender']; 
   	
   	$birthday = $_POST['birthday']; 

    if (empty($_FILES['avatar'])) {
        $GLOBALS['error_message'] = '请选择文件';
        return;
    }

    $address = $_FILES['avatar'];

    //var_dump($address);

    if ($address['error'] !== UPLOAD_ERR_OK){
    	$GLOBALS['error_message'] = '文件上传失败';
        return;
    }

    if($address['size'] > 1 * 1024 *1024){
    	$GLOBALS['error_message'] = '文件上传过大';
        return;
    }
    if(strpos($address['type'],'image/') !== 0){
    	$GLOBALS['error_message'] = '文件格式不符';
        return;
    };
    $suffix = '../upload/avatar-' . uniqid() . '.' . pathinfo($address['name'], PATHINFO_EXTENSION);
    if(!move_uploaded_file($address['tmp_name'],$suffix)){
    	$GLOBALS['error_message'] = '文件上传失败';
        return;
    };
    
    $avatar = substr($suffix, 2);

    $conn = mysqli_connect('127.0.0.1', 'root' ,'123456' ,'test');
    if (!$conn) {
    	$GLOBALS['error_message'] = '服务器连接失败';
        return;
    }
    // var_dump("insert into users values ( null ,'{$name}' , {$gender} ,'{$birthday}' , '{$avatar}')");
  	$query = mysqli_query($conn, "insert into users values (null, '{$name}', {$gender}, '{$birthday}', '{$avatar}');");
    var_dump($query);
    if (!$query) {
    	$GLOBALS['error_message'] = '数据查询失败';
        return;
    }

    if(mysqli_affected_rows($conn) !== 1){
    	$GLOBALS['error_message'] = '添加信息失败';
        return;
    }

	header('Location: index.php');

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    add();
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
    <h1 class="heading">添加用户</h1>
    <form action=" <?php echo $_SERVER['PHP_SELF'] ?> " method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form-group">
        <label for="avatar">头像</label>
        <input type="file" class="form-control" id="avatar" name="avatar">
      </div>
      <div class="form-group">
        <label for="name">姓名</label>
        <input type="text" class="form-control" id="name" name="name">
      </div>
      <div class="form-group">
        <label for="gender">性别</label>
        <select class="form-control" id="gender" name="gender">
          <option value="-1">请选择性别</option>
          <option value="1">男</option>
          <option value="0">女</option>
        </select>
      </div>
      <div class="form-group">
        <label for="birthday">生日</label>
        <input type="date" class="form-control" id="birthday" name="birthday">
      </div>
      <button class="btn btn-primary">保存</button>
    </form>
  </main>
</body>
</html>

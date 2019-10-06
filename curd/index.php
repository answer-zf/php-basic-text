<?php 
	
	$connection = @mysqli_connect('127.0.0.1', 'root', '123456', 'test');

	if (!$connection) {
		exit('<h1>连接数据库失败！</h1>');
	}

	$query = mysqli_query($connection, 'select * from users;');

	if (!$query){
		
		exit('<h1>查询数据失败</h1>');

	}

	//while ($row = mysqli_fetch_assoc($query)) {
	//	$list[]=$row;
	//}

	//mysqli_free_result($query);
	//mysqli_close($connection);
	//var_dump($list);

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XXX管理系统</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
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
    <h1 class="heading">用户管理 <a class="btn btn-link btn-sm" href="add.php">添加</a></h1>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>头像</th>
          <th>姓名</th>
          <th>性别</th>
          <th>年龄</th>
          <th class="text-center" width="140">操作</th>
        </tr>
      </thead>
      <tbody>
			<?php while ($row = mysqli_fetch_assoc($query)): 
				$both = strtotime($row['birthday'] . '0:0:0');
				$bY = date('Y', $both);
				$bm = date('m', $both);
				$bd = date('d', $both);
				$nm = date('m');
				$nd = date('d');
				//var_dump($nd > $bd);
				$age = date('Y') - $bY;
				if( $bm > $nm || $bm == $nm && $bd > $nd){
					$age--;
				}
			?>
	      		<tr>
					<th scope="row"><?php echo $row['id']; ?></th>
				   	<td><img src="<?php echo $row['avatar']; ?>" class="rounded" alt="<?php echo $row['name']; ?>"></td>
				   	<td><?php echo $row['name']; ?></td>
					<td><?php echo $row['gender']== 0 ? '♀' : '♂'?></td>
				   	<td><?php echo $age;?></td>
				    <td class="text-center">
				        <a class="btn btn-info btn-sm" href="edit.php?id=<?php echo $row['id']; ?>">编辑</a>
				        <a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $row['id']; ?>">删除</a>
				    </td>			    				    				    						
				</tr>				
			<?php endwhile ?>
</table>
<ul class="pagination justify-content-center">
    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
    </ul>
  </main>
</body>
</html>

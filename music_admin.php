<?php

function add_music()
{

    //非空验证
 
    if (empty($_POST['username'])) {
        $GLOBALS['error_message'] = '请输入歌手';
        return;
    }
    if (empty($_POST['usermuc'])) {
        $GLOBALS['error_message'] = '请输入歌曲';
        return;
    }
    $data           = array();
    $data['id']     = uniqid();
    $data['title']  = $_POST['username'];
    $data['artist'] = $_POST['usermuc'];
    //var_dump($_FILES['userposter']);
    // array(5) {
    //     ["name"] =>
    //     string(11)"favicon.ico"
    //     ["type"] =>
    //     string(12)"image/x-icon"
    //     ["tmp_name"] =>
    //     string(27)"C:\Windows\Temp\phpEBF3.tmp"
    //     ["error"] =>
    //     int(0)
    //     ["size"] =>
    //     int(4286)
    // }
     // $json = file_get_contents('storage.json');
     // $old = json_decode($json, true);
     // array_push($old ,$json);
     // $n = json_encode($old);
     // file_put_contents('storage.json' ,$n);
     // header('Location: ')

    /**
     * 音乐上传验证与上传 （单文件上传）
     */

    //判断文件域是否存在
    if (empty($_FILES['usermuc-file'])) {
        $GLOBALS['error_message'] = '请正确提交文件';
        return;
    }

    $muc_file = $_FILES['usermuc-file'];

    if (empty($muc_file['name'])) {
        $GLOBALS['error_message'] = '请上传文件';
        return;
    }

    if ($muc_file['error'] !== UPLOAD_ERR_OK) {
        $GLOBALS['error_message'] = '上传失败';
        return;
    }

    // 校验文件大小

    if ($muc_file['size'] > 10 * 1024 * 1024) {
        $GLOBALS['error_message'] = "文件过大";
        return;
    }

    if ($muc_file['size'] < 1 * 1024 * 1024) {
        $GLOBALS['error_message'] = "文件过小";
        return;
    }

    // 校验文件类型

    $muc_type = array('audio/mp3', 'audio/wma');
    if (!in_array($muc_file['type'], $muc_type)) {
        $GLOBALS['error_message'] = "不支持音乐该文件格式";
        return;
    }

    // 为了防止上传文件名相同导致文件覆盖，用uniqid生成唯一文件名

    $n_address = "./upload/" . uniqid() . '-' . $muc_file['name'];

    if (!move_uploaded_file($muc_file['tmp_name'], $n_address)) {
        $GLOBALS['error_message'] = '音乐上传失败';
        return;
    }
    // 保存数据的路径一定使用绝对路径存!!!
    $data['source'] = substr($n_address,1);

    /**
     * 图片上传验证与上传（多文件上传）
     */

    // 文件域非空验证

    if (empty($_FILES['userposter'])) {
        $GLOBALS['error_message'] = '请正确上传图片';
        return;
    }
    $dest = $_FILES['userposter'];

    for ($i = 0; $i < count($dest['name']); $i++) {

        // 多文件上传error验证
        //
        if ($dest['error'][$i] !== UPLOAD_ERR_OK) {
            $GLOBALS['error_message'] = '图片文件上传失败';
            return;
        }

        // 多文件大小验证
        //
        if ($dest['size'][$i] >= 1 * 1024 * 1024) {
            $GLOBALS['error_message'] = '图片文件过大';
            return;
        }

        // 多文件类型验证
        //
        if (strpos($dest['type'][$i], 'image/') !== 0) {
            $GLOBALS['error_message'] = '图片文件类型不正确';
            return;
        }

        // 上传成功，转移文件
        //

        $url = './upload/' . uniqid() . '-' . $dest['name'][$i];

        if (!move_uploaded_file($dest['tmp_name'][$i], $url)) {
            $GLOBALS['error_message'] = '图片文件上传失败';
            return;
        }

        $data['images'][] = substr($url, 1);

    }
    // var_dump($data);
    $json = file_get_contents('storage.json');
    $old  = json_decode($json, true);
    array_push($old, $data);
    $new_json = json_encode($old);
    file_put_contents('storage.json', $new_json);
    // 跳转

   header('Location: music.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    add_music();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>admin</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>

	<div class="container">
		<h2 class="mt-5 mb-3">添加音乐</h2>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" autocomplete="off">
			<?php if (isset($error_message)): ?>
				<div class="alert alert-danger" role="alert">
					<?php echo $error_message; ?>
				</div>
			<?php endif?>
			<div class="form-group">
				<label for="username">歌手</label>
				<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>">
				<small class="form-text"></small>
			</div>
			<div class="form-group">
				<label for="usermuc">歌曲</label>
				<input type="text" name="usermuc" id="usermuc" class="form-control" value="<?php echo isset($_POST['usermuc']) ? $_POST['usermuc'] : '' ?>">
				<small class="form-text"></small>
			</div>
			<div class="form-group">
				<label for="userposter">海报</label>
				<input type="file" name="userposter[]" id="userposter" class="form-control" accept="image/*" multiple>
				<small class="form-text"></small>
			</div>
			<div class="form-group">
				<label for="usermuc-file">音乐</label>
				<input type="file" name="usermuc-file" id="usermuc-file" class="form-control" accept="audio/*">
				<small class="form-text"></small>
			</div>
			<div class="form-group">
				<button class="btn btn-danger btn-lg btn-block">保存</button>
			</div>
		</form>

	</div>

</body>
</html>
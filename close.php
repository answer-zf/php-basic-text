<?php

setcookie('close', '1', time() + 1 * 24 * 60 * 60);

header('Location: closeBanner_cookie.php');

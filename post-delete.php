<?php 
require_once '../functions.php';

//
if (empty($_GET['id'])) {
	exit('exit code 1');
}
$id=$_GET['id'];

$rows=xiu_execute('delete from posts where id in (' . $id . ');');
$row_s=xiu_execute('delete from comments where post_id in (' . $id . ');');


header('Location: /admin/posts.php');
// if ($rows>0) {
// 	# code...
// }




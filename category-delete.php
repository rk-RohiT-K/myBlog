<?php 
require_once '../functions.php';
//Delete corresponding data according to the ID passed by the client
//
if (empty($_GET['id'])) {
	exit('Missing required parameters');
}
$id=$_GET['id'];

$rows=xiu_execute('delete from categories where id in (' . $id . ');');
$row_s=xiu_execute('delete from posts where category_id in (' . $id . ');');

header('Location: /admin/categories.php');
// if ($rows>0) {
// 	# code...
// }




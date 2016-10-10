<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();
$new_quantity = $_GET['new_quantity'];
$post = get_post( $_GET['id'] );
// if (!isset($stockGlobal))
// 	echo $stockGlobal = get_post_meta($_GET['id'], 'stock', true);
if($new_quantity <= $GLOBALS['stockGlobal']){
	$stock=true;
	$data["stock"] = $stock;

}
else {
	$stock=false;
	$data["stock"] = $stock;
}
setup_postdata($post);
// ChromePhp:log($post);
echo json_encode($data);

?>
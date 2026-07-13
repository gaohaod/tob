<?php
if (!isset($_GET['assetId'])) {
    return '';
}
header('Location: http://noname.xyz/marketplace/productinfo?id=' . (INT)$_GET['assetId']); 
exit; 
?>
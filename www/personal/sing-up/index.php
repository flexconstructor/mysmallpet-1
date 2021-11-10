<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?>
<?php
$sSorterPath = $_SERVER['DOCUMENT_ROOT'].'/include/personal/sing-up/index.php';
if (file_exists($sSorterPath)) {
    include($sSorterPath);    
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
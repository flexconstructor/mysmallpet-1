<?define('NEED_AUTH', true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle('Успешная авторизация');?>


<?php
use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();
$bIsAjax = (
    $request->isAjaxRequest() ||
    $request->get('rs_ajax') == 'Y' ||
    $request->get('rs_ajax__page') == 'Y'
);

if ($bIsAjax)
{
    $APPLICATION->RestartBuffer();
    ?>
    <script>
    if (window.jQuery && appSLine != undefined)
	{
        appSLine.closePopup({
        <?php if (strlen($request->get('backurl')) > 0): ?>
            backurl: <?=CUtil::PhpToJSObject($request->get('backurl'))?>,
        <?php endif; ?>
        });
    }
    </script>
    <?php
}
else if (strlen($request->get('backurl')) > 0)
{
	LocalRedirect($request->get('backurl'));
}
?>

<p class="notetext">Вы успешно зарегистрировались и авторизовались на сайте!</p>

<?
if ($bIsAjax) {
    die();
}
?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
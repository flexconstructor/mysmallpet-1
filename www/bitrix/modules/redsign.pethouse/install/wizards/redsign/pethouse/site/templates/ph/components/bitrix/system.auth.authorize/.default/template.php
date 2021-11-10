<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

$request = Application::getInstance()->getContext()->getRequest();

if ($request->get('backurl') && strlen($request->get('backurl')) > 0) {
	$arResult['BACKURL'] = htmlspecialchars($request->get('backurl'));
}
/*
ob_start();
*/
?>
<div class="form row">
    <div class="col col-md-4">
<form class="js-ajax_form" id="authform_body" name="form_auth" method="POST" target="_top" action="<?=$arResult['AUTH_URL']?>" data-fancybox-title="<?=Loc::getMessage('AUTH_TITLE')?>">
    <?php $frame = $this->createFrame('authform_body', false)->begin(''); ?>

    <?/*if($arResult["AUTH_SERVICES"]):?>
        <div class="panel__head"><?=Loc::getMessage('AUTH_PLEASE_AUTH');?></div>
    <?endif*/?>

    <?php
    ShowMessage($arParams['~AUTH_RESULT']);
    ShowMessage($arResult['ERROR_MESSAGE']);
    ?>

    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="AUTH">

		<?php if (strlen($arResult["BACKURL"]) > 0): ?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
		<?php endif ?>

    <?foreach ($arResult["POST"] as $key => $value):?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>">
    <?endforeach?>

    <div class="form-group">
        <input class="form-control" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult['LAST_LOGIN']?>" placeholder="<?=Loc::getMessage('AUTH_LOGIN')?>">
    </div>

    <div class="form-group form-group-password has-feedback">
        <input class="form-control" type="password" name="USER_PASSWORD" maxlength="255" placeholder="<?=GetMessage('AUTH_PASSWORD')?>">
        
        <?php if ($arParams["NOT_SHOW_LINKS"] != "Y"): ?>
            <a class="form-control-feedback js-ajax_link" href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" title="<?=Loc::getMessage('FORGOT_PASSWORD_TITLE')?>" rel="nofollow"><?=Loc::getMessage('AUTH_FORGOT_PASSWORD_2')?></a>
        <?php endif; ?>
    </div>

    <?php if ($arResult['SECURE_AUTH']): ?>
        <noscript><div class="authform__nonsecure-note"><?=Loc::getMessage('AUTH_NONSECURE_NOTE')?></div></noscript>
    <?php endif; ?>
    
    <?php if ($arResult['CAPTCHA_CODE']): ?>
        <div class="form-group form-captcha clearfix">
            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
            <img class="captcha-img pull-right" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" alt="CAPTCHA">
            <div class="l-overflow">
                <input class="form-control" type="text" name="captcha_word" maxlength="50" value="" size="15" autocomplete="off" placeholder="<?=Loc::getMessage('AUTH_CAPTCHA_PROMT');?>">
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group clearfix">
        <?php if ($arResult['STORE_PASSWORD'] == 'Y'): ?>
            <div class="checkbox form-auth__save">
                <label>
                    <input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y">
                    <svg class="checkbox__icon icon-check icon-svg"><use xlink:href="#svg-check"></use></svg>
                    <?=Loc::getMessage('AUTH_REMEMBER_SHORT')?>
                </label>
            </div>
        <?php endif; ?>
        <div class="l-overflow">
            <input class="btn btn-primary" type="submit" name="Login" value="<?=Loc::getMessage('AUTH_AUTHORIZE')?>">
        </div>
    </div>

    <?php $frame->end(); ?>

</form>

<?php if ($arResult['AUTH_SERVICES']): ?>
    <?
    $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat",
        array(
            "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
            "CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
            "AUTH_URL" => $arResult["AUTH_URL"],
            "POST" => $arResult["POST"],
            "SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
            "FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
            "AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
        ),
        $component,
        array("HIDE_ICONS"=>"Y")
    );
    ?>
<?php endif; ?>

<?php if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"): ?>
    <div class="fancybox-footer">
        <a class="js-ajax_link" href="<?=$arResult["AUTH_REGISTER_URL"]?>" title="<?=Loc::getMessage('REGISTER_TITLE')?>" rel="nofollow"><?=Loc::getMessage('AUTH_REGISTER')?></a>
    </div>
<?php endif; ?>
<script>
RSAL_PlaceHolderForIE();
<?php if (strlen($arResult['LAST_LOGIN']) > 0): ?>
    try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?php else: ?>
	try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?php endif; ?>
</script>
	</div>
</div>

<?php //$templateData['TEMPLATE_HTML'] = ob_get_flush(); ?>

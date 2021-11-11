<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

CJSCore::Init();

$request = Application::getInstance()->getContext()->getRequest();

if ($request->get('backurl') && strlen($request->get('backurl')) > 0) {
	$arResult['BACKURL'] = htmlspecialchars($request->get('backurl'));
}
$arResult["POST"]['backurl'] = $arResult['BACKURL'];
//ob_start();
?>
<div class="form row">
    <div class="col col-md-4">

<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>

<?if($arResult["FORM_TYPE"] == "login"):?>


<form class="js-ajax_form" id="auth_form_popup" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" data-fancybox-title="<?=Loc::getMessage('AUTH_BLOCK_TITLE')?>">

<?foreach ($arResult["POST"] as $key => $value):?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>">
<?endforeach?>

	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="AUTH">

	<div class="form-group">
		<input class="form-control" type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult['USER_LOGIN']?>" placeholder="<?=Loc::getMessage('AUTH_LOGIN')?>">
        <script>
            BX.ready(function() {
                var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
                if (loginCookie)
                {
                    var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
                    var loginInput = form.elements["USER_LOGIN"];
                    loginInput.value = loginCookie;
                }
            });
        </script>
	</div>

	<div class="form-group form-group-password has-feedback">
		<input class="form-control" type="password" name="USER_PASSWORD" maxlength="50" placeholder="<?=Loc::getMessage('AUTH_PASSWORD')?>">
		<a class="form-control-feedback js-ajax_link" href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" title="<?=Loc::getMessage('AUTH_FORGOT_PASSWORD_TITLE')?>" rel="nofollow"><?=Loc::getMessage('AUTH_FORGOT_PASSWORD_2')?></a>
		<?/*if($arResult["SECURE_AUTH"]):?>
			<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
				<div class="bx-auth-secure-icon"></div>
			</span>
			<noscript>
			<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
				<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
			</span>
			</noscript>
			<script type="text/javascript">
			document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
			</script>
		<?endif*/?>
	</div>

	<?php if ($arResult['CAPTCHA_CODE']): ?>
		<div class="form-group form-captcha clearfix">
			<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
			<img class="captcha-img pull-right" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" alt="CAPTCHA">
			<div class="l-overflow">
				<input class="form-control" placeholder="<?=Loc::getMessage('AUTH_CAPTCHA_PROMT');?>" type="text" name="captcha_word" maxlength="50" value="" size="15" autocomplete="off">
			</div>
		</div>
	<?php endif; ?>

	<div class="form-group clearfix">
		<?php if ($arResult['STORE_PASSWORD'] == 'Y'): ?>
			<div class="checkbox form-auth__save">
				<label>
					<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y">
					<svg class="checkbox__icon icon-check icon-svg"><use xlink:href="#svg-check"></use></svg>
					<?=Loc::getMessage('AUTH_REMEMBER_SHORT')?>
				</label>
			</div>
		<?php endif; ?>
		<div class="l-overflow">
			<input class="btn btn-primary" type="submit" name="Login" value="<?=Loc::getMessage('AUTH_LOGIN_BUTTON')?>">
		</div>
	</div>

</form>

<?if($arResult["AUTH_SERVICES"]):?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat",
    array(
        "AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
        "AUTH_URL"=>$arResult["AUTH_URL"],
        "POST"=>$arResult["POST"],
        "POPUP"=>"N",
        "SUFFIX"=>"form",
    ),
    $component,
    array("HIDE_ICONS"=>"Y")
);
?>
<?endif?>

<?php if ($arResult['NEW_USER_REGISTRATION'] == 'Y'): ?>
    <div class="fancybox-footer">
        <a class="js-ajax_link" href="<?=$arResult["AUTH_REGISTER_URL"]?>" title="<?=Loc::getMessage('AUTH_REGISTER')?>" rel="nofollow"><?=Loc::getMessage('AUTH_REGISTER')?></a>
    </div>
<?php endif; ?>

<?
elseif($arResult["FORM_TYPE"] == "otp"):
?>

<form class="js-ajax_form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" data-fancybox-title="<?=Loc::getMessage('AUTH_BLOCK_TITLE')?>">
	<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
	<?endif?>

	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="OTP">
	<div class="form-group">
		<input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off" placeholder="<?echo GetMessage("auth_form_comp_otp")?>">
	</div>

	<?php if ($arResult['CAPTCHA_CODE']): ?>
	<div class="form-group clearfix">
		<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
		<img class="captcha-img pull-right" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" alt="CAPTCHA">
		<div class="l-overflow">
			<input class="form-control" placeholder="<?=Loc::getMessage('AUTH_CAPTCHA_PROMT');?>" type="text" name="captcha_word" maxlength="50" value="" size="15">
		</div>
	</div>
	<?php endif; ?>

	<div class="form-group clearfix">
		<?php if ($arResult['REMEMBER_OTP'] == 'Y'): ?>
			<div class="checkbox form-auth__save">
				<label>
					<input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y">
					<svg class="checkbox__icon icon-check icon-svg"><use xlink:href="#svg-check"></use></svg>
					<?=Loc::getMessage('auth_form_comp_otp_remember')?>
				</label>
			</div>
		<?php endif; ?>
		<div class="l-overflow">
			<input class="btn btn-primary" type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>">
		</div>
	</div>

	<div class="fancybox-footer">
        <!--noindex-->
            <a class="js-ajax_link" href="<?=$arResult["AUTH_LOGIN_URL"]?>" title="<?=Loc::getMessage('auth_form_comp_auth')?>" rel="nofollow"><?=Loc::getMessage('auth_form_comp_auth')?></a>
        <!--/noindex-->
	</div>

</form>

<?
else:
?>
<form class="js-ajax_form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult['AUTH_URL']?>" data-fancybox-title="<?=Loc::getMessage('AUTH_BLOCK_TITLE')?>">
    <div class="form-group text-center">
        <svg class="form__icon icon-svg"><use xlink:href="#svg-user"></use></svg>
    </div>
    
    <?=ShowMessage(array('MESSAGE'=>GetMessage('AUTH_TRUE'),'TYPE'=>'OK'))?>

    <div class="form-group btn-group">
        <input type="hidden" name="logout" value="yes" />
        <a class="btn btn-primary" href="<?=$arResult['BACKURL']?>" onclick="window.appSLine.closePopup();"><?=Loc::getMessage('RS_SLINE.BSAF_FANCYBOX.CONTINUE')?></a>
    </div>
    <script>
	if (window.jQuery && appSLine != undefined)
	{
		appSLine.closePopup({
		<?php if (strlen($arResult['BACKURL']) > 0): ?>
			backurl: <?=\Bitrix\Main\Web\Json::encode($arResult['BACKURL'])?>,
		<?php endif; ?>
		});
	}
    </script>
</form>
<?
/*
<form action="<?=$arResult["AUTH_URL"]?>">
	<table width="95%">
		<tr>
			<td align="center">
				<?=$arResult["USER_NAME"]?><br />
				[<?=$arResult["USER_LOGIN"]?>]<br />
				<a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("AUTH_PROFILE")?></a><br />
			</td>
		</tr>
		<tr>
			<td align="center">
			<?foreach ($arResult["GET"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
			<input type="hidden" name="logout" value="yes" />
			<input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
			</td>
		</tr>
	</table>
</form>
*/?>
<?endif?>

	</div>
</div>

<?php //$templateData['TEMPLATE_HTML'] = ob_get_flush(); ?>
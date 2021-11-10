<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

$request = Application::getInstance()->getContext()->getRequest();

if(empty($arParams['DISABLED_FIELDS'])) {
	$arParams['DISABLED_FIELDS'] = array();
}

if ($request->get('backurl') && strlen($request->get('backurl')) > 0) {
	$arResult['BACKURL'] = htmlspecialchars($request->get('backurl'));
}
?>
<div class="form row">
	<div class="col col-md-4">
	<?php if(!empty($arParams['FORM_DESCRIPTION'])): ?>
		<div class="well"><?=$arParams['FORM_DESCRIPTION']?></div><br>
	<?php endif; ?>

	<?php if ($arResult['LAST_ERROR'] != ''): ?>
		<div class="alert alert-danger" role="alert"><?=$arResult['LAST_ERROR']?></div><br>
	<?php endif; ?>

	<?php if ($arResult['GOOD_SEND'] == 'Y'): ?>
		<div class="alert alert-success" role="alert"><?=$arResult['GOOD_ORDER_TEXT']?></div><br>
			<?php
			$jsParams = array();
			if (strlen($arResult['BACKURL']) > 0)
			{
				$jsParams['backurl'] = $arResult['BACKURL'];
			}
			?>
			<script>
			if (window.jQuery && appSLine)
			{
				appSLine.closePopup(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
			}
			</script>
	<?php endif; ?>

	<form class="form js-ajax_form" action="<?=$arResult['ACTION_URL']?>" method="POST" data-fancybox-title="<?=Loc::getMessage('MSG_BUY1CLICK')?>">
		<?=bitrix_sessid_post()?>
		<input type="hidden" name="<?=$arParams['REQUEST_PARAM_NAME']?>" value="Y">
		<input type="hidden" name="PARAMS_HASH" value="<?=$arResult['PARAMS_HASH']?>">

		<?php if (strlen($arResult["BACKURL"]) > 0): ?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
		<?php endif ?>

		<?php foreach ($arResult['SYSTEM_FIELDS'] as $arSysFields): ?>
			<input type="hidden" name="<?=$arSysFields['CODE']?>" value="">
		<?php endforeach;?>

		<?php if (is_array($arResult['SHOW_FIELDS']) && count($arResult['SHOW_FIELDS']) > 0): ?>
			<?php foreach ($arResult['SHOW_FIELDS'] as $key => $arField): ?>

				<div class="form-group" <?echo($arField['CODE']=='RS_AUTHOR_ORDER_LIST' ? 'style="display:block"' : '')?>>
					<?php
					$sInputLabel = '';

					if (strlen($arField['NAME']) > 0) {
						$sInputLabel = $arField['NAME'];
					} else {
						$sInputLabel = Loc::getMessage('MSG_'.$arField['CODE']);
					}

					if ($arField['REQUIRED_FIELDS'] == 'Y') {
						$sInputLabel .= '*';
					}

					if ($sInputLabel != '') {
						$sInputLabel = ' placeholder="'.$sInputLabel.'"';
					}
					?>
					<input class="form-control<?=($arField['CODE'] == 'PHONE' ? ' js-mask_phone' : '');?>" id="<?=$arField['CODE']?>" value="<?=$arField['DEFAULT_VALUE'];?>" name="<?=$arField['CODE']?>" type="text"<?=$sInputLabel?>>
				</div>

			<?php endforeach; ?>
		<?php endif; ?>

		<?php if($arParams['ALFA_USE_CAPTCHA'] == 'Y'): ?>
		<div class="form-group clearfix">
			<input type="hidden" name="captcha_sid" value="<?=$arResult['CATPCHA_CODE']?>">
			<img class="captcha-img pull-right" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CATPCHA_CODE']?>" alt="CAPTCHA">
			<div class="l-overflow">
				<input class="form-control" id="captcha_<?=$arResult['WEB_FORM_NAME']?>" type="text" name="captcha_word" size="30" maxlength="50" value="" autocomplete="off" placeholder="<?=Loc::getMessage('MSG_CAPTHA');?>*">
			</div>
		</div>
		<?php endif; ?>

		<?php
		$sBtnSubmitText = isset($arParams['~MESS_SUBMIT'])
			? $arParams['~MESS_SUBMIT']
			: Loc::getMessage('MSG_SUBMIT');
		?>
		<?php if ($arParams['USER_CONSENT'] == 'Y'):?>
			<div class="form-group">
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.userconsent.request",
					"form",
					array(
						"ID" => $arParams["USER_CONSENT_ID"],
						"IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
						"IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
						"AUTO_SAVE" => "Y",
						// "ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
						// "ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
						"INPUT_NAME" => "FORM",
						// 'SUBMIT_EVENT_NAME' => '',
						'REPLACE' => array(
							'button_caption' => $sBtnSubmitText,
							// 'fields' => array()
						)
					)
				);?>
			</div>
		<?php endif; ?>

		<div class="form-group">
			<input class="btn btn-primary" type="submit" name="submit" value="<?=Loc::getMessage("MSG_SUBMIT")?>">
		</div>

		<div><?=Loc::getMessage('MSG_REQUIRED_FIELDS')?></div>
	</form>
	</div>

<?=CJSCore::Init(array('ls'), true)?>
<script>
if (window.jQuery) {
  $(function() {
	'use strict';
	BX.ready(function(){
	  var ajaxData = BX.localStorage.get('ajax_data'),
		  key;
	  if (ajaxData) {
		if(typeof ajaxData === 'string' || ajaxData instanceof String) {
		  ajaxData = BX.parseJSON(ajaxData);
		}
		for (key in ajaxData) {
		  $(".form [name=" + key + "]").val(ajaxData[key]);
		}

	  }
	});
  });
}
</script>
</div>


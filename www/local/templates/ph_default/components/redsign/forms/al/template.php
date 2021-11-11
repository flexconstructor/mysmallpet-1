<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$request = Application::getInstance()->getContext()->getRequest();

if (!isset($arParams['DISABLED_FIELDS']) || !is_array($arParams['DISABLED_FIELDS'])) {
    $arParams['DISABLED_FIELDS'] = array();
}

if ($request->get('backurl') && strlen($request->get('backurl')) > 0) {
	$arResult['BACKURL'] = htmlspecialchars($request->get('backurl'));
}
?>
<div class="form row">
    <div class="col col-md-4">
        <?php if (count($arResult['MESSAGES']['ERRORS']) > 0): ?>
            <div class="alert alert-danger"><?php foreach ($arResult['MESSAGES']['ERRORS'] as $msg): ?><?=$msg?><br><?php endforeach; ?></div><br>
        <?php endif; ?>
        <?php if (count($arResult['MESSAGES']['SUCCESS']) > 0): ?>
            <div class="alert alert-success"><?php foreach ($arResult['MESSAGES']['SUCCESS'] as $msg): ?><?=$msg?><br><?php endforeach; ?></div><br>
			<?php
			$jsParams = array();
			if (strlen($arResult['BACKURL']) > 0)
			{
				$jsParams['backurl'] = $arResult['BACKURL'];
			}
			?>
			<script>
			if (window.jQuery && appSLine) {
				appSLine.closePopup(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
			}
			</script>
        <?php endif; ?>

        <form class="form" action="<?=POST_FORM_ACTION_URI?>" method="POST">
            <?=bitrix_sessid_post()?>
            <input type="hidden" name="PARAMS_HASH" value="<?=$arResult['PARAMS_HASH']?>">

			<?php if (strlen($arResult["BACKURL"]) > 0): ?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
			<?php endif ?>

            <?php
            foreach ($arResult['FIELDS'] as $key => $arField):
                $disabled = in_array($arField['CODE'], $arParams['DISABLED_FIELDS']) ? ' disabled' : '';

                $sInputLabel = $arField['NAME'];
                if ($arField['IS_REQUIRED'] == 'Y') {
                    $sInputLabel .= '*';
                }
                if ($sInputLabel != '') {
                    $sInputLabel = ' placeholder="'.$sInputLabel.'"';
                }
            ?>
                <?php if ($arField['PROPERTY_TYPE'] == 'S'): ?>
                    <div class="form-group">
                        <?php if ($arField['USER_TYPE'] == 'HTML'): ?>
                            <textarea style="max-width: 100%;" id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" class="form-control<?=$disabled?>"<?=$sInputLabel?>><?=$arField['CURRENT_VALUE']?></textarea>
                        <?php elseif ($arField['USER_TYPE'] == 'Date'): ?>
                            <input type="date" id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="form-control<?=$disabled?>"<?=$disabled.$sInputLabel?>>
                        <?php elseif ($arField['USER_TYPE'] == 'DateTime'): ?>
                            <input type="datetime-local" id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="form-control<?=$disabled?>"<?=$disabled.$sInputLabel?>>
                        <?php else: ?>
                            <input type="text" id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="form-control<?=$disabled?>"<?=$disabled.$sInputLabel?>>
                        <?php endif; ?>
                    </div>
                <?php elseif ($arField['PROPERTY_TYPE'] == 'L' && is_array($arField['VALUES'])): ?>
                    <div class="form-group">
                        <select class="form-control<?=$disabled?>" name="FIELD_<?=$arField['CODE']?>" id="FIELD_<?=$arField['CODE']?>"<?=$disabled?>>
                            <option value=""><?=$sInputLabel?></option>
                            <?php foreach ($arField['VALUES'] as $i => $arValue): ?>
                                <option <?php if ((empty($arField['CURRENT_VALUE']) && $i == 0) || $arField['CURRENT_VALUE'] == $arValue['ID']): ?>selected="selected"<?php endif; ?> value="<?=$arValue['ID']?>"><?=$arValue['VALUE']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($arResult['USE_CAPTCHA'] == 'Y'): ?>
                <div class="form-group form-captcha clearfix">
                    <input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
                    <img class="captcha-img pull-right" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
                    <div class="l-overflow">
                        <input class="form-control" type="text" name="captcha_word" size="30" maxlength="50" value="" autocomplete="off" placeholder="<?=Loc::getMessage('MSG_CAPTHA'); ?>*">
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
                <input type="submit" class="btn btn-primary" name="submit" value="<?=Loc::getMessage('MSG_SUBMIT')?>">
            </div>
            <div class="form__bottom-ps"><span class="req">*</span> - <?=Loc::getMessage('MSG_REQUIRED_FIELDS')?></div>
        </form>
    </div>
<script>
   $(function() {
       'use strict';
       if (BX.localStorage) {
         var ajaxData = BX.localStorage.get('ajax_data'),
             key;
         if (ajaxData) {
           if (typeof ajaxData === 'string' || ajaxData instanceof String) {
             ajaxData = JSON.parse(ajaxData);
           }
           for(key in ajaxData) {
             //$(".form [name=" + key +"]").val(ajaxData[key]);
           }
         }
       }
   });
</script>
</div>


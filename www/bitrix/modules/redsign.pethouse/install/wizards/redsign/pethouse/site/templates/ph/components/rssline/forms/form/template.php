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
        <?php if(!empty($arParams['FORM_DESCRIPTION'])): ?>
            <div class="well"><?=$arParams['FORM_DESCRIPTION']?></div><br>
        <?php endif; ?>

        <?php if ($arResult['LAST_ERROR'] != ''): ?>
            <div class="alert alert-danger" role="alert"><?=$arResult['LAST_ERROR']?></div><br>
        <?php endif; ?>

        <?php if ($arResult['GOOD_SEND'] == 'Y'): ?>
            <div class="alert alert-success" role="alert"><?=$arResult['MESSAGE_AGREE']?></div><br>
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

        <form class="js-ajax_form" action="<?=$arResult['ACTION_URL']?>" method="POST" data-fancybox-title="<?=Loc::getMessage('MSG_BUY1CLICK')?>">
            <?=bitrix_sessid_post()?>
            <input type="hidden" name="<?=$arParams['REQUEST_PARAM_NAME']?>" value="Y">
            <input type="hidden" name="PARAMS_HASH" value="<?=$arResult['PARAMS_HASH']?>">

			<?php if (strlen($arResult["BACKURL"]) > 0): ?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
			<?php endif ?>

            <?php foreach ($arResult['FIELDS'] as $key => $arField): ?>
                <?php
                if($arField['SHOW'] !== 'Y') {
                    continue;
                }
                ?>
                <div class="form-group">
                    <?php
                    $sInputLabel = '';

                    if ($arField['EXT'] == "Y") {
                        $sInputLabel = $arParams['RS_FLYAWAY_FIELD_'.$arField['INDEX'].'_NAME'];
                    } elseif (!empty($arParams['INPUT_NAME_'.$arField['CONTROL_NAME']])) {
                        $sInputLabel = $arParams['INPUT_NAME_'.$arField['CONTROL_NAME']];
                    } else {
                        $sInputLabel = Loc::getMessage("MSG_".$arField['CONTROL_NAME']);
                    }

                    if(in_array($arField['CONTROL_NAME'], $arParams['REQUIRED_FIELDS'])) {
                        $sInputLabel .= '*';
                    }

                    if ($sInputLabel != '') {
                        $sInputLabel = ' placeholder="'.$sInputLabel.'"';
                    }
                    ?>
                    <?php if ($arField['CONTROL_NAME'] == 'RS_TEXTAREA'): ?>
                        <textarea name="<?=$arField['CONTROL_NAME']?>" class="form-control"<?=$sInputLabel?>><?=$arField['HTML_VALUE']?></textarea>
                    <?php else: ?>
                        <input type="text" value="<?=$arField['HTML_VALUE']?>" name="<?=$arField['CONTROL_NAME']?>"
                            class="<?php if(in_array($arField['CONTROL_NAME'], $arParams['REQUIRED_FIELDS'])) echo 'req-input';?> form-control"
                            <?php if(in_array($arField['CONTROL_NAME'], $arParams['DISABLED_FIELDS'])) echo ' readonly';?>
                            <?=$sInputLabel?>
                        />
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if($arParams['USE_CAPTCHA'] == 'Y'): ?>
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
                <input class="btn btn-primary" type="submit" name="submit" value="<?=$sBtnSubmitText?>">
            </div>

            <div><?=Loc::getMessage('MSG_REQUIRED_FIELDS')?></div>
        </form>
    </div>
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
          $(".form [name=" + key +"]").val(ajaxData[key]);
        }

      }
    });
  });
}
</script>

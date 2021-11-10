<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

$request = Application::getInstance()->getContext()->getRequest();

if ($request->get('backurl') && strlen($request->get('backurl')) > 0) {
	$arResult['BACKURL'] = htmlspecialchars($request->get('backurl'));
}

ob_start();
?>
<form name="locforma" class="cities js-ajax_form" action="<?=$arResult['ACTION_URL']?>" method="POST" data-fancybox-title="<?=Loc::getMessage('RS_SLINE.RADL_LIST.YOUR_CITY')?>">
    <?/*<script type="text/javascript" src="<?=$templateFolder?>/script.js"></script>*/?>
    <?echo bitrix_sessid_post(); ?>

    <?php $frame = $this->createFrame()->begin(''); ?>
	<input type="hidden" name="<?=$arParams['REQUEST_PARAM_NAME']?>" value="Y">
	<input type="hidden" name="PARAMS_HASH" value="<?=$arParams['PARAMS_HASH']?>">
	<?php if (0 < strlen($arResult['BACKURL'])): ?>
		<input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>">
	<?php endif; ?>
    
	<?php ShowMessage($arResult['ERROR_MESSAGE']); ?>
    
	<?php if (is_array($arResult['LOCATIONS']) && 0 < count($arResult['LOCATIONS'])): ?>
		<h2><?=Loc::getMessage('RS_SLINE.RADL_LIST.SELECT_CITY')?></h2>
		<div class="cities__list row">
			<?php $bChecked = false; ?>
			<?php foreach ($arResult['LOCATIONS'] as $arLocation): ?>
                <div class="col-xs-6 col-lg-2">
                    <div class="radio">
                    <label>
                        <input
                            type="radio"
                            name="<?=$arParams['CITY_ID']?>_radio"
                            value="<?=$arLocation['ID']?>"
                            onclick="rsLocationSelect(this, '<?=$arParams['CITY_ID']?>')"
                            <?php
                            if ($arResult['LOCATION']['ID'] == $arLocation['ID']):
                                $bChecked = true;
                            ?>
                                checked
                            <?php endif; ?>
                        />
                        <i class="radio__icon"></i>
                        <?php
                        if ('' != $arLocation['CITY_NAME']) {
                            echo $arLocation['CITY_NAME'];
                        } else if ('' != $arLocation['REGION_NAME']) {
                            echo $arLocation['REGION_NAME'];
                        } else if ('' != $arLocation['COUNTRY_NAME']) {
                            echo $arLocation['COUNTRY_NAME'];
                        }
                        ?>
                    </label>
                    </div>
                </div>
			<?php endforeach; ?>
            
			<?php if (!$bChecked && $arResult['LOCATION']['ID']): ?>
                <div class="col-xs-6 col-lg-2">
                    <div class="radio">
                        <label>
                            <input
                                type="radio"
                                name="<?=$arParams['CITY_ID']?>_radio"
                                value="<?=$arResult['LOCATION']['ID']?>"
                                onclick="rsLocationSelect(this, '<?=$arParams['CITY_ID']?>')"
                                checked="checked"
                            />
                            <i class="radio__icon"></i>
                            <?php
                            if ('' != $arResult['LOCATION']['CITY_NAME']) {
                                echo $arResult['LOCATION']['CITY_NAME'];
                            } else if ('' != $arResult['LOCATION']['REGION_NAME']) {
                                echo $arResult['LOCATION']['REGION_NAME'];
                            } else if ('' != $arResult['LOCATION']['COUNTRY_NAME']) {
                                echo $arResult['LOCATION']['COUNTRY_NAME'];
                            } else {
                                echo Loc::getMessage('RS_SLINE.RADL_HEAD.SELECT_CITY');
                            }
                            ?>
                        </label>
                    </div>
                </div>
			<?php endif; ?>
		</div>
        <br>
		<h2><?=Loc::getMessage('RS_SLINE.RADL_LIST.OR_PRINT_CITY')?></h2>
	<?php else: ?>
        <h2><?=Loc::getMessage('RS_SLINE.RADL_LIST.PRINT_CITY')?></h2>
	<?php endif; ?>
    <div class="form">
        <div class="input-group">
            <?php
            CSaleLocation::proxySaleAjaxLocationsComponent(
                array(
                    'AJAX_CALL' => 'N',
                    'COUNTRY_INPUT_NAME' => 'COUNTRY',
                    'REGION_INPUT_NAME' => 'REGION',
                    'CITY_INPUT_NAME' => $arParams['CITY_ID'],
                    'CITY_OUT_LOCATION' => 'Y',
                    'LOCATION_VALUE' => $arResult['LOCATION']['ID'],
                    //'ORDER_PROPS_ID' => $arProperties['ID'],
                    'ONCITYCHANGE' => '',
                    'SIZE1' => '',
                    //'PROPERTY_NAME' => $arProperties['NAME'],
                ),
                array(
                    //'ID' => $arProperties['VALUE'],
                    'CODE' => '',
                    'SHOW_DEFAULT_LOCATIONS' => 'Y',
                    'DISABLE_KEYBOARD_INPUT' => 'Y',
                    //'PROPERTY_NAME' => $arProperties['NAME'],
                ),
                'popup',
                true
            );
            ?>
            <div class="input-group-btn">
                <button class="btn btn-primary" type="submit" name="submit"><?=Loc::getMessage('RS_SLINE.RADL_LIST.IS_MY_CITY')?></button>
            </div>
        </div>
    </div>
	<?php $frame->end(); ?>
    
    <script>
var rsLocationSelect = function(input, city_id){
	var $input = $(input),
        $form = $(input.form);
    
	$form.find('input[name="'+ city_id +'"]').val($input.val());
	$form.find('[type="submit"]').trigger('click');
};
    </script>
</form>
<?php
$templateData['TEMPLATE_HTML'] = ob_get_flush();
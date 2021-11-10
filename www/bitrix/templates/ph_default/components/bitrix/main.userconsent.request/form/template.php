<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

$config = \Bitrix\Main\Web\Json::encode($arResult['CONFIG']);
// $inputId = 'CONSTENT_'.$arParams['ID'].'_'.htmlspecialcharsbx($arParams['INPUT_NAME']);
$inputId = 'CONSTENT_'.$this->randString().'_'.htmlspecialcharsbx($arParams['INPUT_NAME']);
$component = $this->getComponent();
$templateData['INPUT_ID'] = $inputId;
?>
<div class="checkbox">
	<label data-bx-user-consent="<?=htmlspecialcharsbx($config)?>">
		<input type="checkbox" value="Y" <?=($arParams['IS_CHECKED'] ? 'checked' : '')?> name="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>" id="<?=$inputId?>" required>
		<svg class="checkbox__icon icon-check icon-svg"><use xlink:href="#svg-check"></use></svg>
		<?=$arResult['INPUT_LABEL']?>
	</label>
</div>

<script type="text/html" data-bx-template="main-user-consent-request-loader">
	<div class="fancybox-container fancybox-is-open fancybox-can-drag"><?php
		?><div class="fancybox-bg"></div><?php
		?><div class="fancybox-inner"><?php
		?><div class="fancybox-stage"><?php
		?><div class="fancybox-slide fancybox-slide--current"><?php
		?><div class="fancybox-content"><?php
			?><div data-bx-head="" class="fancybox-title"></div><?php
			?><div class="main-user-consent-request-popup-body"><?php
				?><div data-bx-loader="" class="main-user-consent-request-loader"><?php 
					include($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/assets/html/load.html');
				?></div><?php
				?><div data-bx-content="" class="form">
					<div class="form-group">
						<textarea data-bx-textarea="" class="form-control" readonly style="height:200px"></textarea>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-6">
								<span data-bx-btn-accept="" class="btn btn-primary">Y</span>
							</div>
							<div class="col-xs-6">
								<span data-bx-btn-reject="" class="btn btn-grey">N</span>
							</div>
						</div>
					</div>
				</div><?php
			?></div><?php
		?></div><?php
		?></div><?php
		?></div><?php
		?></div><?php
	?></div>
</script>
<?php
$arMessages = Loc::loadLanguageFile(__DIR__.'/user_consent.php');
?>
<?php if ($request->isAjaxRequest()): ?>
<script>
if (!!BX.UserConsent) {  
	BX.UserConsent.loadFromForms();
} else {
  BX.loadScript('<?=$templateFolder?>/user_consent.js', function(){
	BX.message(<?=CUtil::PhpToJSObject($arMessages);?>);
    BX.UserConsent.loadFromForms();
  });
}
</script>
<?php endif; ?>
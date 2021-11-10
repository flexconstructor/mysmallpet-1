<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

?>
<form id="location_head" class="auth_top__item" action="<?=$arResult['ACTION_URL']?>" method="POST">
    <?php
	echo bitrix_sessid_post();
	ShowMessage($arResult['ERROR_MESSAGE']);
	$frame = $this->createFrame('location_head',false)->begin();
	$frame->setBrowserStorage(true);
    ?>
    <input type="hidden" name="<?=$arParams['REQUEST_PARAM_NAME']?>" value="Y">
	<input type="hidden" name="PARAMS_HASH" value="<?=$arParams['PARAMS_HASH']?>">
	<a class="auth_top__link js-ajax_link" data-fancybox="mycity" data-type="ajax" href="<?=SITE_DIR?>mycity/" title="<?=getMessage('RS_SLINE.RADL_HEAD.SELECT_CITY')?>">
        <svg class="icon icon-svg icon-marker"><use xlink:href="#svg-marker"></use></svg>
        <?php
        if ('' != $arResult['LOCATION']['CITY_NAME']) {
            echo $arResult['LOCATION']['CITY_NAME'];
        } else if ('' != $arResult['LOCATION']['REGION_NAME']) {
            echo $arResult['LOCATION']['REGION_NAME'];
        } else if ('' != $arResult['LOCATION']['COUNTRY_NAME']) {
            echo $arResult['LOCATION']['COUNTRY_NAME'];
        } else {
            echo getMessage('RS_SLINE.RADL_HEAD.YOUR_CITY');
        }
        ?>
    </a>
    <?php $frame->beginStub(); ?>
		<a class="auth_top__link js-ajax_link" data-fancybox="mycity" href="<?=SITE_DIR?>mycity/" title="<?=getMessage('RS_SLINE.RADL_HEAD.SELECT_CITY')?>">
			<svg class="icon icon-svg icon-marker"><use xlink:href="#svg-marker"></use></svg>
            <?=getMessage('RS_SLINE.RADL_HEAD.YOUR_CITY')?>
		</a>
	<?php $frame->end(); ?>
</form>
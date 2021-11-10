<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */

$config = \Bitrix\Main\Web\Json::encode($arResult['CONFIG']);
?>
<label data-bx-user-consent="<?=htmlspecialcharsbx($config)?>">
	<input type="checkbox" value="Y" <?=($arParams['IS_CHECKED'] ? 'checked' : '')?> name="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>">
    <svg class="checkbox__icon icon-check icon-svg"><use xlink:href="#svg-check"></use></svg>
	<?=$arResult['INPUT_LABEL']?>
</label>

<script type="text/html" data-bx-template="main-user-consent-request-loader">
	<div class="fancybox-container fancybox-is-open fancybox-can-drag">
        <div class="fancybox-bg"></div>
		<div class="fancybox-inner">
		<div class="fancybox-stage">
		<div class="fancybox-slide fancybox-slide--current">
		<div>
			<div data-bx-head="" class="fancybox-title"></div>
			<div class="main-user-consent-request-popup-body">
				<div data-bx-loader="" class="main-user-consent-request-loader">
					<?php include($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/assets/html/load.html'); ?>
				</div>
				<div data-bx-content="" class="form">
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
				</div>
			</div>
		</div>
		</div>
		</div>
		</div>
	</div>
</script>

<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$frame = $this->createFrame()->begin();

$injectId = 'sale_gift_product_'.rand();

$currentProductId = (int)$arResult['POTENTIAL_PRODUCT_TO_BUY']['ID'];

if (isset($arResult['REQUEST_ITEMS']))
{
	CJSCore::Init(array('ajax'));

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.sale.gift.product'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.sale.gift.product');

	?>

	<div id="<?=$injectId?>" class="sale_gift_product_container"></div>

	<script type="text/javascript">
		BX.ready(function(){

			var currentProductId = <?=CUtil::JSEscape($currentProductId)?>;
			var giftAjaxData = {
				'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
				'template': '<?=CUtil::JSEscape($signedTemplate)?>',
				'site_id': '<?=CUtil::JSEscape(SITE_ID)?>'
			};

			bx_sale_gift_product_load(
				'<?=CUtil::JSEscape($injectId)?>',
				giftAjaxData
			);

			BX.addCustomEvent('onCatalogStoreProductChange', function(offerId){
				if(currentProductId == offerId)
				{
					return;
				}
				currentProductId = offerId;
				bx_sale_gift_product_load(
					'<?=CUtil::JSEscape($injectId)?>',
					giftAjaxData,
					{offerId: offerId}
				);
			});
		});
	</script>

	<?
	$frame->end();
	return;
}


$sTemplateExtPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/template_ext/catalog.section/al/template.php';
if (file_exists($sTemplateExtPath)) {
    include($sTemplateExtPath);    
}
?>
<?$frame->beginStub();?>
<?$frame->end();?>
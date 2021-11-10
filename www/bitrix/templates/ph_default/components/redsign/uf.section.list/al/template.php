<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (is_array($arResult['SECTIONS']) && count($arResult['SECTIONS']) > 0) {
    $iPicrureHeightMax = max(array_map(
        function($arSection){
            return intval($arSection['PICTURE']['RESIZE']['height']);
        },
        array_filter($arResult['SECTIONS'], function($arSection) {
            return (
                is_array($arSection['PICTURE'])
            );
        })
    ));
}

global $bRSHomeTabShow;
$tabId = $this->getEditAreaId('tab');
?>
<?php if (is_array($arResult['SECTIONS']) && count($arResult['SECTIONS']) > 0): ?>
<div id="<?=$tabId?>" class="tab-pane<?php if (!$bRSHomeTabShow && !empty($arResult['SECTIONS'])): ?> active<?php endif; ?>">
    <div class="carousel js-carousel_section owl-shift">
        <?php foreach ($arResult['SECTIONS'] as $arSection): ?>
            <a class="carousel__item" href="<?=$arSection['SECTION_PAGE_URL']?>">
                <?php if (is_array($arSection['PICTURE'])): ?>
                    <span class="carousel__pic"<?php if ($iPicrureHeightMax > 0): ?> style="height:<?=$iPicrureHeightMax?>px"<?php endif; ?>>
                        <img class="carousel__img" src="<?=$arSection['PICTURE']['RESIZE']['src']?>" alt="<?=$arSection['ALT']?>" title="<?=$arSection['TITLE']?>">
                    </span>
                <?php endif; ?>
                <span class="carousel__name text_fade"><?=$arSection['NAME']?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php $this->SetViewTarget('main_section_tab'); ?>

<?php if (is_array($arResult['SECTIONS']) && count($arResult['SECTIONS']) > 0): ?>
	<li<?php if(!$bRSHomeTabShow): ?> class="active"<?php endif; ?>>
		<a href="#<?=$tabId?>" data-toggle="tab">
			<?=(0 < strlen($arParams['BLOCK_TITLE']) ? $arParams['BLOCK_TITLE'] : Loc::getMessage('RS_SLINE.UFSL_AL.BLOCK_TITLE_SAMPLE')); ?>
		</a>
	</li>
<? endif; ?>

<?php $this->EndViewTarget(); ?>
<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

?><div class="news-list"><?
if ($arParams['DISPLAY_TOP_PAGER'])
{
	echo $arResult['NAV_STRING'];?><br /><?
}

foreach ($arResult['ITEMS'] as $arItem)
{
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="news-item clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="row">
			<div class="col-xs-5 col-sm-4 col-md-2 news_pic_sm">
				<?php
				if ($arItem['PREVIEW_PICTURE']['SRC'] != '')
				{
					?>
					<div class="news-item__image">
						<?php
						if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS']))
						{
							?>
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
							<?php
						}
						?>
						<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
						<?php
						if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS']))
						{
							?>
							</a>
							<?php
						}
						?>
					</div>
					<?php
				}
				elseif ($arItem['DETAIL_PICTURE']['SRC'] != '')
				{
					?>
					<div class="news-item__image">
						<?php
						if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS']))
						{
							?>
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
							<?php
						}
						?>
						<img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" alt="<?=$arItem['DETAIL_PICTURE']['ALT']?>" title="<?=$arItem['DETAIL_PICTURE']['TITLE']?>">
						<?php
						if(!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS']))
						{
							?>
							</a>
							<?php
						}
						?>
					</div>
					<?php
				}
			?>
			</div>
			<div class="col-xs-7 col-sm-8 col-md-10">
				<div class="news-item__name">
					<?php
					if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS']))
					{
						?>
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
						<?php
					}
					echo $arItem['NAME'];
					if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS']))
					{
						?>
						</a>
						<?php
					}
					?>
				</div>
				<?php
				if ($arParams['DISPLAY_PREVIEW_TEXT']=="Y" && $arItem['PREVIEW_TEXT'])
				{
					?>
					<div class="clearfix">
						<div class="news-item__preview"><?=$arItem['PREVIEW_TEXT']?></div>
						<?php
						if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS']))
						{
							?>
							<a class="news-item__detail" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=GetMessage('LINK2DETAIL')?>
								<svg class="icon-right-quote icon-svg"><use xlink:href="#svg-right-quote"></use></svg>
							</a>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
}

if ($arParams['DISPLAY_BOTTOM_PAGER'])
{
	?>
	<br><?=$arResult['NAV_STRING']?>
	<?php
}
?>
</div>
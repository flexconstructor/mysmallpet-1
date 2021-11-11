<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

$request = Application::getInstance()->getContext()->getRequest();
?>
        <?php if ($request->isAjaxRequest() || $request->get('rs_ajax__page') == 'Y'): ?>
            </div>
            <?php die(); ?>
        <?php endif; ?>

            <?php
			if (!function_exists('showTemplatePageEnd'))
			{
				function showTemplatePageEnd()
				{
					global $APPLICATION;

					$curPage = $APPLICATION->GetCurPage(true);
					$bAddContainer = true;

					if ($APPLICATION->GetProperty('WIDE_PAGE') == 'Y') {
						$bAddContainer = false;
					}

					// main page
					if ($curPage == SITE_DIR.'index.php') {
						$bAddContainer = false;
					}


					ob_start();

					if ($bAddContainer):
						?>
						</div>
						<?php
					endif;

					$sHtmlContent = ob_get_clean();
					return $sHtmlContent;
				}
            }

            $APPLICATION->AddBufferContent('showTemplatePageEnd');
            ?>
        </main>

        <footer id="footer" class="l-footer">
            <div class="container">
                <div class="l-footer__inner row clearfix">
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="l-footer__logo logo" itemscope itemtype="http://schema.org/Organization">
                            <a href="<?=SITE_DIR?>" itemprop="url">
                                <?$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath(SITE_DIR.'include/company_logo2.php'),
                                    Array(),
                                    Array('MODE'=>'html')
                                );?>
                            </a>

                            <?php if (is_array($arSiteData['SITE_ADDRESS_PATH']) && count($arSiteData['SITE_ADDRESS_PATH']) > 0): ?>
                                <meta itemprop="address" content="<?=implode(', ', $arSiteData['SITE_ADDRESS_PATH'])?>">
                            <?php endif; ?>

                            <?php
                            $sCompanyPhone = $APPLICATION->GetFileContent($_SERVER["DOCUMENT_ROOT"].SITE_DIR.'include/telephone1.php');

                            if ($sCompanyPhone):
                            ?>
                            <?php endif; ?>

                            <meta itemprop="name" content="<?=$arSiteData['SITE_NAME']?>">
                        </div>

                        <div class="l-footer__adds">
                            <div class="l-footer__phone adds recall">
                                <a class="js-ajax_link" href="<?=SITE_DIR?>recall/" data-fancybox="recall" data-type="ajax" title="<?=Loc::getMessage('RS_SLINE.FOOTER.RECALL')?>" rel="nofollow">
									<svg class="btn__icon icon icon-phone icon-svg"><use xlink:href="#svg-phone"></use></svg>
                                    <?=Loc::getMessage('RS_SLINE.FOOTER.RECALL')?>
                                </a>
                                <div class="adds__phone">
                                <?$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath(SITE_DIR.'include/telephone1.php'),
                                    Array(),
                                    Array("MODE"=>"html")
                                );?>
                                </div>
                            </div>
                            <!--<div class="l-footer__phone adds feedback">
                                <a class="js-ajax_link" href="<?=SITE_DIR?>feedback/" data-fancybox="feedback" data-type="ajax" title="<?=Loc::getMessage('RS_SLINE.FOOTER.FEEDBACK_TITLE')?>" rel="nofollow">
									<svg class="btn__icon icon icon-dialog icon-svg"><use xlink:href="#svg-dialog"></use></svg>
</*?=Loc::getMessage('RS_SLINE.FOOTER.FEEDBACK')*/?>
                                </a>
                                <div class="adds__phone">
<?/*$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath(SITE_DIR.'include/telephone2.php'),
                                    Array(),
                                    Array("MODE"=>"html")
);*/?>
                                </div>
                            </div>-->
                        </div>
                    </div>

                    <div class="l-footer__catalog col-xs-6 col-md-3 col-lg-6">
                        <?php
                        $sFooterMenuCatalogPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/footer/menu_catalog.php';
                        if (file_exists($sFooterMenuCatalogPath)) {
                            include($sFooterMenuCatalogPath);
                        }
                        ?>
                    </div>

                    <div class="l-footer__menu col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <?php
                        $sFooterMenuPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/footer/menu_footer.php';
                        if (file_exists($sFooterMenuPath)) {
                            include($sFooterMenuPath);
                        }
                        ?>
                    </div>

                    <?php
                        $sSocServ = $APPLICATION->GetFileContent($_SERVER["DOCUMENT_ROOT"].SITE_DIR.'include/footer/socservice.php');
                    ?>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <!--<div class="l-footer__soc">
<?/*php if ($sSocServ): */?>
                                <div class="l-footer__title"><?=Loc::getMessage('RS_SLINE.FOOTER.JOIN_NOW')?></div>
<?/*$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath(SITE_DIR.'include/footer/socservice.php'),
                                    Array(),
                                    Array("MODE"=>"html")
);*/?>
<?/*php endif; */?>
                        </div>-->
                        <?php
                        $sFooterSubscribePath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/footer/subscribe.php';
                        if (file_exists($sFooterSubscribePath)) {
                            include($sFooterSubscribePath);
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="l-footer__bottom">
                <div class="container">
                    <div class="row">
                        <div class="l-footer__copy col-sm-8">
                            <?$APPLICATION->IncludeFile(
                                $APPLICATION->GetTemplatePath(SITE_DIR.'include/copyright.php'),
                                Array(),
                                Array("MODE"=>"html")
                            );?>
                        </div>
                        <div class="l-footer__dev col-sm-4">
                            <?php // #REDSIGN_COPYRIGHT# ?>
							<!--noindex-->
                            Powered by <a class="developer-copyright" href="https://redsign.ru/" target="_blank" rel="nofollow"><b>ALFA Systems</b></a>
							<!--/noindex-->
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <div style="display:none;">alfaSystems sline pethouse PH91TG62</div>
    <script>$('#svg-icons').setHtmlByUrl({url:appSLine.SITE_TEMPLATE_PATH+'/assets/img/icons.svg?3'});</script>
    <?//$APPLICATION->IncludeFile(SITE_DIR."include/tuning/component.php", Array(), Array("MODE"=>"html"));?>
    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/body_end.php", array(), array("MODE" => "html"))?>
</body>
</html>
<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\ModuleManager;
//use  Bitrix\Main\Web\Uri;

Loc::loadMessages(__FILE__);

$documentRoot = Application::getDocumentRoot();
$request = Application::getInstance()->getContext()->getRequest();
$curPage = $APPLICATION->GetCurPage(true);

if (file_exists($documentRoot.SITE_TEMPLATE_PATH.'/template_ext/config.php')) {
    include($documentRoot.SITE_TEMPLATE_PATH.'/template_ext/config.php');
}

// init devfunc
if (Loader::includeModule('redsign.devfunc')) {
    RSDevFunc::Init(array('jsfunc'));
} else {
    die('<span style="color:red;">'.Loc::getMessage('RS_SLINE.HEADER.ERROR_DEVFUNC').'</span>');
}

// is main page
$IS_MAIN = 'N';
if ($curPage == SITE_DIR.'index.php') {
    $IS_MAIN = 'Y';
}

// is catalog page
$IS_CATALOG = 'N';
if (
    strpos($curPage, SITE_DIR.'catalog/') !== false  ||
    strpos($curPage, SITE_DIR.'brands/') !== false
) {
    $IS_CATALOG = 'Y';
}

// is personal page
$IS_PERSONAL = 'Y';
if (strpos($curPage, SITE_DIR.'personal/') === false) {
    $IS_PERSONAL = 'N';
}

// get site data
$cacheTime = 86400;
$cacheId = 'CSiteGetByID'.SITE_ID;
$cacheDir = '/siteData/'.SITE_ID.'/';

$cache = Bitrix\Main\Data\Cache::createInstance();
if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
    $arSiteData = $cache->getVars();
} elseif ($cache->startDataCache()) {

    $arSiteData = array();

    $rsSites = CSite::GetByID(SITE_ID);
    if ($arSite = $rsSites->Fetch()) {
        $arSiteData['SITE_NAME'] = $arSite['SITE_NAME'];
    }

    if (Loader::includeModule('sale')) {
        $arLocationPath = array();

        $location_zip = COption::GetOptionString('sale', 'location_zip', '', SITE_ID);
        if ($location_zip != ''){
            $arLocationPath[] = $location_zip;
        }

       $location = COption::GetOptionString('sale', 'location', '', SITE_ID);

        if ($location != '') {
            $dbLocations = \Bitrix\Sale\Location\LocationTable::getPathToNodeByCode(
                $location,
                array(
                    'select' => array(
                        'LNAME' => 'NAME.NAME',
                        'SHORT_NAME' => 'NAME.SHORT_NAME',
                        'LEFT_MARGIN',
                        'RIGHT_MARGIN',
                    ),
                    'filter' => array(
                        'NAME.LANGUAGE_ID' => LANGUAGE_ID
                    )
                )
            );

            while ($arLocation = $dbLocations->Fetch()) {
                $arLocationPath[] = $arLocation['LNAME'];
            }
        }

        if (is_array($arLocationPath) && count($arLocationPath) > 0) {
            $arSiteData['SITE_ADDRESS_PATH'] = $arLocationPath;
        }
    }

    if (empty($arSiteData)) {
        $cache->abortDataCache();
    }

    $cache->endDataCache($arSiteData);
}

CJSCore::Init(array('ls'));

$asset = Asset::getInstance();
$asset->addString('<meta name="viewport" content="width=device-width, initial-scale=1">');
$asset->addString('<meta http-equiv="X-UA-Compatible" content="IE=edge">');
$asset->addString('<link href="'.CHTTP::URN2URI('/favicon.ico').'" rel="icon"  type="image/x-icon">');
$asset->addString('<link href="'.CHTTP::URN2URI('/favicon.ico').'" rel="shortcut icon"  type="image/x-icon">');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/jquery/jquery-3.3.1.js');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/bootstrap/transition.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/bootstrap/dropdown.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/bootstrap/collapse.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/bootstrap/tab.js');
//$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/bootstrap/bootstrap.js');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/mousewheel/jquery.mousewheel.js');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/jquery.scrollbar/jquery.scrollbar.js');
$asset->addCss(SITE_TEMPLATE_PATH.'/assets/lib/jquery.scrollbar/jquery.scrollbar.css');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/fancybox/jquery.fancybox.js');
$asset->addCss(SITE_TEMPLATE_PATH.'/assets/lib/fancybox/jquery.fancybox.css');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/lib/owlcarousel2/owl.carousel.js');
$asset->addCss(SITE_TEMPLATE_PATH.'/assets/lib/owlcarousel2/assets/owl.carousel.css');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/timer/timer.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/glass/glass.js');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/script.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/offers.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/iefix.js');


$asset->addCss(SITE_TEMPLATE_PATH.'/assets/css/template.css');

$asset->addCss(SITE_TEMPLATE_PATH.'/assets/css/custom.css', true);
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/custom.js', true);
$asset->addCss(SITE_DIR.'assets/css/custom.css', true);
$asset->addJs(SITE_DIR.'assets/js/custom.js', true);

$asset->addString('<script src="https://yastatic.net/share2/share.js" async="async" charset="utf-8"></script>');
$asset->addString('<!--[if lte IE 8]><script src="'.SITE_TEMPLATE_PATH.'/assets/lib/html5shiv.min.js" async="async" data-skip-moving="true"></script><![endif]-->');
$asset->addString('<!--[if lte IE 8]><script src="'.SITE_TEMPLATE_PATH.'/assets/lib/respond.min.js" async="async" data-skip-moving="true"></script><![endif]-->');

$protocol = \Bitrix\Main\Context::getCurrent()->getRequest()->isHttps() ? "https://" : "http://";
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/head_start.php",array(),array("MODE"=>"html"))?>
    <?$APPLICATION->ShowHead();?>
    <title><?php
    $APPLICATION->ShowTitle();
    if (
        $curPage != SITE_DIR.'index.php' &&
        $arSiteData['SITE_NAME'] != ''
    ) {
        echo ' | '. $arSiteData['SITE_NAME'];
    }
    ?></title>
    <?php
    CAjax::Init();
    ?>
    <script>appSLine.SITE_TEMPLATE_PATH = '<?=SITE_TEMPLATE_PATH?>';</script>
    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/head_end.php",array(),array("MODE"=>"html"))?>
</head>
<body <?=$APPLICATION->ShowProperty("backgroundImage")?>>
    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/body_start.php",array(),array("MODE"=>"html"))?>
    <?php
    $sHeaderFavoritePath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/favorite.php';
    if (file_exists($sHeaderFavoritePath)) {
        include($sHeaderFavoritePath);
    }
    ?>
    <div id="svg-icons"></div>
    <div id="panel"><?php $APPLICATION->ShowPanel(); ?></div>
    <div id="webpage" class="body <?=LANGUAGE_ID;?>">
        <header class="l-header header">
            <div class="l-header__top">
                <div class="container clearfix">
                    <div class="auth_top">
                        <?php
                        $sHeaderComparePath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/compare.php';
                        if (file_exists($sHeaderComparePath)) {
                            include($sHeaderComparePath);
                        }
                        ?>
                        <?php
/*$sHeaderLocationPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/location.php';
                        if (file_exists($sHeaderLocationPath)) {
                            include($sHeaderLocationPath);
}*/
                        ?>
                        <?php
                        $sHeaderMenuPersonalPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/menu_toppersonal.php';
                        if (file_exists($sHeaderMenuPersonalPath)) {
                            include($sHeaderMenuPersonalPath);
                        }
                        ?>
                        <div class="auth_top__item" id="auth_top__exit">
                        <?php
                        $frame = new \Bitrix\Main\Page\FrameBuffered('auth_top__exit', false);
                        $frame->setBrowserStorage(true);
                        $frame->begin();
                        //$frame->beginStub();
                        ?>
                            <?php if ($USER->IsAuthorized()): ?>
								<form action="<?=POST_FORM_ACTION_URI?>" method="POST">
									<input type="hidden" name="logout" value="yes" />
									<input class="auth_top__link" type="submit" name="logout_butt" value="<?=Loc::getMessage('RS_SLINE.HEADER.EXIT')?>">
								</form>
                            <?php else: ?>
                                <a class="auth_top__link js-ajax_link" data-fancybox="sign-in" data-type="ajax" href="<?=SITE_DIR?>personal/sing-in/" title="<?=Loc::getMessage('RS_SLINE.HEADER.AUTH_TITLE')?>" rel="nofollow">
                                    <svg class="icon icon-locked icon-svg"><use xlink:href="#svg-locked"></use></svg><span class="auth_top__text"><?=Loc::getMessage('RS_SLINE.HEADER.AUTH')?></span>
                                </a>
                            <?php endif; ?>
                        <?php $frame->beginStub(); ?>
                            <a class="auth_top__link js-ajax_link" data-fancybox="sign-in" data-type="ajax" href="<?=SITE_DIR?>personal/sing-in/" title="<?=Loc::getMessage('RS_SLINE.HEADER.AUTH_TITLE')?>" rel="nofollow">
                                <svg class="icon icon-locked icon-svg"><use xlink:href="#svg-locked"></use></svg><?=getMessage('RS_SLINE.HEADER.AUTH')?>
                            </a>
                        <?php $frame->end(); ?>
                        </div>
                    </div>
                    <?php
                    $sHeaderMenuTopPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/menu_top.php';
                    if (file_exists($sHeaderMenuTopPath)) {
                        include($sHeaderMenuTopPath);
                    }
                    ?>
                    <?php
                    /*
                    <div id="top_line" class="hidden-mobile">
                        <?$APPLICATION->IncludeFile(
                            $APPLICATION->GetTemplatePath(SITE_DIR.'include/template/top_line.php'),
                            Array(),
                            Array("MODE"=>"html")
                        );?>
                    </div>
                    */?>
                </div>
            </div>

            <div class="container l-header__infowrap clearfix">

                <div class="l-header__info clearfix">
                    <?php
                    $sHeaderCartPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/cart.php';
                    if (file_exists($sHeaderCartPath)) {
                        include($sHeaderCartPath);
                    }
                    ?>
                    <div class="l-header__logo logo" itemscope itemtype="http://schema.org/Organization">
                        <a href="<?=SITE_DIR?>" itemprop="url">
                            <?$APPLICATION->IncludeFile(
                                $APPLICATION->GetTemplatePath(SITE_DIR.'include/company_logo.php'),
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
                    <div class="l-header__adds">
                        <div class="l-header__phone adds recall">
                            <a class="js-ajax_link" data-fancybox="recall" data-type="ajax" href="<?=SITE_DIR?>recall/" title="<?=Loc::getMessage('RS_SLINE.HEADER.RECALL')?>" rel="nofollow">
                                <svg class="btn__icon icon icon-phone icon-svg"><use xlink:href="#svg-phone"></use></svg><?=Loc::getMessage('RS_SLINE.HEADER.RECALL')?>
                            </a>
                            <div class="adds__phone">
                            <?$APPLICATION->IncludeFile(
                                $APPLICATION->GetTemplatePath(SITE_DIR.'include/telephone1.php'),
                                Array(),
                                Array("MODE"=>"html")
                            );?>
                            </div>
                        </div>
						<!--<div class="l-header__phone adds feedback">
                            <a class="js-ajax_link" data-fancybox="feedback" data-type="ajax" href="<?=SITE_DIR?>feedback/" title="<?=Loc::getMessage('RS_SLINE.HEADER.FEEDBACK_TITLE')?>" rel="nofollow">
                                <svg class="btn__icon icon icon-dialog icon-svg"><use xlink:href="#svg-dialog"></use></svg><?=Loc::getMessage('RS_SLINE.HEADER.FEEDBACK')?>
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
                <div class="l-header__line clearfix">
                    <div class="l-header__search">
                        <?php
                        $sHeaderSearchPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/search.php';
                        if (file_exists($sHeaderSearchPath)) {
                            include($sHeaderSearchPath);
                        }
                        ?>
                    </div>
                    <?php
                    $sMenuCatalogPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/menu_catalog.php';
                    if (file_exists($sMenuCatalogPath)) {
                        include($sMenuCatalogPath);
                    }
                    ?>
                </div>
            </div>

            <?php
            if ($IS_MAIN == 'Y') {
                $APPLICATION->ShowViewContent('mainbanners');
            }
            ?>

        </header>
        <main class="l-main clearfix">
            <?php
			if (!function_exists('showTemplatePageStart'))
			{
				function showTemplatePageStart()
				{
					global $APPLICATION;

					$curPage = $APPLICATION->GetCurPage(true);
					$bAddContainer = true;
					$bShowTitle = true;
					$bShowNavChain = true;

					if ($APPLICATION->GetProperty('WIDE_PAGE') == 'Y') {
						$bAddContainer = false;
					}

					if ($APPLICATION->GetProperty('HIDE_PAGE_TITLE') == 'Y') {
						$bShowTitle = false;
					}

					// main page
					if ($curPage == SITE_DIR.'index.php') {
						$bShowTitle = false;
						$bAddContainer = false;
					}

					// catalog page
					if (strpos($curPage, SITE_DIR.'catalog/') !== false || strpos($curPage, SITE_DIR.'brands/') !== false) {
						$bShowTitle = false;
					}

					ob_start();

					$sPageTitle = $APPLICATION->GetTitle(false, false);

					if ($bAddContainer):
						?>
						<div class="container">
						<?php
					endif;

					if (strlen($sPageTitle) > 0 || $APPLICATION->GetProperty('NOT_SHOW_NAV_CHAIN') == 'Y')
					{
						?>
						<div class="webpage__head">
							<?php
							echo $APPLICATION->GetNavChain(
								$path = false,
								$iNumFrom = 0,
								$sNavChainPath = SITE_TEMPLATE_PATH.'/components/bitrix/breadcrumb/al/template.php',
								$bIncludeOnce = true,
								$bShowIcons = false
							);

							if ($bShowTitle)
							{
								?>
								<h1 class="webpage__title"><?=$sPageTitle?></h1>
								<?php
							}
							?>
						</div>
						<?php
					}

					$sHtmlContent = ob_get_clean();
					return $sHtmlContent;
				}
			}

            $APPLICATION->AddBufferContent('showTemplatePageStart');
            ?>

            <?php if ($request->isAjaxRequest() || $request->get('rs_ajax__page') == 'Y'): ?>
                <?php $APPLICATION->restartBuffer(); ?>
                <div>
            <?php endif; ?>

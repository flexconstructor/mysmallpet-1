<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)
	die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

Option::set('redsign.pethouse', 'wizard_installed', 'Y', WIZARD_SITE_ID);
// Option::set('redsign.pethouse', 'use_personal_license', 'Y', WIZARD_SITE_ID);
// Option::set('redsign.pethouse', 'personal_license_text', Loc::getMessage('RS.SLINE.OPTIONS.PERSONAL_LICENSE_TEXT_SAMPLE'), WIZARD_SITE_ID);


if (Loader::includeModule('redsign.devfunc')) {
    $arData = array(
        'mp_code' => array('redsign.pethouse'),
    );

    $ret = \Redsign\DevFunc\Module::registerInstallation($arData);
}
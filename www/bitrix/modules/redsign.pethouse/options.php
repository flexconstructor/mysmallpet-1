<?
$module_id = 'redsign.pethouse';
/** @global CMain $APPLICATION */
/** @global string $RestoreDefaults */
/** @global string $Update */

use Bitrix\Main\Loader;
use Bitrix\Main\SiteTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag\Debug;


function wrapOptionLHE($inputName, $content = '', $divId = false)
{
	ob_start();
	$ar = array(
		'inputName' => $inputName,
		'height' => '160',
		'width' => '320',
		'content' => $content,
		'bResizable' => true,
		'bManualResize' => true,
		'bUseFileDialogs' => false,
		'bFloatingToolbar' => false,
		'bArisingToolbar' => false,
		'bAutoResize' => true,
		'bSaveOnBlur' => true,
		'toolbarConfig' => array(
			'Bold', 'Italic', 'Underline', 'Strike',
			'CreateLink', 'DeleteLink',
			'Source', 'BackColor', 'ForeColor'
		)
	);

	if($divId)
		$ar['id'] = $divId;

	\Bitrix\Main\Loader::includeModule('fileman');

	$LHE = new CLightHTMLEditor;
	$LHE->Show($ar);
	$sVal = ob_get_contents();
	ob_end_clean();

	return $sVal;
}

$SALE_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($SALE_RIGHT>='R') :

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/options.php');
Loc::loadMessages(__FILE__);

Loader::includeModule($module_id);

$siteList = array();
$siteIterator = SiteTable::getList(array(
	'select' => array('LID', 'NAME'),
	'order' => array('SORT' => 'ASC')
));
while ($oneSite = $siteIterator->fetch())
{
	$siteList[] = array('ID' => $oneSite['LID'], 'NAME' => $oneSite['NAME']);
}
unset($oneSite, $siteIterator);

foreach ($siteList as $iSiteKey => $arSite) {
	if (!in_array(Option::get('main', 'wizard_template_id', '', $arSite['ID']), array('al'))) {
		unset($siteList[$iSiteKey]);
	}
}
unset($iSiteKey, $arSite);
$siteCount = count($siteList);

$bWasUpdated = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && strlen($RestoreDefaults)>0 && $SALE_RIGHT=='W' && check_bitrix_sessid())
{
	$bWasUpdated = true;

	COption::RemoveOption($module_id);
	$z = CGroup::GetList($v1='id',$v2='asc', array('ACTIVE' => 'Y', 'ADMIN' => 'N'));
	while($zr = $z->Fetch())
		$APPLICATION->DelGroupRight($module_id, array($zr['ID']));
}

$arAllOptions =
	array(
    );

$aTabs = array(
	array('DIV' => 'redsign_sline_settings', 'TAB' => GetMessage('RSAL_SETTINGS'), 'ICON' => 'settings', 'TITLE' => GetMessage('RSAL_SETTINGS')),
);

$tabControl = new CAdminTabControl('tabControl', $aTabs);

$strWarning = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (strlen($Update) > 0 || strlen($Apply) > 0) && $SALE_RIGHT == 'W' && check_bitrix_sessid())
{
    $bWasUpdated = true;
    Debug::writeToFile(get_defined_vars());

    for ($i = 0, $intCount = count($arAllOptions); $i < $intCount; $i++)
    {
        if(!empty($arAllOptions[$i]))
        {
            $name = $arAllOptions[$i][0];
            $val = ${$name};
            if ($arAllOptions[$i][3][0]=='checkbox' && $val!='Y')
                $val = 'N';

            COption::SetOptionString($module_id, $name, $val, $arAllOptions[$i][1]);
        }
    }

	if(is_array($_REQUEST['color_table_rgb']) && count($_REQUEST['color_table_rgb'])>0 && is_array($_REQUEST['color_table_name']) && count($_REQUEST['color_table_name'])>0)
	{
		$c = 0;
		foreach($_REQUEST['color_table_rgb'] as $key => $rgb)
		{
			if(trim($rgb)!='' && trim($_REQUEST['color_table_name'][$key])!='')
			{
				COption::SetOptionString($module_id, 'color_table_name_'.$c, trim($_REQUEST['color_table_name'][$key]) );
				COption::SetOptionString($module_id, 'color_table_rgb_'.$c, trim($rgb) );
				$c++;
			}
		}
		COption::SetOptionString($module_id, 'color_table_count', $c);
	}

	if (is_array($USE_PERSONAL_LICENSE) && count($USE_PERSONAL_LICENSE) > 0) {
		foreach ($USE_PERSONAL_LICENSE as $siteId => $val) {
			Option::set($module_id, 'use_personal_license', $val, $siteId);
		}
	}
	
	if (is_array($PERSONAL_LICENSE_TEXT) && count($PERSONAL_LICENSE_TEXT) > 0) {
		foreach ($PERSONAL_LICENSE_TEXT as $siteId => $val) {
			Option::set($module_id, 'personal_license_text', $val, $siteId);
		}
	}
}

if($strWarning != '')
	CAdminMessage::ShowMessage($strWarning);
elseif ($bWasUpdated)
{
	if(strlen($Update)>0 && strlen($_REQUEST['back_url_settings'])>0)
		LocalRedirect($_REQUEST['back_url_settings']);
	else
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANGUAGE_ID."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
}

$tabControl = new CAdminTabControl('tabControl', $aTabs);
$tabControl->Begin();
?><script type="text/javascript">
function OnColorPicker(val,obj){
	document.getElementById(obj.oPar.id).value = val;
}
</script><?
?><form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=$module_id?>&lang=<?=LANGUAGE_ID?>" name="opt_form">
<?$tabControl->BeginNextTab();?>
	<tr class="heading">
		<td colspan="3"><?=GetMessage('RSAL_COLOR_TABLE')?></td>
	</tr>
    <?
	$c = COption::GetOptionString($module_id, "color_table_count", 0);
	for ($i = 0; $i < $c; $i++):
	$c_name = COption::GetOptionString($module_id, "color_table_name_".$i, "");
	$c_rgb = COption::GetOptionString($module_id, "color_table_rgb_".$i, "");
	?>
    <tr>
		<td valign="top" width="47%"><input type="text" name="color_table_name[]" value="<?=$c_name?>" /></td>
		<td>&nbsp;</td>
		<td valign="top" width="47%">
            <input type="text" name="color_table_rgb[]" id="color_table_rgb_<?=$i?>" value="<?=$c_rgb?>" />
            <?
			$APPLICATION->IncludeComponent(
				"bitrix:main.colorpicker",
				"",
				array(
					"ID" => "color_table_rgb_".$i,
					"NAME" => "",
					"SHOW_BUTTON" => "Y",
					"ONSELECT" => "OnColorPicker",
				)
			);
		?>
        </td>
	</tr>
    <?
	endfor;

	for($t = ($i + 1); $t < ($i + 6); $t++):
	?>
    <tr>
		<td valign="top" width="47%" class="more"><input type="text" name="color_table_name[]" value="" /></td>
		<td>&nbsp;</td>
		<td valign="top" width="47%">
        <?
			$color_table_rgb = COption::GetOptionString($module_id, "color_table_rgb");
			?><input type="text" name="color_table_rgb[]" id="color_table_rgb_<?=$t?>" value="" /><?
			$APPLICATION->IncludeComponent(
				"bitrix:main.colorpicker",
				"",
				array(
					"ID" => "color_table_rgb_".$t,
					"NAME" => "",
					"SHOW_BUTTON" => "Y",
					"ONSELECT" => "OnColorPicker",
				)
			);
		?>
        </td>
	</tr>
    <?
	endfor;

?>
	<tr>
		<td colspan="3">
            <?
            $aTabs2 = Array();
			foreach($siteList as $val)
			{
				$aTabs2[] = Array('DIV'=>'personal'.$val['ID'], 'TAB' => '['.$val['ID'].'] '.htmlspecialcharsbx($val['NAME']), 'TITLE' => '['.htmlspecialcharsbx($val['ID']).'] '.htmlspecialcharsbx($val['NAME']));
			}
			$tabControl2 = new CAdminViewTabControl('tabControl2', $aTabs2);
			$tabControl2->Begin();
			foreach ($siteList as $val) {

				$tabControl2->BeginNextTab();

				$bUsePersonalLicense = Option::get($module_id, 'use_personal_license', 'Y', $val['ID']);
				$sPersonalLicenseText = Option::get($module_id, 'personal_license_text', '', $val['ID']);

				?>
				<table cellspacing="5" cellpadding="0" border="0" width="100%" align="center">
					<tr class="heading">
						<td colspan="2"><?=Loc::getMessage('RS_SLINE_OPTIONS_PERSONAL_LICENSE')?></td>
					</tr>
					<tr>
						<td align="right" width="40%"><label for="USE_PERSONAL_LICENSE-<?=$val['ID']?>"><?=Loc::getMessage('RS_SLINE_OPTIONS_USE_PERSONAL_LICENSE')?>:</label></td>
						<td width="60%"><input type="checkbox" name="USE_PERSONAL_LICENSE[<?=$val['ID']?>]" value="Y" id="USE_PERSONAL_LICENSE-<?=$val['ID']?>"<?if($bUsePersonalLicense == 'Y') echo ' checked';?>></td>
					</tr>
					
					<?php if ($bUsePersonalLicense == 'Y'): ?>
						<tr>
							<td align="right"><label for="PERSONAL_LICENSE_TEXT-<?=$val['ID']?>"><?=Loc::getMessage('RS_SLINE_OPTIONS_PERSONAL_LICENSE_TEXT')?>:</label></td>
							<td>
							<?php
							echo wrapOptionLHE(
								'PERSONAL_LICENSE_TEXT['.$val['ID'].']',
								strlen($sPersonalLicenseText) > 0 ? $sPersonalLicenseText : '',
								''
							);
							?>
							</td>
						</tr>
					<?php endif; ?>
				</table>
				<?
			}
			$tabControl2->End();
			?>
		</td>
	</tr>

<?$tabControl->Buttons();?>
<script type="text/javascript">
function RestoreDefaults()
{
	if (confirm('<?echo addslashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
		window.location = "<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?echo LANGUAGE_ID?>&mid=<?echo $module_id?>";
}
</script>
	<input type="hidden" name="siteTabControl_active_tab" value="<?=htmlspecialcharsbx($_REQUEST["siteTabControl_active_tab"])?>">
<?if($_REQUEST["back_url_settings"] <> ''):?>
	<input type="submit" name="Update" <?if ($SALE_RIGHT<"W") echo "disabled" ?> value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>">
<?endif?>
	<input type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
<?if($_REQUEST["back_url_settings"] <> ''):?>
	<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
	<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
<?endif?>
	<input type="submit" name="RestoreDefaults" <?if ($SALE_RIGHT<"W") echo "disabled" ?> title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" onclick="return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
<?$tabControl->End();?>
</form>
<?



endif;


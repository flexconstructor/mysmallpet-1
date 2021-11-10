<?

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arrColors = array(
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_00AEEF'),
		'RGB' => '#00AEEF',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_FFF100'),
		'RGB' => '#FFF100',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_56685C'),
		'RGB' => '#56685C',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_D91557'),
		'RGB' => '#D91557',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_EEEADE'),
		'RGB' => '#EEEADE',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_D3ACA5'),
		'RGB' => '#D3ACA5',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_EF6139'),
		'RGB' => '#EF6139',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_2A2F43'),
		'RGB' => '#2A2F43',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_4A4444'),
		'RGB' => '#4A4444',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_93BD59'),
		'RGB' => '#93BD59',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_1E4EA2'),
		'RGB' => '#1E4EA2',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_B9A216'),
		'RGB' => '#B9A216',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_00A650'),
		'RGB' => '#00A650',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_EDACBF'),
		'RGB' => '#EDACBF',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_3B251A'),
		'RGB' => '#3B251A',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_EE1D24'),
		'RGB' => '#EE1D24',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_000000'),
		'RGB' => '#000000',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_004A80'),
		'RGB' => '#004A80',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_ACACAC'),
		'RGB' => '#ACACAC',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_FFFFFF'),
		'RGB' => '#FFFFFF',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_CCB9AA'),
		'RGB' => '#CCB9AA',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_EBEBEB'),
		'RGB' => '#EBEBEB',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_1E1E20'),
		'RGB' => '#1E1E20',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_3C230D'),
		'RGB' => '#3C230D',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_91278F'),
		'RGB' => '#91278F',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_81CA9D'),
		'RGB' => '#81CA9D',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_7A0026'),
		'RGB' => '#7A0026',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_00A86B'),
		'RGB' => '#00A86B',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_A1410D'),
		'RGB' => '#A1410D',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_EBDAC6'),
		'RGB' => '#EBDAC6',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_E0B0FF'),
		'RGB' => '#E0B0FF',
	),
	array(
		'NAME' => Loc::getMessage('RS_SLINE_OPTIONS_COLOR_NAME_9B8793'),
		'RGB' => '#9B8793',
	),
);

$redsign_pethouse_default_option = array(
	'use_personal_license' => 'Y',
	'personal_license_text' => Loc::getMessage('RS_SLINE_OPTIONS_PERSONAL_LICENSE_TEXT_SAMPLE'),
);

foreach($arrColors as $iColorKey => $arColor)
{
	$redsign_pethouse_default_option['color_table_name_'.$iColorKey] = $arColor['NAME'];
	$redsign_pethouse_default_option['color_table_rgb_'.$iColorKey] = $arColor['RGB'];
}
$redsign_pethouse_default_option['color_table_count'] = count($arrColors);

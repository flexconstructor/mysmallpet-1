<?php
$arUrlRewrite=array (
  2 => 
  array (
    'CONDITION' => '#^/index/([0-9]+)/([0-9]+)/#',
    'RULE' => 'mode=read&CID=$1&GID=$2',
    'ID' => 'bitrix:catalog.section',
    'PATH' => '/newforum/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#(.+?)\\.html(.*)#',
    'RULE' => '$1.php$2',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photo',
    'PATH' => '/max/images/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/forum/#',
    'ID' => 'bitrix:forum',
    'PATH' => '/forum/index.php',
    'SORT' => 100,
  ),
);

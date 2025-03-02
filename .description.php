<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    "NAME"        => Loc::getMessage("ALFA_FAVORITES_NAME"),
    "DESCRIPTION" => Loc::getMessage("ALFA_FAVORITES_DESC"),
    "ICON"        => "/images/heart_icon.png",
    "PATH"        => [
        "ID"    => "alfa",
        "NAME"  => "ALFA Components",
    ],
];
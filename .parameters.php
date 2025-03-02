<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues */

use Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    "PARAMETERS" => [
        "ENTITY" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_ENTITY"),
            "TYPE" => "LIST",
            "VALUES" => [
                "ELEMENT" => Loc::getMessage("ALFA_FAVORITES_ENTITY_ELEMENT"),
                "SECTION" => Loc::getMessage("ALFA_FAVORITES_ENTITY_SECTION"),
                "AUDIO" => Loc::getMessage("ALFA_FAVORITES_ENTITY_AUDIO"),
                "VIDEO" => Loc::getMessage("ALFA_FAVORITES_ENTITY_VIDEO"),
                "OTHER" => Loc::getMessage("ALFA_FAVORITES_ENTITY_OTHER"),
            ],
            "DEFAULT" => "ELEMENT"
        ],
        "ENTITY_ID" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_ENTITY_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => "0"
        ],
        "ADD_TEXT" => [
            "PARENT" => "VISUAL",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_ADD_TEXT"),
            "TYPE" => "STRING",
            "DEFAULT" => Loc::getMessage("ALFA_FAVORITES_ADD_DEFAULT"),
        ],
        "REMOVE_TEXT" => [
            "PARENT" => "VISUAL",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_REMOVE_TEXT"),
            "TYPE" => "STRING",
            "DEFAULT" => Loc::getMessage("ALFA_FAVORITES_REMOVE_DEFAULT"),
        ],
        "USE_ICONS" => [
            "PARENT" => "VISUAL",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_USE_ICONS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ],
        "ICON_TYPE" => [
            "PARENT" => "VISUAL",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_ICON_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => [
                "HEART" => Loc::getMessage("ALFA_FAVORITES_ICON_HEART"),
                "STAR" => Loc::getMessage("ALFA_FAVORITES_ICON_STAR"),
                "CUSTOM" => Loc::getMessage("ALFA_FAVORITES_ICON_CUSTOM"),
            ],
            "DEFAULT" => "HEART",
            "REFRESH" => "Y"
        ],
        "ICON_COLOR" => [
            "PARENT" => "VISUAL",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_ICON_COLOR"),
            "TYPE" => "STRING",
            "DEFAULT" => "#cccccc",
        ],
        "ICON_ACTIVE_COLOR" => [
            "PARENT" => "VISUAL",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_ICON_ACTIVE_COLOR"),
            "TYPE" => "STRING",
            "DEFAULT" => "#ff0000",
        ],
        "CACHE_TIME" => [
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => Loc::getMessage("ALFA_FAVORITES_CACHE_TIME"),
            "TYPE" => "STRING",
            "DEFAULT" => "3600"
        ],
    ]
];

if ($arCurrentValues["ICON_TYPE"] === "CUSTOM") {
    $arComponentParameters["PARAMETERS"]["CUSTOM_SVG"] = [
        "PARENT" => "VISUAL",
        "NAME" => Loc::getMessage("ALFA_FAVORITES_CUSTOM_SVG"),
        "TYPE" => "STRING",
        "MULTILINE" => "Y",
        "DEFAULT" => ""
    ];
}


<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var string $templateFolder */
use Bitrix\Main\Page\Asset;
?>

    <a href="javascript:void(0);"
       class="favorite-toggle"
       data-entity="<?= htmlspecialcharsbx($arResult['ENTITY'])?>"
       data-id="<?=intval($arResult['ENTITY_ID'])?>"
       data-favorite="<?=($arResult['IS_FAVORITE'] ? 'true' : 'false')?>"
       data-url="<?=$arResult['AJAX_URL']?>"
       data-add-text="<?=htmlspecialcharsbx($arResult['ADD_TEXT'])?>"
       data-remove-text="<?=htmlspecialcharsbx($arResult['REMOVE_TEXT'])?>"
       data-icon-color="<?=htmlspecialcharsbx($arResult['ICON_COLOR'])?>"
       data-icon-active-color="<?=htmlspecialcharsbx($arResult['ICON_ACTIVE_COLOR'])?>"
       title="<?=($arResult['IS_FAVORITE'] ? $arResult['REMOVE_TEXT'] : $arResult['ADD_TEXT'])?>">

        <?php if ($arResult['USE_ICONS']): ?>
            <?php if ($arResult['ICON_TYPE'] === "HEART"): ?>
                <svg width="16" height="16" viewBox="0 0 24 24">
                    <path fill="<?=($arResult['IS_FAVORITE'] ? $arResult['ICON_ACTIVE_COLOR'] : $arResult['ICON_COLOR'])?>"
                          d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            <?php elseif ($arResult['ICON_TYPE'] === "STAR"): ?>
                <svg width="16" height="16" viewBox="0 0 24 24">
                    <path fill="<?=($arResult['IS_FAVORITE'] ? $arResult['ICON_ACTIVE_COLOR'] : $arResult['ICON_COLOR'])?>"
                          d="M12 .587l3.668 7.431L24 9.254l-6 5.847 1.416 8.26L12 18.897l-7.416 4.464L6 15.101 0 9.254l8.332-1.236z"/>
                </svg>
            <?php elseif ($arResult['ICON_TYPE'] === "CUSTOM" && !empty($arResult['CUSTOM_SVG'])): ?>
                <?= str_replace(["fill=\"#", "fill='#"], ["fill=\"".($arResult['IS_FAVORITE'] ? $arResult['ICON_ACTIVE_COLOR'] : $arResult['ICON_COLOR'])."\"", "fill='".($arResult['IS_FAVORITE'] ? $arResult['ICON_ACTIVE_COLOR'] : $arResult['ICON_COLOR'])."'"], $arResult['CUSTOM_SVG']); ?>
            <?php endif; ?>
        <?php else: ?>
            <?=($arResult['IS_FAVORITE'] ? $arResult['REMOVE_TEXT'] : $arResult['ADD_TEXT'])?>
        <?php endif; ?>
    </a>
<?php
$asset = Asset::getInstance();
$asset->addJs($templateFolder . "/../../js/script.js");
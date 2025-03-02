<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\CurrentUser;

class CustomFavoritesComponent extends CBitrixComponent implements Controllerable
{
    public function configureActions()
    {
        return [
            'toggleFavorite' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                ],
            ],
        ];
    }

    public function toggleFavoriteAction($entity, $entityId, $action)
    {
        if (!Loader::includeModule('highloadblock')) {
            return ['success' => false, 'error' => Loc::getMessage('ALFA_FAVORITES_MODULE_NOT_LOADED')];
        }

        require_once __DIR__ . "/class/FavoriteManager.php";

        $userId = CurrentUser::get()->getId();
        if (!$userId) {
            return ['success' => false, 'error' => Loc::getMessage('ALFA_FAVORITES_USER_IS_NOT_AUTHORIZED')];
        }

        FavoritesManager::init($this->arParams["CACHE_TIME"] ?? 3600);

        $entityId = (int)$entityId;

        if ($action === "add") {
            $success = FavoritesManager::add($userId, $entity, $entityId);
        } elseif ($action === "remove") {
            $success = FavoritesManager::remove($userId, $entity, $entityId);
        } else {
            return ['success' => false, 'error' => Loc::getMessage('ALFA_FAVORITES_INVALID_ACTION')];
        }

        return ['success' => $success];
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('highloadblock')) {
            return;
        }

        require_once __DIR__ . "/class/FavoriteManager.php";

        $userId = CurrentUser::get()->getId();
        if (!$userId) {
            return;
        }

        // Передаём CACHE_TIME в класс
        FavoritesManager::init($this->arParams["CACHE_TIME"] ?? 3600);

        $entity = $this->arParams["ENTITY"];
        $entityId = (int)$this->arParams["ENTITY_ID"];

        $this->arResult = [
            "IS_FAVORITE" => FavoritesManager::isFavorite($userId, $entity, $entityId),
            "ENTITY" => $entity,
            "ENTITY_ID" => $entityId,
            "ADD_TEXT" => $this->arParams["ADD_TEXT"] ?: Loc::getMessage("ALFA_FAVORITES_ADD_DEFAULT"),
            "REMOVE_TEXT" => $this->arParams["REMOVE_TEXT"] ?: Loc::getMessage("ALFA_FAVORITES_REMOVE_DEFAULT"),
            "USE_ICONS" => $this->arParams["USE_ICONS"] === "Y",
            "ICON_TYPE" => $this->arParams["ICON_TYPE"] ?: "HEART",
            "CUSTOM_SVG" => $this->arParams["CUSTOM_SVG"] ?? "",
            "ICON_COLOR" => $this->arParams["ICON_COLOR"] ?: "#cccccc",
            "ICON_ACTIVE_COLOR" => $this->arParams["ICON_ACTIVE_COLOR"] ?: "#ff0000",
        ];

        $this->includeComponentTemplate();
    }
}

<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\SystemException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Data\Cache;

Loc::loadMessages(__FILE__);

class FavoritesManager
{
    private static int $hlBlockId = 0;
    private static $entityDataClass = null;
    private static int $cacheTime = 3600; // Значение по умолчанию

    public static function init($cacheTime = 3600)
    {
        self::$cacheTime = (int)$cacheTime;

        if (!Loader::includeModule('highloadblock')) {
            throw new SystemException(Loc::getMessage("ALFA_FAVORITES_MODULE_NOT_INSTALLED"));
        }

        if (!self::$hlBlockId) {
            $hlblock = HighloadBlockTable::getList([
                'filter' => ['=NAME' => 'Favorites']
            ])->fetch();

            if (!$hlblock) {
                throw new SystemException(Loc::getMessage("ALFA_FAVORITES_HLBLOCK_NOT_FOUND"));
            }

            self::$hlBlockId = (int)$hlblock['ID'];
        }

        if (!self::$entityDataClass) {
            $hlblock = HighloadBlockTable::getById(self::$hlBlockId)->fetch();
            $entity = HighloadBlockTable::compileEntity($hlblock);
            self::$entityDataClass = $entity->getDataClass();
        }
    }

    public static function add($userId, $entity, $entityId)
    {
        self::init();

        $existing = self::$entityDataClass::getList([
            'filter' => [
                '=UF_USER_ID' => $userId,
                '=UF_ENTITY' => $entity,
                '=UF_ENTITY_ID' => $entityId,
            ]
        ])->fetch();

        if ($existing) {
            return false;
        }

        $result = self::$entityDataClass::add([
            'UF_USER_ID' => $userId,
            'UF_ENTITY' => $entity,
            'UF_ENTITY_ID' => $entityId,
        ]);

        if ($result->isSuccess()) {
            self::clearCache($userId);
        }

        return $result->isSuccess();
    }

    public static function remove($userId, $entity, $entityId)
    {
        self::init();

        $item = self::$entityDataClass::getList([
            'filter' => [
                '=UF_USER_ID' => $userId,
                '=UF_ENTITY' => $entity,
                '=UF_ENTITY_ID' => $entityId,
            ]
        ])->fetch();

        if (!$item) {
            return false;
        }

        $result = self::$entityDataClass::delete($item['ID']);

        if ($result->isSuccess()) {
            self::clearCache($userId);
        }

        return $result->isSuccess();
    }

    public static function isFavorite($userId, $entity, $entityId)
    {
        self::init();

        $cache = Cache::createInstance();
        $cacheId = "favorite_{$userId}_{$entity}_{$entityId}";
        $cacheDir = "/favorites/{$userId}";

        if ($cache->initCache(self::$cacheTime, $cacheId, $cacheDir)) {
            return $cache->getVars();
        }

        if ($cache->startDataCache()) {
            $isFavorite = (bool)self::$entityDataClass::getList([
                'filter' => [
                    '=UF_USER_ID' => $userId,
                    '=UF_ENTITY' => $entity,
                    '=UF_ENTITY_ID' => $entityId,
                ],
                'limit' => 1,
            ])->fetch();

            $cache->endDataCache($isFavorite);
            return $isFavorite;
        }

        return false;
    }

    public static function getUserFavorites($userId)
    {
        self::init();

        $cache = Cache::createInstance();
        $cacheId = "favorites_list_{$userId}";
        $cacheDir = "/favorites/{$userId}";

        if ($cache->initCache(self::$cacheTime, $cacheId, $cacheDir)) {
            return $cache->getVars();
        }

        if ($cache->startDataCache()) {
            $favorites = [];
            $result = self::$entityDataClass::getList([
                'filter' => ['=UF_USER_ID' => $userId],
                'select' => ['UF_ENTITY', 'UF_ENTITY_ID']
            ]);

            while ($item = $result->fetch()) {
                $favorites[$item['UF_ENTITY']][$item['UF_ENTITY_ID']] = true;
            }

            $cache->endDataCache($favorites);
            return $favorites;
        }

        return [];
    }

    private static function clearCache($userId)
    {
        $cacheDir = "/favorites/{$userId}";
        Cache::createInstance()->cleanDir($cacheDir);
    }
}

<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\SystemException;

class FavoritesManager
{
    private static int $hlBlockId = 0;
    private static $entityDataClass = null;

    public static function init()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new SystemException("Модуль highloadblock не установлен.");
        }

        if (!self::$hlBlockId) {
            $hlblock = HighloadBlockTable::getList([
                'filter' => ['=NAME' => 'Favorites']
            ])->fetch();

            if (!$hlblock) {
                throw new SystemException("HL-блок 'Favorites' не найден.");
            }

            self::$hlBlockId = $hlblock['ID'];
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
        return $result->isSuccess();
    }

    public static function isFavorite($userId, $entity, $entityId)
    {
        self::init();

        return (bool)self::$entityDataClass::getList([
            'filter' => [
                '=UF_USER_ID' => $userId,
                '=UF_ENTITY' => $entity,
                '=UF_ENTITY_ID' => $entityId,
            ],
            'limit' => 1,
        ])->fetch();
    }

    public static function getUserFavorites($userId)
    {
        self::init();

        $favorites = [];
        $result = self::$entityDataClass::getList([
            'filter' => ['=UF_USER_ID' => $userId],
            'select' => ['UF_ENTITY', 'UF_ENTITY_ID']
        ]);

        while ($item = $result->fetch()) {
            $favorites[$item['UF_ENTITY']][$item['UF_ENTITY_ID']] = true;
        }

        return $favorites;
    }
}

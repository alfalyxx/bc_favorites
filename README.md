# Компонент "Избранное" для Битрикс

Этот компонент позволяет пользователям добавлять различные сущности (элементы инфоблоков, разделы, аудио/видео файлы) в избранное.

## 📌 Возможности
- Добавление и удаление из избранного с помощью контроллера.
- Поддержка различных типов сущностей (элементы ИБ, разделы ИБ, файлы).
- Вариативность отображения (текст, иконки, кастомный SVG).
- Гибкие настройки цвета заливки иконок.
- Удобный механизм хранения в Highload-блоке.

## 🔧 Установка

### 1️⃣ Создание Highload-блока для хранения избранного
Создайте Highload-блок в Битриксе вручную или выполните SQL-запрос:

#### SQL-файл для создания таблицы (если вручную)
```mysql
CREATE TABLE `b_hlbd_favorites` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT,
    `UF_USER_ID` INT(11) NOT NULL,
    `UF_ENTITY` VARCHAR(50) NOT NULL,
    `UF_ENTITY_ID` INT(11) NOT NULL,
    `UF_DATE_ADD` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`ID`),
    UNIQUE KEY `user_entity_unique` (`UF_USER_ID`, `UF_ENTITY`, `UF_ENTITY_ID`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### Структура Highload-блока
| Поле          | Тип данных | Описание                         |
|--------------|-----------|--------------------------------|
| `UF_USER_ID`   | `int`       | ID пользователя               |
| `UF_ENTITY`    | `string`    | Тип сущности (`IBLOCK`, `FILE`, `SECTION`) |
| `UF_ENTITY_ID` | `int`       | ID сущности                   |
| `UF_DATE_ADD`  | `datetime`  | Дата добавления в избранное   |

## 2️⃣ Копирование файлов компонента

Скопируйте компонент в директорию:

/local/components/custom/favorites/

## 3️⃣ Использование компонента

Добавьте компонент в нужное место, например, в template.php:
```php
<?$APPLICATION->IncludeComponent("custom:favorites", "", [
    "ENTITY" => "IBLOCK",
    "ENTITY_ID" => $arResult["ID"],
    "ADD_TEXT" => "Добавить в избранное",
    "REMOVE_TEXT" => "Удалить из избранного",
    "USE_ICONS" => "Y",
    "ICON_TYPE" => "HEART",
    "ICON_COLOR" => "#cccccc",
    "ICON_ACTIVE_COLOR" => "#ff0000",
]);?>
```

## ⚙️ Настройки параметров
| Параметр            | Тип     | Описание                                    | Значение по умолчанию |
|---------------------|---------|---------------------------------------------|------------------------|
| `ENTITY`            | `string`  | Тип сущности (`IBLOCK`, `SECTION`, `FILE`) | `"IBLOCK"`            |
| `ENTITY_ID`         | `int`     | ID сущности                                | `0`                    |
| `ADD_TEXT`         | `string`  | Текст "Добавить в избранное"               | `"Добавить"`           |
| `REMOVE_TEXT`      | `string`  | Текст "Удалить из избранного"              | `"Удалить"`            |
| `USE_ICONS`        | `string`  | Использовать иконки (`Y`/`N`)               | `"Y"`                  |
| `ICON_TYPE`        | `string`  | Тип иконки (`HEART`, `STAR`, `CUSTOM`)      | `"HEART"`              |
| `CUSTOM_SVG`       | `string`  | Собственный SVG-код                        | `""`                   |
| `ICON_COLOR`       | `string`  | Цвет SVG-иконки (обычный)                   | `"#cccccc"`            |
| `ICON_ACTIVE_COLOR` | `string`  | Цвет SVG-иконки (активный)                  | `"#ff0000"`            |


## 📌 Дополнение: Работа с Контроллером в Компоненте

Компонент использует стандартный механизм Bitrix\Controllerable для работы с избранным через AJAX-запросы.
⚙️ Как работает контроллер?

Контроллер реализуется внутри компонента, что исключает необходимость в отдельных AJAX-обработчиках.

### 1️⃣ Метод toggleFavoriteAction()

Этот метод добавляет или удаляет элемент из избранного.

```js
BX.ajax.runComponentAction('custom:favorites', 'toggleFavorite', {
    mode: 'class',
    data: { 
        entity: this.dataset.entity, 
        entityId: this.dataset.id, 
        action: this.dataset.favorite === "true" ? "remove" : "add" 
    }
})
```

## 📜 Лицензия

MIT

## 📜 Разработчик

    Название компании / Разработчик: Alfa (Alexander Fast)
    Контакт: [lyxx@mail.ru / alfalyxx.pro]
    Версия модуля: 1.0.0
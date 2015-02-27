
# <a name="about"></a>Bitrix Highloadblock Wrapper class

Composer:

```
require: "cjp2600/hiwrapper": ">=1.0.0"
```

Example:

**Получение сущности Hlblock по названию таблицы**
```php
$ob = HiWrapper::table("table_name");
```
**или по названию**
```php
$ob = HiWrapper::code("EntityName");
```
**или по id**
```php
$ob = HiWrapper::id(5);
```

**далее работаем с обычным orm bitrix**
```php
$ob = HiWrapper::table("table_name")->getList()
```
```php
$ob = HiWrapper::table("table_name")->add()
```

**и.т.д**

Так-же можно использовать Query (\Bitrix\Main\Entity\Query)

Example:
```php

$query = HiWrapper::code("EntityName")->query();
$query->registerRuntimeField("other", array(
            "data_type" => HiWrapper::code("OtherEntityName")->getDataType(),
            'reference' => array('=this.UF_OTHER_ID' => 'ref.ID'),
            'join_type' => "LEFT"
        )
    )
    ->setSelect(array("other_name" => "other.UF_NAME", "UF_NAME"))
    ->setFilter(array(
            "LOGIC" => "OR",
            array("other.UF_TYPE" => "old"),
            array("ID" => 3)
        )
    )
    ->setLimit(2);
$object = $query->exec();

 while ( $item = $object->fetch( new Local\Converters\CategoryImportConverter() ) )
 {
     echo "<pre>";
     print_r($item );
     echo "</pre>";
 }

```
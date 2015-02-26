
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
**или**
```php
$ob = HiWrapper::code("EntityName");
```
**или**
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
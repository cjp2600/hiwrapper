
# <a name="about"></a>Bitrix Highloadblock Wrapper class

Example:

**Получение сущности Hlblock по названию таблицы**
```php
$ob = HiWrapper::table("table_name");
```

**далее работаем с обычным orm bitrix**
```php
$ob = HiWrapper::table("table_name")->getList()
```
```php
$ob = HiWrapper::table("table_name")->add()
```

**и.т.д**
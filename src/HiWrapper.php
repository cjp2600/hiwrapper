<?php
/**
 * @author: Stanislav Semenov (CJP2600)
 * @email: cjp2600@ya.ru
 *
 * @date: 26.02.2014
 * @time: 11:36
 *
 */

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;

class HiWrapper {

    # singleton object
    private static $_instance = null;
    # highloadblock code
    private $_table_name = null;
    # default cache time
    private $_default_cache = 86400;
    # hlblock data
    private $_hldata = null;


    function __construct($_table_name)
    {
        # validate table name
        if (!isset($_table_name) || empty($_table_name)){
            throw new \Exception("table name is required param");
        }
        # load highloadblock module
        if (!Loader::includeModule('highloadblock')) {
            throw new \Exception("highloadblock module not exists");
        }
        # set highloadblock code
        $this->_table_name = $_table_name;
    }

    /**
     * limit cloning object
     * __clone
     */
    protected function __clone(){}

    /**
     * table
     * @param $_table_name
     * @return Hiblock|null
     */
    public static function table($_table_name)
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self($_table_name);
        }
        return self::$_instance;
    }

    /**
     * getList
     * @param $param
     * @return \Bitrix\Main\DB\Result
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public function getList($param)
    {
        $obEntity = $this->getEntityDataClass();
        return $obEntity::getList($param);
    }

    /**
     * add
     * @param $param
     * @return \Bitrix\Main\Entity\AddResult
     * @throws \Exception
     */
    public function add($param)
    {
        $obEntity = $this->getEntityDataClass();
        return $obEntity::add($param);
    }

    /**
     * update
     * @param $primary
     * @param array $data
     * @return \Bitrix\Main\Entity\UpdateResult
     * @throws \Exception
     */
    public function update($primary, array $data)
    {
        $obEntity = $this->getEntityDataClass();
        return $obEntity::update($primary, $data);
    }

    /**
     * delete
     * @param $primary
     * @return \Bitrix\Main\Entity\DeleteResult
     * @throws \Exception
     */
    public function delete($primary)
    {
        $obEntity = $this->getEntityDataClass();
        return $obEntity::delete($primary);
    }

    /**
     * getEntityDataClass
     * @return \Bitrix\Main\Entity\DataManager
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    private function getEntityDataClass()
    {
        if (is_null($this->getHldata())) {
            if (false === ($hlblock = $this->getHlBlockByTable())) {
                throw new \Exception('Not found HighloadBlock for table = "' . $this->getTableCode() . '"');
            }
            $this->setHldata($hlblock);
        }
        $entity = HighloadBlockTable::compileEntity($this->getHldata());
        $entityDataClass = $entity->getDataClass();
        return $entityDataClass;
    }

    /**
     * getHlBlockByTable
     * @return array|bool|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    private function getHlBlockByTable($refresh_cache = false)
    {
        $tableName = $this->getTableName();
        if (!$tableName) {
            throw new \Exception('table name is empty.');
        }
        $hlblock = false;
        $cache = new \CPHPCache();
        $cache_time = $this->getDefaultCacheTime();
        $cache_id = $tableName;
        $cache_path = '/'.__CLASS__.'/'.__METHOD__.'/';
        if ((!$refresh_cache) && $cache->InitCache($cache_time, $cache_id, $cache_path))
        {
            $hlblock = $cache->GetVars();
        } else {
            $cache->StartDataCache($cache_time, $cache_id, $cache_path);

            $hlBlockDbRes = HighloadBlockTable::getList(array('filter' => array('TABLE_NAME' => $tableName)));
            if ($hlBlockDbRes !== false && $hlBlockDbRes->getSelectedRowsCount()) {
                $hlblock = $hlBlockDbRes->fetch();
            }
            if (!$hlblock) {
                $cache->AbortDataCache();
            }
            $cache->EndDataCache($hlblock);
        }
        return $hlblock;
    }

    /**
     * @return null
     */
    public function getTableName()
    {
        return $this->_table_name;
    }

    /**
     * @return int
     */
    public function getDefaultCacheTime()
    {
        return $this->_default_cache;
    }

    /**
     * @return null
     */
    public function getHldata()
    {
        return $this->_hldata;
    }

    /**
     * @param null $hldata
     */
    public function setHldata($hldata)
    {
        $this->_hldata = $hldata;
    }

}
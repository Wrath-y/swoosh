<?php
/**
* 模型基类
*/
class Model extends Sql
{
	protected $_model;
	protected $_table;

	function __construct()
	{
		//连接数据库
		$this->connetc(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		//获取模型类名
		$this->_model = get_class($this);
		//表名与类名一致
		$this->_table = strtolower($this->_model);
	}
}
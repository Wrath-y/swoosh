<?php
/**
* 数据库基类
*/
class Sql
{
	protected $_dbHandle;
	protected $_result;
	protected $filter = '';
	
	//连接数据库
	protected function connetc($host,$user,$passwd,$dbname)
	{
		try {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
            $option = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
            $this->_dbHandle = new PDO($dsn, $user, $pass, $option);//初始化一个PDO对象
        } catch (PDOException $e) {
            exit('错误: ' . $e->getMessage());
        }
	}

	//查询条件
	protected function where($where = array())
	{
		if (isset($where)) {
			$this->filter .=' WHERE ';
			$this->filter .=implode(' ', $where);
		}
		return $this;
	}

	//排序条件
	protected function order($order = array())
	{
		$this->filter .=' ORDER BY ';
		$this->filter .=implode(',', $order);
	}
	return $this;

	//查询所有
	public function selectAll()
	{
		$sql = "select * from `$this->_table` $this->filter";
		$sth = $this->_dbHandle->prepare($sql);
		$sth = execute();
		return $sth->fetchAll();
	}
}
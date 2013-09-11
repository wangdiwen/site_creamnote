<?php
/*****************************************************************************/
/**
 * 数据库接口类，适合mysql
 */
/*****************************************************************************/
/**
 * 数据库异常类，继承自基类
 */
class DB_Exception extends Exception {}

class WX_DB
{
    var $db_service;           // oss server obj
    var $db_database = 'CREAMNOTE';
    var $db_server = 'localhost'; // 暂时为云服务器本地数据库：121.199.4.71
    var $db_user_name = 'root';
    var $db_passwd = 'wx@creamnote';

/*****************************************************************************/
    public function __construct()
    {
        // 加载必要的库接口
        // require_once WX_BASE_PATH.WX_SEPARATOR.lib.WX_SEPARATOR."wx_alioss_public.php";

        // 初始化DB服务连接
        $this->db_service = mysql_connect($this->db_server, $this->db_user_name, $this->db_passwd);
        if (! $this->db_service)
        {
            echo 'Could not connect: '.mysql_error()."\n\n";
            throw new DB_Exception("create db service failed");
        }
    }
/*****************************************************************************/
    public function __destruct()
    {
        // echo '__destruct'."\n";
        // 断开与数据库的连接
        mysql_close($this->db_service);
    }
/*****************************************************************************/
    /**
     * 以下为数据库的使用接口，包含：
     * 1. 选择数据: select
     * 2. 插入数据：insert
     * 3. 更新数据：update
     * 4. 删除数据：delete
     */
/*****************************************************************************/
    public function select($table = '', $select = array(), $where = array(), $limit = 0, $order_by = '', $join = array())
    {
        if (! $this->db_service)
        {
            echo 'database connection error'."\n\n";
            throw new DB_Exception('lost database connection');
        }
        else
        {
            if ($table)
            {
                // create sql query string
                $sql = 'SELECT';
                $sql_select = '';
                $sql_from = '';
                $sql_where = '';

                if ($select)
                {
                    $sql_select = implode(',', $select);
                    $sql = $sql.' '.$sql_select;
                }
                else
                {
                    $sql = $sql.' *';
                }
                $sql = $sql.' FROM '.$table;

                if ($join) {
                    if (isset($join['table']) && isset($join['item']) && isset($join['type'])) {
                        $sql_join = strtoupper($join['type']).' JOIN '.$join['table'].' ON '.$table.'.'.$join['item'].'='.$join['table'].'.'.$join['item'];
                        // echo $sql_join."\n";
                        $sql = $sql.' '.$sql_join;
                    }
                }

                if ($where)
                {
                    $str_list = array();
                    foreach ($where as $key => $value)
                    {
                        if (strrpos($key, '=') || strrpos($key, '>') || strrpos($key, '<')) {
                            array_push($str_list, $key." '".$value."'");
                        }
                        else {
                            array_push($str_list, $key."='".$value."'");
                        }
                    }
                    $sql_where = join(' AND ', $str_list);
                    $sql = $sql.' WHERE '.$sql_where;
                }

                if ($limit > 0)
                {
                    $sql = $sql.' LIMIT '.$limit;
                }

                if ($order_by) {
                    $sql = $sql.' ORDER BY '.$order_by;
                }

                // echo $sql."\n";

                // exec the sql syntax
                $result = array();
                mysql_select_db($this->db_database, $this->db_service);
                $query = mysql_query($sql);
                if ($query)
                {
                    while($row = mysql_fetch_assoc($query))
                    {
                        array_push($result, $row);
                    }
                }
                return $result;
            }
        }
    }
/*****************************************************************************/
    public function insert($table = '', $data = array())
    {
        if (! $this->db_service) {
            echo 'database connection error'."\n\n";
            throw new DB_Exception('lost database connection');
        }
        else {
            if ($table && $data) {
                $sql = 'INSERT INTO '.$table.' ';

                $key_str = '';
                $key_list = array();
                $value_str = '';
                $value_list = array();

                foreach ($data as $key => $value) {
                    array_push($key_list, $key);
                    array_push($value_list, "'".$value."'");
                }

                $key_str = '('.implode(',', $key_list).')';
                $value_str = '('.implode(',', $value_list).')';

                $sql = $sql.$key_str.' VALUES '.$value_str;
                // echo $sql."\n";

                // exec the sql syntax
                mysql_select_db($this->db_database, $this->db_service);
                $query = mysql_query($sql);
                if (! $query) {
                    echo 'insert error: '.mysql_error()."\n";
                    return false;
                }
                return true;
            }
            else {
                echo 'argument error: insert(table, data)'."\n";
                return false;
            }
        }
    }
/*****************************************************************************/
    public function update($table = '', $data = array(), $where = array())
    {
        if (! $this->db_service) {
            echo 'database connection error'."\n\n";
            throw new DB_Exception('lost database connection');
        }
        else {
            if ($table && $data && $where) {
                $sql = 'UPDATE '.$table.' ';

                $data_list = array();
                foreach ($data as $key => $value) {
                    array_push($data_list, $key."='".$value."'");
                }
                $sql = $sql.'SET '.implode(',', $data_list).' ';

                $where_list = array();
                foreach ($where as $key => $value) {
                    array_push($where_list, $key."='".$value."'");
                }
                $sql = $sql.'WHERE '.implode(' AND ', $where_list);

                // echo $sql."\n";

                // exec the sql syntax
                mysql_select_db($this->db_database, $this->db_service);
                $query = mysql_query($sql);
                if (! $query) {
                    echo 'update error: '.mysql_error()."\n";
                    return false;
                }
                return true;
            }
            else {
                echo 'argument error: update(table, data, where)'."\n";
                return false;
            }
        }
    }
/*****************************************************************************/
    public function delete($table = '', $where = array())
    {
        if (! $this->db_service) {
            echo 'database connection error'."\n\n";
            throw new DB_Exception('lost database connection');
        }
        else {
            if ($table && $where) {
                $sql = 'DELETE FROM '.$table.' ';

                $where_list = array();
                foreach ($where as $key => $value) {
                    array_push($where_list, $key."='".$value."'");
                }
                $sql = $sql.'WHERE '.implode(' AND ', $where_list);

                // echo $sql."\n";

                // exec the sql syntax
                mysql_select_db($this->db_database, $this->db_service);
                $query = mysql_query($sql);
                if (! $query) {
                    echo 'delete error: '.mysql_error()."\n";
                    return false;
                }
                return true;
            }
            else {
                echo 'argument error: delete(table, where)'."\n";
                return false;
            }
        }
    }
/*****************************************************************************/
}

?>

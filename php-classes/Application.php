<?php

class Application 
{
    const OPERATION_DELETE = 0;
    const OPERATION_CREATE = 1;
    const OPERATION_UPDATE = 2;
    
    private $_connection;
    private $_values;
    private $_sizePage = 10;

    private $_host = 'localhost';
    private $_dbname = 'task1';
    private $_user = 'root';
    private $_pass = 'annet2711';
    protected static $_instance;

    private function __construct() 
    {
        try 
        {
            $this->_connection = new PDO("mysql:host=$this->_host;dbname=$this->_dbname", $this->_user, $this->_pass);
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            echo "Ошибка в скрипте.";
            file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
        }
    }

    private function __clone() 
    {
        //Закрытие доступа к функции вне класса.
    }

    public static function getInstance() 
    {
        if (self::$_instance !== null) {
            return self::$_instance;
        } else {
            self::$_instance = new self();
            return self::$_instance;
        }
    }
    
    /**
     * Вспомогательный метод для построения запросов INSERT/UPDATE
     * Соглашение: имена полей в форме должны соответствовать именам полей в таблице
     * @param type $allowed имена полей
     * @param type $values значения полей
     * @param type $source 
     * @return type string
     */
    private function pdoSet($allowed, $source = array()) 
    {
        $set = '';
        $this->_values = array();
        if (!$source)
            $source = &$_POST;
        foreach ($allowed as $field) {
            if (isset($source[$field])) {
                $set.="`" . str_replace("`", "``", $field) . "`" . "=:$field, ";
                $this->_values[$field] = $source[$field];
            }
        }
        return substr($set, 0, -2);
    }

    public function pdoQueryPage($tableName) 
    {
        if(isset($_GET['page'])) 
            $page = $_GET['page'];
        else 
            $page = 0;
        $sql = "SELECT * FROM $tableName LIMIT $page, $this->_sizePage";
        return $this->_connection->query($sql);
    }
    
    public function pdoQueryAllRows($tableName) 
    {
        $sql = "SELECT * FROM $tableName";
        return $this->_connection->query($sql);
    }
    
    /**
     * Получить количество строк в таблице по ее имени
     * @param string $tableName
     * @return integer возвращает количество строк в таблице
     */
    public function getCountRows($tableName)
    {
        $sql = "SELECT count(*) FROM $tableName";
        $sth = $this->_connection->query($sql);
        $sth->execute();
        $row = $sth->fetch();
        return $row['count(*)'];
    }

    /**
     * Получает имя страны по ID
     * @param type $id
     * @return type
     */
    public function getRowById($id, $tableName) 
    {
        $sth = $this->_connection->prepare("SELECT * FROM $tableName WHERE id = :id");
        $sth->execute([':id' => $id]);
        $row = $sth->fetch();
        return $row;
    }

    /**
     * Обработка запроса на операции создание, обновления и удаления записей
     * @param string $tableName имя таблицы с которой будем производить операцию
     * @param array $columns столбцы таблицы
     * @param string $scriptName имя скрипта на который будет осуществлен редирект
     * @param string $requireColumn название столбца обызательного для заполнения
     */
    public function handlerRequest($tableName, $columns, $scriptName, $requireColumn) 
    {
        if(empty($_GET['operation']) && empty($_GET['id'])) 
            die('Ошибка в запросе');
        if($_GET['operation'] == Application::OPERATION_CREATE && isset($_POST[$requireColumn]))
            $this->createRow($tableName, $columns, $scriptName);
        if($_GET['operation'] == Application::OPERATION_DELETE) {
            if(empty($_GET['reltable']) && empty($_GET['fk'])) {
                $this->deleteRow($_GET['id'], $tableName, $scriptName);
            } else {
                $this->deleteRelRow($_GET['id'], $tableName, $scriptName, $_GET['reltable'], $_GET['fk']);
            }
        }
        if($_GET['operation'] == Application::OPERATION_UPDATE && isset($_POST[$requireColumn]))
            $this->updateRow($_GET['id'], $tableName, $columns, $scriptName);
    }
    
    /**
     * INSERT страну
     * @param string $tableName имя таблицы с которой будем производить операцию
     * @param array $columns столбцы этой таблицы
     * @param string $scriptName имя скрипта на который редиректим
     */
    private function createRow($tableName, $columns, $scriptName)
    {
        $sql = "INSERT INTO $tableName SET ".$this->pdoSet($columns);
        $sth = $this->_connection->prepare($sql);
        $sth->execute($this->_values);
        $this->redirectToPage($scriptName);
    }
    
    /**
     * UPDATE наименования страны по ID
     * @param integer $id
     * @param string $tableName имя таблицы с которой будем производить операцию
     * @param array $columns столбцы этой таблицы
     * @param string $scriptName имя скрипта на который редиректим
     */
    private function updateRow($id, $tableName, $columns, $scriptName)
    {
        $sql = "UPDATE $tableName SET ".$this->pdoSet($columns)." WHERE id = $id";
        $sth = $this->_connection->prepare($sql);
        $sth->execute($this->_values);
        $this->redirectToPage($scriptName);
    }
    
    /**
     * Удаление строки в таблице
     * @param type $id
     * @param string $tableName имя таблицы с которой будем производить операцию
     * @param string $scriptName имя скрипта на который редиректим
     */
    private function deleteRow($id, $tableName, $scriptName)
    {
        $sql = "DELETE FROM $tableName WHERE id = $id";
        $sth = $this->_connection->prepare($sql);
        $sth->execute();
        $this->redirectToPage($scriptName);
    }
    
    /**
     * Удаление строки в таблице, и строк в связанной таблице по ее внешнему ключу
     * @param type $id
     * @param string $tableName имя таблицы с которой будем производить операцию
     * @param string $scriptName имя скрипта на который редиректим
     * @param string $relTableName имя связанной таблицы
     * @param string $fk внешний ключ
     */
    private function deleteRelRow($id, $tableName, $scriptName, $relTableName, $fk)
    {
        $sql = "DELETE FROM $relTableName WHERE $fk = $id";
        $sth = $this->_connection->prepare($sql);
        $sth->execute();
        $sql = "DELETE FROM $tableName WHERE id = $id";
        $sth = $this->_connection->prepare($sql);
        $sth->execute();
        $this->redirectToPage($scriptName);
    }
    
    /**
     * Редирект на страницу указанной в строке параметре (без '.php')
     * @param string $scriptName
     */
    private function redirectToPage($scriptName)
    {
        $host = $_SERVER['SERVER_NAME'];
        header("Location: http://$host/$scriptName.php");
    }    
}
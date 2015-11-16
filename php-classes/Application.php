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

    public function pdoQuery($tableName) 
    {
        if(isset($_GET['page'])) 
            $page = $_GET['page'];
        else 
            $page = 0;
        $sql = "SELECT * FROM $tableName LIMIT $page, $this->_sizePage";
        //$sql = "SELECT * FROM countries LIMIT 0, 30";
        return $this->_connection->query($sql);
    }
    
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
    public function getCountryName($id) 
    {
        $sth = $this->_connection->prepare('SELECT * FROM countries WHERE id = :id');
        $sth->execute([':id' => $id]);
        $record = $sth->fetch();
        return $record['name'];
    }

    public function handlerCountry() 
    {
        if(isset($_GET['operation']) && isset($_GET['id'])) {
            $opr = $_GET['operation'];
            $id = $_GET['id'];
        } else {
            die('Ошибка в запросе');
        }
        if($opr == Application::OPERATION_CREATE && $_POST['name'])
            $this->createCountry();
        if($opr == Application::OPERATION_DELETE)
            $this->deleteCountry($id);           
        if($opr == Application::OPERATION_UPDATE && $_POST['name'])
            $this->updateCountry($id);
    }
    /**
     * INSERT страну
     */
    private function createCountry()
    {
        $allowed = array("name"); 
        $sql = "INSERT INTO countries SET ".$this->pdoSet($allowed);
        $sth = $this->_connection->prepare($sql);
        $sth->execute($this->_values);
        $this->redirectToCountries();
    }
    
    /**
     * UPDATE наименования страны по ID
     * @param type $id
     */
    private function updateCountry($id)
    {
        $allowed = array("name"); 
        $sql = "UPDATE countries SET ".$this->pdoSet($allowed)." WHERE id = $id";
        $sth = $this->_connection->prepare($sql);
        $sth->execute($this->_values);
        $this->redirectToCountries();
    }
    
    private function deleteCountry($id)
    {
        $sql = "DELETE FROM countries WHERE id = $id";
        $sth = $this->_connection->prepare($sql);
        $sth->execute();
        $this->redirectToCountries();
    }
    
    private function redirectToCountries()
    {
        $host = $_SERVER['SERVER_NAME'];
        header("Location: http://$host/countries.php");
    }    
}
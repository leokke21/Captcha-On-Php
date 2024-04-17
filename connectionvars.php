<?php
class DbConnect
{     //  Параметры подключения к БД   
    private $host = "localhost";   private $port = '3305';
    private $dbname = "mismatchdb";     private $user = "root";           
    private $pass = "";           private $charset = 'utf8';          
    private $options = [    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    public $PDO;
    public $stmt;
    public function connect_pdo()  //метод подключения БД
    {
        // $this->PDO = NULL;
        try {
            $this->PDO = new PDO("mysql:host=" . $this->host . ";
                            dbname=" . $this->dbname . ";  
                            charset=" . $this->charset,  $this->user, 
                            $this->pass,  $this->options  );
        } catch (PDOException $exception) {
            echo "Ошибка соединения: " . $exception->getMessage();
        }         return $this->PDO;
    }

    public function query($query, $params = []) {
        $this->stmt = $this->PDO ->prepare($query);
        $this->stmt->execute($params);
        return $this->stmt;
    }
    public function getInfPDO() //метод возврата PDO для разработчика
    {
        return $this->PDO;
    }
}
$pdo = new DbConnect();
$pdo ->connect_pdo();
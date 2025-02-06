<?php
    class Database {


        private $dbHost = 'localhost';
        private $dbUser ='root';
        private $dbPass = '';
        private $dbName = 'agricult';

        private $statement;
        private $dbHandler;
        private $error;

        //constructeur PDO, qui recevra les valeurs de config.php
        public function __construct() {
            $conn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            try {
                $this->dbHandler = new PDO($conn, $this->dbUser, $this->dbPass, $options);
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        //méthode générique requétes
        public function query($sql) {
            $this->statement = $this->dbHandler->prepare($sql);
        }

        //méthode générique PDO de bind parameters selon le type de $value
        public function bind($parameter, $value, $type = null) {
            switch (is_null($type)) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
            $this->statement->bindValue($parameter, $value, $type);
        }

        //Exécute le statement préparé
        public function execute() {
            return $this->statement->execute();
        }

        //Méthode  array objet
        public function resultSet() {
            $this->execute();
            return $this->statement->fetchAll(PDO::FETCH_OBJ);
        }

        //Méthode row 
        public function single() {
            $this->execute();
            return $this->statement->fetch(PDO::FETCH_OBJ);
        }

        //méthode pour compter les rows
        public function rowCount() {
            return $this->statement->rowCount();
        }
    }

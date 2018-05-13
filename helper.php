<?php

    class connectDB{

        private $conn;
        private $host = "127.0.0.1";
        private $db_name = "api";
        private $username = "root";
        private $password = "root";
        private $dsn;
        private static $instance = null;
        
        private function __construct(){
            $this->dsn = "mysql:host=".$this->host.";dbname=".$this->db_name; 
            $this->conn = new PDO($this->dsn,$this->username,$this->password);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        }

        public static function getInstance(){
            if(!self::$instance){
                self::$instance = new ConnectDb();
            }
            return self::$instance;
        }

        public function getConnection(){
            return $this->conn;
        }
    }

    class Checker{
 
        protected $result = false;

        protected function setResult($result){
            return $this->result = $result;
        }

        protected function getResult(){
            return $this->result;
        }
    }

    class loginChecker extends Checker{

        public $email;
        public $password;
        public $conn;

        public function __construct(){
            $dbInstance = ConnectDb::getInstance();
            $this->conn = $dbInstance->getConnection();
        }

        public function checkLoginStatus($email = "",$password = ""){

            $this->email = $email;
            $this->password = $password;

            //Query to DB for user's email
            $stmt = $this->conn->prepare("SELECT password FROM di_users WHERE email = :email");
            $stmt->execute(['email' => $this->email]);
            $row = $stmt->fetch();

            //Check login password with this email
            if(($this->password == $row['password']) && ($this->password != null)){
                $this->setResult(true); //Return success login
            }
            else $this->setResult(false); //Return failed login
            return $this->getResult();
        }

        public function setRemember($user_id,$email){
            $create_time = time();
            setcookie('lt',$create_time,time() + (86400 * 30), "/");
            setcookie('uid',$user_id,time() + (86400 * 30), "/");
            setcookie('ue',$email,time() + (86400 * 30), "/");
            $stmt = $this->conn->prepare("INSERT INTO di_sessions (user_id,timestamp) VALUE (:user_id,:timestamp);");
            $stmt->execute(['user_id' => $user_id,'timestamp' => $create_time]);
        }

        public function removeRemember($user_id){
            if(isset($_COOKIE['lt'])) {
                unset($_COOKIE['lt']);
                unset($_COOKIE['uid']);
                setcookie('lt', "", time()-3600);
                setcookie('uid', "", time()-3600);
            }
            $stmt = $this->conn->prepare("DELETE FROM di_sessions WHERE user_id=:user_id");
            $stmt->execute(['user_id' => $user_id]);
        }

        public function checkRemember($user_id){
            $stmt = $this->conn->prepare("SELECT user_id,timestamp FROM di_sessions WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $session = $stmt->fetch();
            if(($session['user_id'] == $_COOKIE['uid'])){
                return true;
            }else return false;
        }
    }

    class users{
        
        public $conn;
        public $user_id;

        public function __construct(){
            $dbInstance = ConnectDb::getInstance();
            $this->conn = $dbInstance->getConnection();
        }

        public function getUserByEmail($email){
            $stmt = $this->conn->prepare("SELECT * FROM di_users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch();
            return $row;
        }

        public function getUserById($id){
            $stmt = $this->conn->prepare("SELECT id, email, name, address, telephone FROM di_users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            return $row;
        }

        public function getUserList(){
            $stmt = $this->conn->prepare("SELECT id, email, name, address, telephone FROM di_users");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            return $rows;
        }

        public function updateUserById($id,$dataset){
            $stmt = $this->conn->prepare("UPDATE di_users SET name=:name, address=:address, telephone=:telephone WHERE id=:id");
            $stmt->execute([
                'id' => $id,
                'name' => $dataset['name'],
                'address' => $dataset['address'],
                'telephone' => $dataset['telephone']
            ]);

            return $this->getUserById($id);
        }

        public function insertUser(){
            //Todo
        }

        public function deleteUser(){
            //Todo
        }

    
        public function encrypt($string, $key) {
            $result = '';
            for($i=0; $i<strlen($string); $i++) {
              $char = substr($string, $i, 1);
              $keychar = substr($key, ($i % strlen($key))-1, 1);
              $char = chr(ord($char)+ord($keychar));
              $result.=$char;
            }
            return base64_encode($result);
        }

        public function decrypt($string, $key) {
            $result = '';
            $string = base64_decode($string);
          
            for($i=0; $i<strlen($string); $i++) {
              $char = substr($string, $i, 1);
              $keychar = substr($key, ($i % strlen($key))-1, 1);
              $char = chr(ord($char)-ord($keychar));
              $result.=$char;
            }
          
            return $result;
          }
    }
?>
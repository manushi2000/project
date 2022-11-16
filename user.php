<?php

    class User{
        protected $name;
        protected $phone;
        protected $pin;

        function __construct($phone)
        {
            $this->phone = $phone;
        }

        //setters and getters
        public function setName($name){
            $this->name = $name;;
        }

        public function getName(){
            return $this->name;
        }

        public function getPhone(){
            return $this->phone;
        }

        public function setPin($pin){
            $this->pin = $pin;;
        }

        public function getPin(){
            return $this->pin;
        }


        public function register($pdo){
           try{
                //hash the pin 
                $hashedPin = password_hash($this->getPin(), PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO user (name, pin, phone) values(?,?,?)");
                $stmt->execute([$this->getName(),$hashedPin, $this->getPhone()]);
                
           }catch(PDOException $e){
                echo $e->getMessage();
           }
        }

        public function isUserRegistered($pdo){
           $stmt = $pdo->prepare("SELECT * FROM user WHERE phone=?");
           $stmt->execute([$this->getPhone()]);
           if(count($stmt->fetchAll()) > 0){
               return true;
           }else{
               return false;
           }
        }

        public function readName($pdo){
            $stmt = $pdo->prepare("SELECT * FROM user WHERE phone=?");
            $stmt->execute([$this->getPhone()]);
            $row = $stmt->fetch();
            return $row['name'];
        }

        public function readUserId($pdo){
           $stmt = $pdo->prepare("SELECT uid FROM user where phone=?");
           $stmt->execute([$this->getPhone()]);
           $row = $stmt->fetch();
           return $row['uid'];
        }

        public function correctPin($pdo){
           $stmt = $pdo->prepare("select pin from user where phone=?");
           $stmt->execute([$this->getPhone()]);
           $row = $stmt->fetch();
           if($row == null){
               return false;
           }

           if(password_verify($this->getPin(),$row['pin'])){
               return true;
           }

           return false;
        }


    }
?>
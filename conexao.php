<?php
    function getConnection() {
        try {
            $conn =  new PDO("mysql:host=localhost;dbname=db_valemobi", "usuario-do-banco-de-dados", "senha-do-banco-de-dados");
            $conn->exec("set names utf8");
            return $conn;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }    
    function closeConnection($conn) {
        try {
            $conn = null;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            $conn = null;
        }
    }
?>
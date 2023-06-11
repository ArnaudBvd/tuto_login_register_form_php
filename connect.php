<?php
try {
        $host = 'localhost';
        $dbName = 'authent_pe5';
        $user = 'root';
        $password = '';

        $pdo = new PDO(
            'mysql:host=' . $host . ';dbname=' . $dbName . ';charset=utf8',
            $user,
            $password
        );
    } catch (Exception $e) {

        echo ('Erreur connexion à la base de données : ' . $e->getMessage());

        exit;
    }
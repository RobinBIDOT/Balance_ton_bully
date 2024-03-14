<?php
function dbConnexion(){
    $file_path = '/Applications/MAMP/htdocs/Balance_ton_bully/php/tools/info.json';
    $tool = json_decode(file_get_contents($file_path), true);
    $dsn = "mysql:host=" . $tool['host'] . ";dbname=". $tool['database'] . ";port=". $tool['port'] . ";charset=". $tool['charset'];
    $user = $tool['user'];
    $password = $tool['pwd'];
    try {
        $dbh = new PDO($dsn,$user,$password);
    }catch(Exception $e){
        $message = $e->getMessage();
        echo"Erreur de connexion à la base de données : ".$message;
        exit();
    }

    return $dbh;

}
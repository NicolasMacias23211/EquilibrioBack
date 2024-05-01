<?php

declare(strict_types=1);
namespace App;
Use PDO;
class DataBase
{
    public function __construct(private String $host,
                                private String $name,
                                private String $user,
                                Private String $password)
    {
    }
    public function GetConnection(): PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->name;charset=utf8";
        $pdo = new PDO($dsn,$this->user,$this->password,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        return $pdo;
    }
}

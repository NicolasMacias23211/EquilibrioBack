<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\DataBase;
use PDO;
class UsersRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }

    public function GetAllUsers(): Array
    {
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->query('select * from usuarios;');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
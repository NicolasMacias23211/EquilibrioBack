<?php

declare(strict_types=1);
namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;

class RolesRepository
{

    public function __construct(private DataBase $dataBase)
    {
    }

    public function getAllRoles():string
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->query("
            SELECT 
                r.roleID, 
                r.roleName
            FROM roles r
            ");
            return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); 
        } catch (\Throwable $th) {
            return json_encode(['success' => false, 'Message' => 'Error obteniendo la lista de roles', 'log' => $th->getMessage()]);
        }
    }


}
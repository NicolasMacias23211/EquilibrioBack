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
            return json_encode(['success' => false, 'message' => 'Error obteniendo la lista de roles', 'log' => $th->getMessage()]);
        }
    }

    public function getRoleById(int $id):string
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'SELECT 
                        r.roleID, 
                        r.roleName
                    FROM roles r
                    WHERE r.roleID = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($role) {
                return json_encode(['success' => true, 'data' => $role]);
            } else {
                return json_encode(['success' => false, 'message' => 'Rol no encontrado']);
            }
        } catch (\Throwable $th) {
            return json_encode(['success' => false, 'message' => 'Error consultando el rol', 'log' => $th->getMessage()]);
        }
    }

    public function getRoleByName(string $name):array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'SELECT 
                        r.roleID, 
                        r.roleName
                    FROM roles r
                    WHERE r.roleName = :name';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($role) {
                return ['success' => true, 'data' => $role];
            } else {
                return ['success' => false, 'message' => 'Rol no encontrado'];
            }
        } catch (\Throwable $th) {
            return ['success' => false, 'message' => 'Error consultando el rol', 'log' => $th->getMessage()];
        }
    }


}
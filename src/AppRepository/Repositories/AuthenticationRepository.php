<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;
class AuthenticationRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }

    public function GetAutentication(String $usuario): ?string
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'SELECT userName,password FROM members WHERE userName = :userName';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':userName', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return  $result['password'];
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

}


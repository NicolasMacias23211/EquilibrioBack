<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\DataBase;
use PDO;
class AutenticacionRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }

    public function GetAutentication(String $usuario, String $contraseña): ?int
    {
        $pdo = $this->dataBase->GetConnection();
        $sql = 'SELECT Usuarios_idUsuario FROM credenciales WHERE Username = ? AND Contraseña = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $usuario, PDO::PARAM_STR);
        $stmt->bindValue(':contraseña', $contraseña, PDO::PARAM_STR);
        $stmt->execute([$usuario,$contraseña]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return (int) $result['Usuarios_idUsuario'];
        } else {
            return null;
        }
    }

}


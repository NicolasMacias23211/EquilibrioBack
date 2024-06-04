<?php
declare(strict_types=1);
namespace AppRepository\Repositories;
use App\DataBase;
use PDO;
use PDOException;

class CitasRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }
    public function InsertCita(array $body): bool
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'INSERT INTO citas (DireccionDeLaCita, IdEstadoDeCita, IdEmpleado, IdUsuario, idServicio) VALUES (?, ?, ?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $body['DireccionDeLaCita'], PDO::PARAM_STR);
            $stmt->bindValue(2, $body['IdEstadoDeCita'], PDO::PARAM_INT);
            $stmt->bindValue(3, $body['IdEmpleado'], PDO::PARAM_INT);
            $stmt->bindValue(4, $body['IdUsuario'], PDO::PARAM_INT);
            $stmt->bindValue(5, $body['idServicio'], PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log('Error al insertar cita: ' . $e->getMessage());
            return false;
        }
    }




}
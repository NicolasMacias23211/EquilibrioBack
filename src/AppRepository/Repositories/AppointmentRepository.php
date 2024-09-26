<?php
declare(strict_types=1);
namespace AppRepository\Repositories;
use App\DataBase;
use PDO;
use PDOException;

class AppointmentRepository
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

    public function GetAllCitas(): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->query(
                "SELECT 
                            c.IdCita,
                            c.DireccionDeLaCita,
                            CONCAT(e.Nombre, ' ', e.PrimerApellido, ' ', e.SegundoApellido) AS NombreCompletoEmpleado,
                            CONCAT(u.Nombre, ' ', u.PrimerApellido, ' ', u.SegundoApellido) AS NombreCompletoUsuario,
                            s.Nombre AS NombreServicio,
                            esc.Estado AS EstadoDeCita
                        FROM 
                            citas c
                        JOIN 
                            empleados e ON c.IdEmpleado = e.IdEmpleado
                        JOIN 
                            usuarios u ON c.IdUsuario = u.IdUsuario
                        JOIN 
                            servicios s ON c.idServicio = s.idServicio
                        JOIN 
                            estadosxcitas esc ON c.IdEstadoDeCita = esc.IdEstadoDeCita;");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            error_log("Error consultando las citas en el sistema:" . " - " . $e->getMessage());
            return (['success' => false, 'Message' => 'Error consultando las citas en el sistema']);
        }
    }




}
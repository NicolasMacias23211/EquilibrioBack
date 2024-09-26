<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\DataBase;
use PDO;
use PDOException;

class EmployeesRepository
{
    public function __construct(private DataBase $dataBase, private UsersRepository $UsersRepository)
    {
    }

    public function GetAllEmpleados(): array
    {
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->query('select * from empleados;');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetEmpleadoById(int $id): string
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'SELECT * FROM empleados WHERE idEmpleado = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $empleado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($empleado) {
                return json_encode(['success' => true, 'data' => $empleado]);
            } else {
                return json_encode(['success' => false, 'Message' => 'Empleado no encontrado']);
            }
        } catch (PDOException $e) {
            error_log("Error consultando el empleado: " . $id . " - " . $e->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error consultando el empleado']);
        }
    }

    public function Create(array $data) :string
    {
        $sql = 'insert into empleados (Nombre,Apellido,UltimoTituloProfecional,Foto,CampoDeProfundizacion,Documento,Correo) 
        values (:nombre,:apellido,:titulo,:foto,:campo,:Documento,:Correo)';
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nombre',$data['nombre'],PDO::PARAM_STR);
        $stmt->bindValue(':apellido',$data['apellido'],PDO::PARAM_STR);
        $stmt->bindValue(':titulo',$data['titulo'],PDO::PARAM_STR);
        $stmt->bindValue(':foto',$data['foto'],PDO::PARAM_STR);
        $stmt->bindValue(':Documento',$data['Documento'],PDO::PARAM_STR);
        $stmt->bindValue(':campo',$data['campo'],PDO::PARAM_STR);
        $stmt->bindValue(':Correo',$data['Correo'],PDO::PARAM_STR);
        $stmt->execute();
        return $pdo->lastInsertId();
    }
    public function UpdateEmployee(array $data) :string
    {
        try {
            $sql = 'UPDATE empleados 
        SET Nombre = :nombre,
            PrimerApellido = :primerApellido,
            SegundoApellido = :segundoApellido,
            Telefono = :telefono,
            UltimoTituloProfesional = :UltimoTituloProfesional,
            Documento = :Documento,
            Correo = :Correo,
            CampoDeProfundizacion = :campo
        WHERE IdEmpleado = :idEmpleado';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nombre', $data['Nombre'], PDO::PARAM_STR);
            $stmt->bindValue(':primerApellido', $data['PrimerApellido'], PDO::PARAM_STR);
            $stmt->bindValue(':segundoApellido', $data['SegundoApellido'], PDO::PARAM_STR);
            $stmt->bindValue(':telefono', $data['Telefono'], PDO::PARAM_INT);
            $stmt->bindValue(':UltimoTituloProfesional', $data['UltimoTituloProfesional'], PDO::PARAM_STR);
            $stmt->bindValue(':Documento', $data['Documento'], PDO::PARAM_STR);
            $stmt->bindValue(':Correo', $data['Correo'], PDO::PARAM_STR);
            $stmt->bindValue(':campo', $data['CampoDeProfundizacion'], PDO::PARAM_STR);
            $stmt->bindValue(':idEmpleado', $data['IdEmpleado'], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return json_encode(['success' => true, 'Message' => 'Actualización exitosa']);
            } else {
                return json_encode(['success' => false, 'Message' => 'No se realizaron cambios o no se encontró el registro.']);
            }
        }catch (PDOException $e) {
            error_log("Error actualizando el empleado:" .json_encode($data, JSON_PRETTY_PRINT) . " - " . $e->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error actualizando el empleado']);
        }
    }

}
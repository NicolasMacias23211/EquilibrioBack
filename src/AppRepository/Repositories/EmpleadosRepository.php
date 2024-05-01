<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\DataBase;
use PDO;
class EmpleadosRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }
    public function GetAllEmpleados(): Array
    {
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->query('select * from empleados;');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function GetEmpleadoById(int $id): Array|bool
    {
        $pdo = $this->dataBase->GetConnection();
        $sql = 'select * from empleados where idEmpleado = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
    public function Update(array $data) :string
    {
        //todo: hacer el SQl
        $sql = '';
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

}
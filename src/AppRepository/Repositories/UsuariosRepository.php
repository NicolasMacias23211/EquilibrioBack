<?php

namespace AppRepository\Repositories;

use App\DataBase;
use PDO;
class UsuariosRepository
{

    public function __construct(private DataBase $dataBase)
    {
    }

    public function InserNewUser(array $data) :string
    {
        $sql = 'insert into anamnesis (AntecedentesQuirurgicos,AntecedentesFarmacologicos,AntecedentesToxicoAlergicos,Fuma,Alcohol,Ejercicio,AntecedentesFamiliares) 
        values (:antecendetesQuirurgicos,:antecendetesFarmaceuticos,:antecendetesToxicos,:fuma,:bebe,:ejercisio,:antecendetesFamiliares)';
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':antecendetesQuirurgicos',$data['antecendetesQuirurgicos'],PDO::PARAM_STR);
        $stmt->bindValue(':antecendetesFarmaceuticos',$data['antecendetesFarmaceuticos'],PDO::PARAM_STR);
        $stmt->bindValue(':antecendetesToxicos',$data['antecendetesToxicos'],PDO::PARAM_STR);
        $stmt->bindValue(':fuma',$data['fuma'],PDO::PARAM_STR);
        $stmt->bindValue(':bebe',$data['bebe'],PDO::PARAM_STR);
        $stmt->bindValue(':ejercisio',$data['ejercisio'],PDO::PARAM_STR);
        $stmt->bindValue(':antecendetesFamiliares',$data['antecendetesFamiliares'],PDO::PARAM_STR);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function InserNewAnamnesis(array $data) :string
    {
        $sql = 'insert into anamnesis (AntecedentesQuirurgicos,AntecedentesFarmacologicos,AntecedentesToxicoAlergicos,Fuma,Alcohol,Ejercicio,AntecedentesFamiliares) 
        values (:antecendetesQuirurgicos,:antecendetesFarmaceuticos,:antecendetesToxicos,:fuma,:bebe,:ejercisio,:antecendetesFamiliares)';
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':antecendetesQuirurgicos',$data['antecendetesQuirurgicos'],PDO::PARAM_STR);
        $stmt->bindValue(':antecendetesFarmaceuticos',$data['antecendetesFarmaceuticos'],PDO::PARAM_STR);
        $stmt->bindValue(':antecendetesToxicos',$data['antecendetesToxicos'],PDO::PARAM_STR);
        $stmt->bindValue(':fuma',$data['fuma'],PDO::PARAM_STR);
        $stmt->bindValue(':bebe',$data['bebe'],PDO::PARAM_STR);
        $stmt->bindValue(':ejercisio',$data['ejercisio'],PDO::PARAM_STR);
        $stmt->bindValue(':antecendetesFamiliares',$data['antecendetesFamiliares'],PDO::PARAM_STR);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function InserNewCredentials(array $data) :string
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

}
<?php

namespace AppRepository\Repositories;

use App\DataBase;
use PDO;
class UsuariosRepository
{

    public function __construct(private DataBase $dataBase)
    {
    }

    public function InsertNewUser(array $data) : string
    {
        $sql = 'INSERT INTO usuarios (Nombre, PrimerApellido, SegundoApellido, Documento, FechaNacimiento, Sexo, CorreoElectronico, Telefono, Direccion, Ocupacion, RH, Anamnesis_IdAnamnesis) 
            VALUES (:nombre, :primerApellido, :segundoApellido, :documento, :fechaNacimiento, :sexo, :correoElectronico, :telefono, :direccion, :ocupacion, :rh, :anamnesisId)';
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindValue(':primerApellido', $data['primerApellido'], PDO::PARAM_STR);
        $stmt->bindValue(':segundoApellido', $data['segundoApellido'], PDO::PARAM_STR);
        $stmt->bindValue(':documento', $data['documento'], PDO::PARAM_STR);
        $stmt->bindValue(':fechaNacimiento', $data['fechaNacimiento'], PDO::PARAM_STR);
        $stmt->bindValue(':sexo', $data['sexo'], PDO::PARAM_STR);
        $stmt->bindValue(':correoElectronico', $data['correoElectronico'], PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $data['telefono'], PDO::PARAM_INT);
        $stmt->bindValue(':direccion', $data['direccion'], PDO::PARAM_STR);
        $stmt->bindValue(':ocupacion', $data['ocupacion'], PDO::PARAM_STR);
        $stmt->bindValue(':rh', $data['rh'], PDO::PARAM_STR);
        $stmt->bindValue(':anamnesisId', $data['anamnesisId'], PDO::PARAM_INT);
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

    public function InsertNewCredential(array $data) : string
    {
        $sql = 'INSERT INTO credenciales (Username, Contraseña, Usuarios_IdUsuario) 
            VALUES (:username, :contrasena, :usuariosId)';

        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':contrasena', $data['contrasena'], PDO::PARAM_STR);
        $stmt->bindValue(':usuariosId', $data['usuariosId'], PDO::PARAM_INT);

        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function GetUserByDocument(string $documento)
    {
        $sql = 'SELECT * FROM usuarios WHERE Documento = :documento';

        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':documento', $documento, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function UsernameExists(string $username) : bool
    {
        $sql = 'SELECT COUNT(*) FROM credenciales WHERE Username = :username';

        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }


}
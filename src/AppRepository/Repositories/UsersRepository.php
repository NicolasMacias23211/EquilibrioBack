<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\DataBase;
use PDO;
use PDOException;

class UsersRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }
    public function InsertNewUser(array $data) : string
    {
        try {
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

            return json_encode(['success' => true, 'message' => 'Usuario registrado correctamente','lastInsertId' => $pdo->lastInsertId()]);
        } catch (PDOException $e) {
            error_log("Error registrando nuevo usuario:". json_encode($data) . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error  registrando nuevo usuario' ]);
        }
    }


    public function InserNewAnamnesis(array $data) :string
    {
        try {
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
            return json_encode(['success' => true, 'lastInsertId' => $pdo->lastInsertId(), 'message' => 'Anamnesis creada correctaemnte',]);
        } catch (PDOException $e) {
            error_log("Error creado la nueva anamnesis: " . json_encode($data)  . $e->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error creado la nueva anamnesis']);
        }
    }

    public function InsertNewCredential(array $data) : string
    {
        try {
            $sql = 'INSERT INTO credenciales (Username, Contraseña, Usuarios_IdUsuario) 
                VALUES (:username, :contrasena, :usuariosId)';

            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindValue(':contrasena', $data['contrasena'], PDO::PARAM_STR);
            $stmt->bindValue(':usuariosId', $data['usuariosId'], PDO::PARAM_INT);

            $stmt->execute();
            return json_encode(['success' => true, 'lastInsertId' => $pdo->lastInsertId(), 'message' => 'Credenciales creadas con exito',]);
        } catch (PDOException $e) {
            error_log("Error creando las credenciales" . json_encode($data) . " - " . $e->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error creando las credenciales']);
        }
    }

    public function GetUserByDocument(string $documento) : string
    {
        try {
            $sql = 'SELECT IdUsuario FROM usuarios WHERE Documento = :documento';

            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':documento', $documento, PDO::PARAM_STR);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return json_encode(['success' => true, 'IdUsuario' => $result['IdUsuario']]);
            } else {
                return json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            }
        } catch (PDOException $e) {
            error_log("Error consultando el documento:" . $documento . " - " . $e->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error consultando el documento']);
        }
    }

    public function UsernameExists(string $username) : string
    {
        try {
        $sql = 'SELECT COUNT(*) FROM credenciales WHERE Username = :username';

        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->execute();
        $count = $stmt->fetchColumn();
        if ($count > 0){
            return json_encode(['success' => false]);
        } else {
            return json_encode(['success' => true]);
        }
        }catch (PDOException $e) {
            error_log("Error consultando el usuario:" . $username . " - " . $e->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error consultando el usuario']);
        }
    }

    public function GetAllUsers(): array
    {
        try {
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->query('select * from usuarios;');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            error_log("Error consultando los usuarios en el sistema:" . " - " . $e->getMessage());
            return (['success' => false, 'Message' => 'Error consultando los usuarios en el sistema']);
        }
    }
}
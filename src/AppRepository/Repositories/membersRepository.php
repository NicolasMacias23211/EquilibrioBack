<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;
use PDOException;

class membersRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }

    public function GetAllprofessionals(?int $serviceID = null): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();

            $sql = "
                SELECT 
                    m.document, 
                    m.name, 
                    m.lastName, 
                    m.mail, 
                    m.phone, 
                    m.photo,
                    m.address,
                    GROUP_CONCAT(fs.nameFieldStudy SEPARATOR ', ') AS fieldsOfStudy
                FROM members m
                LEFT JOIN membersFieldsOfStudy mfs ON m.document = mfs.members_document
                LEFT JOIN fieldOfStudy fs ON mfs.fieldOfStudy_fieldOfStudyID = fs.fieldOfStudyID
                ";
            if ($serviceID !== null) {
                $sql .= "
                    INNER JOIN membersByServices mbs ON m.document = mbs.members_document
                    WHERE m.userType = 'professional'
                    AND m.memberStatus = 'E'
                    AND mbs.serviceID = :serviceID
                ";
            } else {
                $sql .= "
                    WHERE m.userType = 'professional'
                    AND m.memberStatus = 'E'
                ";
            }

            $sql .= " GROUP BY m.document";

            $stmt = $pdo->prepare($sql);

            if ($serviceID !== null) {
                $stmt->bindValue(':serviceID', $serviceID, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
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
                return json_encode(['success' => false, 'message' => 'Empleado no encontrado']);
            }
        } catch (PDOException $e) {
            error_log("Error consultando el empleado: " . $id . " - " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error consultando el empleado']);
        }
    }

    public function createMembers(array $data): array
    {
        try {
            $sql = 'INSERT INTO members (
                document, 
                name, 
                secondName, 
                lastName, 
                secondLastName, 
                birthdate, 
                gender, 
                mail, 
                phone, 
                address, 
                occupation, 
                RH, 
                photo, 
                userName, 
                password, 
                userType, 
                memberStatus, 
                anamnesisID, 
                roles_roleID
            ) VALUES (
                :document, 
                :name, 
                :secondName, 
                :lastName, 
                :secondLastName, 
                :birthdate, 
                :gender, 
                :mail, 
                :phone, 
                :address, 
                :occupation, 
                :RH, 
                :photo, 
                :userName, 
                :password, 
                :userType, 
                :memberStatus, 
                :anamnesisID, 
                :roles_roleID
            )';
            
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':document', $data['documento'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindValue(':secondName', $data['segundoNombre'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':lastName', $data['primerApellido'], PDO::PARAM_STR);
            $stmt->bindValue(':secondLastName', $data['segundoApellido'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':birthdate', $data['fechaNacimiento'], PDO::PARAM_STR);
            $stmt->bindValue(':gender', $data['sexo'], PDO::PARAM_STR);
            $stmt->bindValue(':mail', $data['correoElectronico'], PDO::PARAM_STR);
            $stmt->bindValue(':phone', $data['telefono'], PDO::PARAM_INT);
            $stmt->bindValue(':address', $data['direccion'], PDO::PARAM_STR);
            $stmt->bindValue(':occupation', $data['ocupacion'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':RH', $data['rh'], PDO::PARAM_STR);
            $stmt->bindValue(':photo', $data['photo'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':userName', $data['userName'], PDO::PARAM_STR);
            $stmt->bindValue(':password', $data['password'], PDO::PARAM_STR);
            $stmt->bindValue(':userType', $data['userType'], PDO::PARAM_STR);
            $stmt->bindValue(':memberStatus', $data['memberStatus'], PDO::PARAM_STR);
            $stmt->bindValue(':anamnesisID', $data['anamnesisID'] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':roles_roleID', $data['roles_roleID'], PDO::PARAM_INT);
            
            $stmt->execute();
            return ['success' => true, 'id' => $data['documento']];
        } catch (PDOException $e) {
            error_log("Error creating member: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error creating member'];
        }
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
                return json_encode(['success' => true, 'message' => 'Actualización exitosa']);
            } else {
                return json_encode(['success' => false, 'message' => 'No se realizaron cambios o no se encontró el registro.']);
            }
        }catch (PDOException $e) {
            error_log("Error actualizando el empleado:" .json_encode($data, JSON_PRETTY_PRINT) . " - " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Error actualizando el empleado']);
        }
    }

    public function getMemberByDocument(int $document): array
    {
        try {
            $sql = '
                SELECT 
                    m.name,
                    m.secondName,
                    m.lastName,
                    m.address,
                    m.photo,
                    r.roleName
                FROM members m
                INNER JOIN roles r ON m.roles_roleID = r.roleID
                WHERE m.document = :document
            ';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':document', $document, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching member by document: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error fetching member by document'];
        }
    }

}
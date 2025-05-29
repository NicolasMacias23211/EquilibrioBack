<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;
use PDOException;

class membersByServicesRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }

    public function createMembersByServices(array $data): string
    {
        try {
            $sql = 'INSERT INTO membersByServices (
                members_document,
                serviceID,
                servicePackagesID
            ) VALUES (
                :members_document,
                :serviceID,
                :servicePackagesID
            )';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':members_document', $data['members_document'], PDO::PARAM_INT);
            $stmt->bindValue(':serviceID', $data['serviceID'], PDO::PARAM_INT);
            $stmt->bindValue(':servicePackagesID', $data['servicePackagesID'], PDO::PARAM_INT);
            $stmt->execute();
            return json_encode(['success' => true, 'message' => 'Miembro asignado a servicio correctamente']);
        } catch (\Throwable $th) {
            error_log("Error asignando miembro a servicio: " . $th->getMessage());
            return json_encode(['success' => false, 'message' => 'Error asignando miembro a servicio']);
        }
    }

    public function getAllMembersByServices(): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare('SELECT * FROM membersByServices');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function getMembersByServicesByMemberID(int $memberID): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare('SELECT * FROM membersByServices WHERE members_document = :members_document');
            $stmt->bindValue(':members_document', $memberID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function getMembersByServicesByServiceID(int $serviceID): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare('SELECT * FROM membersByServices WHERE serviceID = :serviceID');
            $stmt->bindValue(':serviceID', $serviceID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function updateMembersByServices(array $data): string
    {
        try {
            $sql = 'UPDATE membersByServices SET
            members_document = :members_document,
            serviceID = :serviceID,
            servicePackagesID = :servicePackagesID
            WHERE memberByServiceID = :memberByServiceID';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':memberByServiceID', $data['memberByServiceID'], PDO::PARAM_INT);
            $stmt->bindValue(':members_document', $data['members_document'], PDO::PARAM_INT);
            $stmt->bindValue(':serviceID', $data['serviceID'], PDO::PARAM_INT);
            $stmt->bindValue(':servicePackagesID', $data['servicePackagesID'], PDO::PARAM_INT);
            $stmt->execute();
            return json_encode(['success' => true, 'message' => 'Miembro asignado a servicio actualizado correctamente']);
        } catch (\Throwable $th) {
            error_log("Error actualizando miembro asignado a servicio: " . $th->getMessage());
            return json_encode(['success' => false, 'message' => 'Error actualizando miembro asignado a servicio']);
        }
    }
}
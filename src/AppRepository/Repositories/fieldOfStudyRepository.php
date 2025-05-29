<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;
use PDOException;


class fieldOfStudyRepository
{

    public function __construct(private DataBase $dataBase)
    {
    }

    public function createFieldOfStudy(array $data): string
    {
        try {
            $sql = 'INSERT INTO fieldOfStudy (
                nameFieldStudy,
                description
            ) VALUES (
                :nameFieldStudy,
                ;description
            )';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nameFieldStudy', $data['nameFieldStudy'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
            $stmt->execute();
            return json_encode(['success' => true, 'message' => 'Campo de estudio creado correctamente']);
        } catch (\Throwable $th) {
            error_log("Error creando campo de estudio: " . $th->getMessage());
            return json_encode(['success' => false, 'message' => 'Error creando campo de estudio']);
        }
    }

    public function getFieldOfStudyByName(string $nameFieldStudy): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare("
                SELECT 
                    fieldOfStudyID
                FROM fieldOfStudy
                WHERE nameFieldStudy = :nameFieldStudy
            ");
            $stmt->bindValue(':nameFieldStudy', $nameFieldStudy, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function editFieldOfStudy(array $data): string
    {
        try {
            $sql = 'UPDATE fieldOfStudy SET
            nameFieldStudy = :nameFieldStudy,
            description = :description
            WHERE fieldOfStudyID = :fieldOfStudyID';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nameFieldStudy', $data['nameFieldStudy'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindValue(':fieldOfStudyID', $data['fieldOfStudyID'], PDO::PARAM_INT);
            $stmt->execute();
            return json_encode(['success' => true, 'message' => 'Campo de estudio actualizado correctamente']);
        } catch (\Throwable $th) {
            error_log("Error actualizando campo de estudio: " . $th->getMessage());
            return json_encode(['success' => false, 'message' => 'Error actualizando campo de estudio']);
        }
    }


}
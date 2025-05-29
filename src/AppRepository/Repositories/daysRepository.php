<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;
use PDOException;


class daysRepository
{

    public function __construct(private DataBase $dataBase)
    {
    }

    public function createDay(array $data): string
    {
        try {
            $sql = 'INSERT INTO days (
                dayName
            ) VALUES (
                :dayName
            )';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':dayName', $data['dayName'], PDO::PARAM_STR);
            $stmt->execute();
            return json_encode(['success' => true, 'message' => 'Dia creado correctamente']);
        } catch (\Throwable $th) {
            error_log("Error creando dia: " . $th->getMessage());
            return json_encode(['success' => false, 'message' => 'Error creando dia']);
        }
    }

    public function getAllDays(): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare('SELECT * FROM days');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function getDayByName(string $dayName): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare('SELECT * FROM days WHERE dayName = :dayName');
            $stmt->bindValue(':dayName', $dayName, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function getDayById(int $dayId): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare('SELECT * FROM days WHERE dayID = :dayID');
            $stmt->bindValue(':dayID', $dayId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function updateDay(array $data): string
    {
        try {
            $sql = 'UPDATE days SET
            dayName = :dayName
            WHERE dayID = :dayID';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':dayName', $data['dayName'], PDO::PARAM_STR);
            $stmt->bindValue(':dayID', $data['dayID'], PDO::PARAM_INT);
            $stmt->execute();
            return json_encode(['success' => true, 'message' => 'Dia actualizado correctamente']);
        } catch (\Throwable $th) {
            error_log("Error actualizando dia: " . $th->getMessage());
            return json_encode(['success' => false, 'message' => 'Error actualizando dia']);
        }
    }

    public function deleteDay(int $dayId): string
    {
        try {
            $sql = 'DELETE FROM days WHERE dayID = :dayID';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':dayID', $dayId, PDO::PARAM_INT);
            $stmt->execute();
            return json_encode(['success' => true, 'message' => 'Dia eliminado correctamente']);
        } catch (\Throwable $th) {
            error_log("Error eliminando dia: " . $th->getMessage());
            return json_encode(['success' => false, 'message' => 'Error eliminando dia']);
        }
    }
}
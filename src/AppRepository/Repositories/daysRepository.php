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
        $sql = 'INSERT INTO days (
            dayName
        ) VALUES (
            :dayName
        )';
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':dayName', $data['dayName'], PDO::PARAM_STR);
        $stmt->execute();
        return json_encode(['success' => true, 'Message' => 'Dia creado correctamente']);
    }

    public function getAllDays(): array
    {
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare('SELECT * FROM days');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDayByName(string $dayName): array
    {
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare('SELECT * FROM days WHERE dayName = :dayName');
        $stmt->bindValue(':dayName', $dayName, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDayById(int $dayId): array
    {
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare('SELECT * FROM days WHERE dayID = :dayID');
        $stmt->bindValue(':dayID', $dayId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateDay(array $data): string
    {
        $sql = 'UPDATE days SET
            dayName = :dayName
            WHERE dayID = :dayID';
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':dayName', $data['dayName'], PDO::PARAM_STR);
        $stmt->bindValue(':dayID', $data['dayID'], PDO::PARAM_INT);
        $stmt->execute();
        return json_encode(['success' => true, 'Message' => 'Dia actualizado correctamente']);
    }

    public function deleteDay(int $dayId): string
    {
        $sql = 'DELETE FROM days WHERE dayID = :dayID';
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':dayID', $dayId, PDO::PARAM_INT);
        $stmt->execute();
        return json_encode(['success' => true, 'Message' => 'Dia eliminado correctamente']);
    }
}
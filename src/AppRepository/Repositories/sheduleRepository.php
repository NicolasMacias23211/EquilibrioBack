<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;
use PDOException;

class sheduleRepository
{

    public function __construct(private DataBase $dataBase)
    {
    }

    public function createShedule(array $data,int $day): string
    {

        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'INSERT INTO schedule (
                startTime, 
                endTime, 
                available, 
                days_dayID, 
                member_document
            ) VALUES (
                :startTime, 
                :endTime, 
                :available, 
                :days_dayID, 
                :member_document
            )';
        
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':startTime', $data['startTime'], PDO::PARAM_STR);
            $stmt->bindValue(':endTime', $data['endTime'], PDO::PARAM_STR);
            $stmt->bindValue(':available', $data['available'] ?? 1, PDO::PARAM_INT);
            $stmt->bindValue(':days_dayID', $day, PDO::PARAM_INT);
            $stmt->bindValue(':member_document', $data['member_document'], PDO::PARAM_INT);
            $stmt->execute();    
            return json_encode(['success' => true, 'Message' => 'Horario asignado correctamente']);
        } catch (\Throwable $th) {
            error_log("Error asignando horario: " . $th->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error asignando horario']);
        }
    }

    public function getSheduleByMemberDocument(string $document): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare("SELECT * FROM schedule WHERE member_document = :document AND available = 1");
            $stmt->bindValue(':document', $document, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

}
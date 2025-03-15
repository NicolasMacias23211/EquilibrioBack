<?php

namespace AppRepository\Repositories;

use App\databaseConnection\DataBase;
use PDO;
use PDOException;


class ServicesRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }
    
    public function getAllServices(): array
    {
        try {
            $sql = '
                SELECT 
                    s.serviceID,
                    s.serviceName,
                    s.serviceDescription,
                    s.cost,
                    s.duration,
                    s.uniqueService,
                    f.nameFieldStudy
                FROM 
                    services s
                INNER JOIN 
                    fieldOfStudy f ON s.fieldOfStudyID = f.fieldOfStudyID
            ';
            
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching services: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error fetching services'];
        }
    }

}
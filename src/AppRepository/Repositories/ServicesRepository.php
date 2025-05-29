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
    
    public function getAllServices(?int $members_document = null): array
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
                    s.image,
                    f.nameFieldStudy
                FROM 
                    services s
                INNER JOIN 
                    fieldOfStudy f ON s.fieldOfStudyID = f.fieldOfStudyID
            ';
            if ($members_document !== null) {
                $sql .= '
                    INNER JOIN membersByServices mbs ON s.serviceID = mbs.serviceID
                    WHERE mbs.members_document = :members_document
                ';
            }
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            if ($members_document !== null) {
                $stmt->bindValue(':members_document', $members_document, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching services: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error fetching services'];
        }
    }

    public function getServiceById(int $serviceID): array | null
    {
        try {
            $sql = '
                SELECT 
                    s.serviceID,
                    s.serviceName,
                    s.serviceDescription
                FROM 
                    services s
                WHERE 
                    s.serviceID = :serviceID
            ';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':serviceID', $serviceID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching service by ID: " . $e->getMessage());
            return null;
        }
    }

}
<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;
use PDOException;


class MembersFieldsOfStudyRepository
{

    public function __construct(private DataBase $dataBase)
    {
    }

    public function createMembersFieldsOfStudy(array $data): string
    {
        try {
            $sql = 'INSERT INTO membersFieldsOfStudy (
                members_document, 
                fieldOfStudy_fieldOfStudyID
            ) VALUES (
                :members_document, 
                :fieldOfStudy_fieldOfStudyID
            )';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':members_document', $data['members_document'], PDO::PARAM_STR);
            $stmt->bindValue(':fieldOfStudy_fieldOfStudyID', $data['fieldOfStudy_fieldOfStudyID'], PDO::PARAM_INT);
            $stmt->execute();
            return json_encode(['success' => true, 'Message' => 'Campo de estudio asignado correctamente']);
        } catch (\Throwable $th) {
            error_log("Error asignando campo de estudio: " . $th->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error asignando campo de estudio']);
        }
    }


    public function GetFieldsOfStudyByMemberDocument(string $document): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare("
            SELECT 
                fs.nameFieldStudy
            FROM members m
            LEFT JOIN membersFieldsOfStudy mfs ON m.document = mfs.members_document
            LEFT JOIN fieldOfStudy fs ON mfs.fieldOfStudy_fieldOfStudyID = fs.fieldOfStudyID
            WHERE m.document = :document");
            $stmt->bindValue(':document', $document, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

}
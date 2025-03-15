<?php
declare(strict_types=1);

namespace AppRepository\Repositories;
use App\databaseConnection\DataBase;
use PDO;
use PDOException;

class anamnesisRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }

    public function GetAllAnamnesis(): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->query("
            SELECT 
                anamnesisID,
                surgicalHistory,
                pharmacologicalBackground,
                backgroundToxicAllergic,
                smoke,
                alcohol,
                exercise,
                familyBackground
            FROM anamnesis");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function GetAnamnesisById(int $id): string
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'SELECT 
                        anamnesisID,
                        surgicalHistory,
                        pharmacologicalBackground,
                        backgroundToxicAllergic,
                        smoke,
                        alcohol,
                        exercise,
                        familyBackground
                    FROM anamnesis 
                    WHERE anamnesisID = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $anamnesis = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($anamnesis) {
                return json_encode(['success' => true, 'data' => $anamnesis]);
            } else {
                return json_encode(['success' => false, 'Message' => 'Anamnesis no encontrada']);
            }
        } catch (PDOException $e) {
            error_log("Error consultando la anamnesis: " . $id . " - " . $e->getMessage());
            return json_encode(['success' => false, 'Message' => 'Error consultando la anamnesis']);
        }
    }


    public function createNewAnamnesis(array $data): array
    {
        try {
            $sql = 'INSERT INTO anamnesis (
                surgicalHistory,
                pharmacologicalBackground,
                backgroundToxicAllergic,
                smoke,
                alcohol,
                exercise,
                familyBackground
            ) VALUES (
                :surgicalHistory,
                :pharmacologicalBackground,
                :backgroundToxicAllergic,
                :smoke,
                :alcohol,
                :exercise,
                :familyBackground
            )';
            $pdo = $this->dataBase->GetConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':surgicalHistory', $data['antecendetesQuirurgicos'], PDO::PARAM_STR);
            $stmt->bindValue(':pharmacologicalBackground', $data['antecendetesFarmaceuticos'], PDO::PARAM_STR);
            $stmt->bindValue(':backgroundToxicAllergic', $data['antecendetesToxicos'], PDO::PARAM_STR);
            $stmt->bindValue(':smoke', $data['fuma'] ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindValue(':alcohol', $data['bebe'] ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindValue(':exercise', $data['ejercisio'] ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindValue(':familyBackground', $data['antecendetesFamiliares'], PDO::PARAM_STR);
            $stmt->execute();
            
            $lastInsertId = $pdo->lastInsertId();
            
            return ['success' => true, 'Message' => 'Anamnesis creada correctamente', 'id' => $lastInsertId];
        } catch (PDOException $e) {
            error_log("Error creando la anamnesis: " . $e->getMessage());
            return ['success' => false, 'Message' => 'Error creando la anamnesis'];
        }
    }

}
<?php

namespace AppRepository\Repositories; 

use App\databaseConnection\DataBase;
use PDO;
use PDOException;


class AppointmentRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }

    public function createAppointment(array $data): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'INSERT INTO scheduledAppointments (
                address,
                notes,
                date,
                isHomeService,
                MembersDocument,
                professionalDocument,
                schedule_scheduleId,
                appointmentStatus,
                serviceID,
                appointmentByServicesPackagesID
            ) VALUES (
                :address,
                :notes,
                :date,
                :isHomeService,
                :MembersDocument,
                :professionalDocument,
                :schedule_scheduleId,
                :appointmentStatus,
                :serviceID,
                NULL
            )';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':address', $data['addres'], PDO::PARAM_STR);
            $stmt->bindValue(':notes', $data['notes'], PDO::PARAM_STR);
            $stmt->bindValue(':date', $data['date'], PDO::PARAM_STR);
            $stmt->bindValue(':isHomeService', $data['isHomeService'], PDO::PARAM_INT);
            $stmt->bindValue(':MembersDocument', $data['UserDocumentId'], PDO::PARAM_INT);
            $stmt->bindValue(':professionalDocument', $data['ProfessionalId'], PDO::PARAM_INT);
            $stmt->bindValue(':schedule_scheduleId', $data['sheduleId'], PDO::PARAM_INT);
            $stmt->bindValue(':appointmentStatus', $data['appointmentStatus'], PDO::PARAM_STR);
            $stmt->bindValue(':serviceID', $data['ServiceId'], PDO::PARAM_INT);
            $stmt->execute();    
            return ['success' => true, 'message' => 'Cita creada correctamente'];
        } catch (\Throwable $th) {
            error_log("Error creando cita: " . $th->getMessage());
            return ['success' => false, 'message' => 'Error creando cita'];
        }
    }

    public function getAllAppointments(?int $members_document = null): array
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'SELECT 
                        sa.scheduledAppointmentID,
                        sa.address,
                        sa.notes,
                        sa.isHomeService,
                        sa.MembersDocument,
                        m1.name AS memberName,
                        m1.lastName AS memberLastName,
                        sa.professionalDocument,
                        m2.name AS professionalName,
                        m2.lastName AS professionalLastName,
                        sa.schedule_scheduleId,
                        sa.date,
                        sch.startTime,
                        sch.endTime,
                        sa.appointmentStatus,
                        ast.description AS appointmentStatusDescription,
                        sa.serviceID,
                        s.serviceName,
                        s.serviceDescription,
                        sa.appointmentByServicesPackagesID
                    FROM scheduledAppointments sa
                    INNER JOIN members m1 ON sa.MembersDocument = m1.document
                    INNER JOIN members m2 ON sa.professionalDocument = m2.document
                    INNER JOIN schedule sch ON sa.schedule_scheduleId = sch.scheduleId
                    INNER JOIN appointmentStatus ast ON sa.appointmentStatus = ast.status
                    INNER JOIN services s ON sa.serviceID = s.serviceID
                    WHERE sa.appointmentStatus = :nueva';
            if ($members_document !== null) {
                $sql .= ' AND sa.MembersDocument = :members_document';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nueva', 'nueva', PDO::PARAM_STR);
            if ($members_document !== null) {
                $stmt->bindValue(':members_document', $members_document, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener citas: " . $e->getMessage());
            return [];
        }
    }

    public function cancelAppointmentById(int $appointmentId): bool
    {
        try {
            $pdo = $this->dataBase->GetConnection();
            $sql = 'UPDATE scheduledAppointments SET appointmentStatus = :cancelled WHERE scheduledAppointmentID = :appointmentId';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cancelled', 'cancelada', PDO::PARAM_STR);
            $stmt->bindValue(':appointmentId', $appointmentId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al cancelar cita: " . $e->getMessage());
            return false;
        }
    }
}
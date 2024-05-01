<?php

namespace AppRepository\Repositories;

use App\DataBase;
use PDO;
class ServiciosRepository
{
    public function __construct(private DataBase $dataBase)
    {
    }
    public function getAllServices(): array|bool
    {
        $pdo = $this->dataBase->GetConnection();
        $stmt = $pdo->query('select Nombre,Descripcion,Costo,Duracion from servicios;');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
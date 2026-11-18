<?php
namespace app\models\mappers;

use app\models\entities\Rental;

class RentalsMappers
{
    public static function to_entity(array $row_db): Rental
    {
        return new Rental(
            (int) $row_db['id'],
            (int) $row_db['vehiculo_id'],
            (int) $row_db['cliente_id'],
            $row_db['fecha_inicio'],
            $row_db['fecha_fin'],
            $row_db['fecha_devolucion'] ?? '', // Si es NULL en la BD, lo pasa como texto vacío
            $row_db['estado'],
            $row_db['vehiculo'] ?? '',         // El texto armado por el CONCAT del JOIN
            $row_db['cliente'] ?? ''           // El nombre del cliente traído por el JOIN
        );
    }
}
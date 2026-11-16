<?php

namespace app\models\mappers;

use app\models\entities\Rental;

class RentalsMappers
{
    public static function to_entity(array $row_db): Rental
    {
        return new Rental(
            $row_db['id'],
            $row_db['vehiculo_id'],
            $row_db['cliente_id'],
            $row_db['fecha_inicio'],
            $row_db['fecha_fin'],
            $row_db['fecha_devolucion'] ?? '',    // en la BD es NULL hasta que se devuelve
            $row_db['estado'],
            $row_db['vehiculo'] ?? '',    
            $row_db['cliente'] ?? ''
        );
    }
}

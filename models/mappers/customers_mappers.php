<?php
namespace app\models\mappers;
use  app\models\entities\Customer;

class CustomersMappers
{
    public static function to_entity(array $row_db): Customer
    {
        return new Customer(
            (int) $row_db['id'],
            $row_db['nombre'],
            $row_db['telefono'] ?? '',
            $row_db['correo'] ?? '',
            $row_db['numero_licencia'] ?? ''
        );
    }
}

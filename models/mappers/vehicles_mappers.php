<?php
namespace app\models\mappers;
use app\models\entities\Vehicle;
class VehiclesMappers
{
    public static function to_entity(array $row_db): Vehicle
    {
        return new Vehicle(
            $row_db['id'], 
            $row_db['marca'],      
            $row_db['modelo'],     
            $row_db['anio'],       
            $row_db['categoria'], 
            $row_db['estado']  
        );
    }
}
<?php
namespace app\models\config;

use app\models\database\RentalDB;
use app\models\database\RentalDBInterface;
use app\models\repositories\VehiclesRepository;
use app\models\repositories\VehiclesRepositoryInterface;

class ModelsConfig
{
    private static ?RentalDBInterface $rental_db = null;

    public static function get_rental_db(): RentalDBInterface
    {
        if (self::$rental_db === null) {
            self::$rental_db = new RentalDB();
        }
        return self::$rental_db;
    }

    public static function get_vehicles_repository(): VehiclesRepositoryInterface
    {
        return new VehiclesRepository(self::get_rental_db());
    }
}
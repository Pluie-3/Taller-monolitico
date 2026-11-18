<?php
namespace app\models\config;

use app\models\database\RentalDB;
use app\models\database\RentalDBInterface;
use app\models\repositories\CustomersRepository;
use app\models\repositories\CustomersRepositoryInterface;
use app\models\repositories\RentalsRepository;
use app\models\repositories\RentalsRepositoryInterface;
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

    public static function get_customers_repository(): CustomersRepositoryInterface
    {
        return new CustomersRepository(self::get_rental_db());
    }

    public static function get_rentals_repository(): RentalsRepositoryInterface
    {
        return new RentalsRepository(self::get_rental_db());
    }
}
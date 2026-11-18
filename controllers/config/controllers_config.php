<?php
namespace app\controllers\config;

use app\controllers\customers\CustomersController;
use app\controllers\customers\CustomersControllerInterface;
use app\controllers\rentals\RentalsController;
use app\controllers\rentals\RentalsControllerInterface;
use app\controllers\vehicles\VehiclesController;
use app\controllers\vehicles\VehiclesControllerInterface;
use app\models\config\ModelsConfig;

class ControllersConfig
{
    public static function get_vehicles_controller(): VehiclesControllerInterface
    {
        return new VehiclesController(ModelsConfig::get_vehicles_repository());
    }

    public static function get_customers_controller(): CustomersControllerInterface
    {
        return new CustomersController(
            ModelsConfig::get_customers_repository(),
            ModelsConfig::get_rentals_repository()
        );
    }

    public static function get_rentals_controller(): RentalsControllerInterface
    {
        return new RentalsController(
            ModelsConfig::get_rentals_repository(),
            ModelsConfig::get_vehicles_repository(),
            ModelsConfig::get_customers_repository()
        );
    }
}
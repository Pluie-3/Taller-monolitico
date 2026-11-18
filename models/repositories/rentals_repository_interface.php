<?php
namespace app\models\repositories;

use app\models\entities\Rental;

interface RentalsRepositoryInterface
{
    public function get_rentals_all(): array;
    public function get_rental_by_id(int $id): ?Rental;
    public function get_history_by_vehicle(int $vehicle_id): array;
    public function get_history_by_customer(int $customer_id): array;
    public function has_overlap(int $vehicle_id, string $start_date, string $end_date): bool;
    public function count_by_status(string $status): int;
    public function save_new_rental(Rental $rental): bool;
    public function cancel_rental(int $rental_id): bool;
    public function start_rental(int $rental_id, int $vehicle_id): bool;
    public function return_rental(int $rental_id, int $vehicle_id, string $return_date): bool;
}
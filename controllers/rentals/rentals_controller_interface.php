<?php
namespace app\controllers\rentals;

use app\models\entities\Rental;

interface RentalsControllerInterface
{
    public function get_rentals(): array;
    public function get_rental_by_id(int $id): ?Rental;
    public function get_history_by_vehicle(int $vehicle_id): array;
    public function get_history_by_customer(int $customer_id): array;
    public function count_by_status(string $status): int;
    public function get_active_count(): int;
    public function rental_create(array $post): array;
    public function rental_start(array $post): array;
    public function rental_return(array $post): array;
    public function rental_cancel(array $post): array;
}
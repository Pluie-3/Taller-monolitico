<?php
namespace app\models\repositories;
use app\models\entities\Vehicle;
interface VehiclesRepositoryInterface
{
 public function get_vehicles_all(): array;
 public function get_vehicle_by_id(int $id): ?Vehicle;
 public function get_available_on(string $date): array;
 public function save_new_vehicle(Vehicle $vehicle): bool;
 public function update_vehicle(Vehicle $vehicle): bool;
 public function has_rentals(int $id): bool;
 public function delete_vehicle(int $id): bool;
}
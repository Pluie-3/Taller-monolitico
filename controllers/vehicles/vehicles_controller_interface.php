<?php
namespace app\controllers\vehicles;
use app\models\entities\Vehicle;

interface VehiclesControllerInterface
{
    public function get_vehicles(): array;
    public function get_vehicle_by_id(int $id): ?Vehicle;
    public function get_available_vehicles(string $date): array;
    public function get_summary_counts(): array;
    public function vehicle_create(array $post): array;
    public function vehicle_update(array $post): array;
    public function vehicle_delete(array $post): array;
}
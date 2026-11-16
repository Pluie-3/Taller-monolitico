<?php
namespace app\models\entities {
    class Vehicle
    {
        public function __construct(
            private int $id,
            private string $brand,
            private string $model,
            private int $year,
            private string $category,
            private string $status
        ) {}
        public function get_id(): int { return $this->id; }
        public function get_brand(): string { return $this->brand; }
        public function get_model(): string { return $this->model; }
        public function get_year(): int { return $this->year; }
        public function get_category(): string { return $this->category; }
        public function get_status(): string { return $this->status; }
    }

    class Customer
    {
        public function __construct(
            private int $id,
            private string $name,
            private string $phone,
            private string $email,
            private string $license_number
        ) {}
        public function get_id(): int { return $this->id; }
        public function get_name(): string { return $this->name; }
        public function get_phone(): string { return $this->phone; }
        public function get_email(): string { return $this->email; }
        public function get_license_number(): string { return $this->license_number; }
    }

    class Rental
    {
        public function __construct(
            private int $id,
            private int $vehicle_id,
            private int $customer_id,
            private string $start_date,
            private string $end_date,
            private string $status,
            private string $vehicle_label,
            private string $customer_name
        ) {}
        public function get_id(): int { return $this->id; }
        public function get_vehicle_id(): int { return $this->vehicle_id; }
        public function get_customer_id(): int { return $this->customer_id; }
        public function get_start_date(): string { return $this->start_date; }
        public function get_end_date(): string { return $this->end_date; }
        public function get_status(): string { return $this->status; }
        public function get_vehicle_label(): string { return $this->vehicle_label; }
        public function get_customer_name(): string { return $this->customer_name; }
    }
}

namespace app\controllers\config {
    use app\models\entities\Vehicle;
    use app\models\entities\Customer;
    use app\models\entities\Rental;

    class MockVehiclesController
    {
        private array $items;
        public function __construct()
        {
            $this->items = [
                new Vehicle(1, 'Tesla', 'Model S', 2025, 'Lujo', 'disponible'),
                new Vehicle(2, 'Toyota', 'Fortuner GR', 2024, 'SUV', 'alquilado'),
                new Vehicle(3, 'Porsche', 'Taycan', 2025, 'Lujo', 'disponible'),
                new Vehicle(4, 'Mazda', 'CX-50', 2023, 'Camioneta', 'mantenimiento'),
            ];
        }
        public function get_vehicles(): array { return $this->items; }
        public function get_vehicle_by_id(int $id): ?Vehicle
        {
            foreach ($this->items as $v) {
                if ($v->get_id() === $id) return $v;
            }
            return null;
        }
        public function get_available_vehicles(string $date): array
        {
            return array_values(array_filter($this->items, fn($v) => $v->get_status() === 'disponible'));
        }
        public function get_summary_counts(): array
        {
            return ['disponible' => 2, 'alquilado' => 1, 'mantenimiento' => 1];
        }
        public function vehicle_create(array $d): array { return ['success' => true, 'message' => 'Vista previa: vehiculo guardado']; }
        public function vehicle_update(array $d): array { return ['success' => true, 'message' => 'Vista previa: vehiculo actualizado']; }
        public function vehicle_delete(array $d): array { return ['success' => true, 'message' => 'Vista previa: vehiculo eliminado']; }
    }

    class MockCustomersController
    {
        private array $items;
        public function __construct()
        {
            $this->items = [
                new Customer(1, 'Laura Gomez', '3001112233', 'laura@correo.com', 'LIC-1001'),
                new Customer(2, 'Carlos Ruiz', '3104445566', 'carlos@correo.com', 'LIC-2045'),
            ];
        }
        public function get_customers(): array { return $this->items; }
        public function get_customer_by_id(int $id): ?Customer
        {
            foreach ($this->items as $c) {
                if ($c->get_id() === $id) return $c;
            }
            return null;
        }
        public function customer_create(array $d): array { return ['success' => true, 'message' => 'Vista previa: cliente guardado']; }
        public function customer_update(array $d): array { return ['success' => true, 'message' => 'Vista previa: cliente actualizado']; }
        public function customer_delete(array $d): array { return ['success' => true, 'message' => 'Vista previa: cliente eliminado']; }
    }

    class MockRentalsController
    {
        private array $items;
        public function __construct()
        {
            $hoy = date('Y-m-d');
            $fin = date('Y-m-d', strtotime('+3 days'));
            $this->items = [
                new Rental(1, 2, 1, $hoy, $fin, 'activa', 'Toyota Fortuner GR (2024)', 'Laura Gomez'),
                new Rental(2, 1, 2, '2026-10-10', '2026-10-14', 'completada', 'Tesla Model S (2025)', 'Carlos Ruiz'),
            ];
        }
        public function get_rentals(): array { return $this->items; }
        public function get_active_count(): int { return 1; }
        public function get_history_by_vehicle(int $id): array
        {
            return array_values(array_filter($this->items, fn($r) => $r->get_vehicle_id() === $id));
        }
        public function get_history_by_customer(int $id): array
        {
            return array_values(array_filter($this->items, fn($r) => $r->get_customer_id() === $id));
        }
        public function rental_create(array $d): array { return ['success' => true, 'message' => 'Vista previa: reserva registrada']; }
        public function rental_deliver(array $d): array { return ['success' => true, 'message' => 'Vista previa: entrega registrada']; }
        public function rental_return(array $d): array { return ['success' => true, 'message' => 'Vista previa: devolucion registrada']; }
        public function rental_cancel(array $d): array { return ['success' => true, 'message' => 'Vista previa: reserva cancelada']; }
    }

    class ControllersConfig
    {
        public static function get_vehicles_controller(): MockVehiclesController { return new MockVehiclesController(); }
        public static function get_customers_controller(): MockCustomersController { return new MockCustomersController(); }
        public static function get_rentals_controller(): MockRentalsController { return new MockRentalsController(); }
    }
}

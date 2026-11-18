<?php
namespace app\controllers\rentals;

use app\models\entities\Rental;
use app\models\repositories\CustomersRepositoryInterface;
use app\models\repositories\RentalsRepositoryInterface;
use app\models\repositories\VehiclesRepositoryInterface;
use DateTimeImmutable;

class RentalsController implements RentalsControllerInterface
{
    private RentalsRepositoryInterface $rentals_model;
    private VehiclesRepositoryInterface $vehicles_model;
    private CustomersRepositoryInterface $customers_model;

    public function __construct(
        RentalsRepositoryInterface $rentals_model,
        VehiclesRepositoryInterface $vehicles_model,
        CustomersRepositoryInterface $customers_model
    ) {
        $this->rentals_model = $rentals_model;
        $this->vehicles_model = $vehicles_model;
        $this->customers_model = $customers_model;
    }

    public function get_rentals(): array
    {
        return $this->rentals_model->get_rentals_all();
    }

    public function get_rental_by_id(int $id): ?Rental
    {
        return $this->rentals_model->get_rental_by_id($id);
    }

    public function get_history_by_vehicle(int $vehicle_id): array
    {
        return $this->rentals_model->get_history_by_vehicle($vehicle_id);
    }

    public function get_history_by_customer(int $customer_id): array
    {
        return $this->rentals_model->get_history_by_customer($customer_id);
    }
public function count_by_status(string $status): int
    {
        return $this->rentals_model->count_by_status($status);
    }

    public function get_active_count(): int
    {
        return $this->rentals_model->count_by_status('activa');
    }

    public function rental_create(array $post): array

    {
        $vehicle_id = (int) ($post['vehicle_id'] ?? 0);
        $customer_id = (int) ($post['customer_id'] ?? 0);
        $start_date = trim($post['start_date'] ?? '');
        $end_date = trim($post['end_date'] ?? '');

        if ($vehicle_id <= 0 || $customer_id <= 0) {
            return ['success' => false, 'message' => 'Debe seleccionar un vehiculo y un cliente'];
        }
        if (!$this->is_valid_date($start_date) || !$this->is_valid_date($end_date)) {
            return ['success' => false, 'message' => 'Las fechas ingresadas no son validas'];
        }
        if ($start_date < date('Y-m-d')) {
            return ['success' => false, 'message' => 'La fecha de inicio no puede ser anterior a hoy'];
        }
        if ($end_date < $start_date) {
            return ['success' => false, 'message' => 'La fecha de fin no puede ser anterior a la fecha de inicio'];
        }

        $customer = $this->customers_model->get_customer_by_id($customer_id);
        if ($customer === null) {
            return ['success' => false, 'message' => 'El cliente seleccionado no existe'];
        }

        $vehicle = $this->vehicles_model->get_vehicle_by_id($vehicle_id);
        if ($vehicle === null) {
            return ['success' => false, 'message' => 'El vehiculo seleccionado no existe'];
        }
        if ($vehicle->get_status() === 'mantenimiento') {
            return ['success' => false, 'message' => 'El vehiculo esta en mantenimiento y no se puede reservar'];
        }

        if ($this->rentals_model->has_overlap($vehicle_id, $start_date, $end_date)) {
            return ['success' => false, 'message' => 'El vehiculo ya tiene una reserva o alquiler activo en esas fechas'];
        }

        $rental = new Rental(0, $vehicle_id, $customer_id, $start_date, $end_date, '', 'activa');
        $ok = $this->rentals_model->save_new_rental($rental);

        return [
            'success' => $ok,
            'message' => $ok ? 'Reserva creada correctamente' : 'No se pudo registrar la reserva'
        ];
    }

    public function rental_start(array $post): array
    {
        $id = (int) ($post['id'] ?? 0);
        if ($id <= 0) {
            return ['success' => false, 'message' => 'Reserva no valida'];
        }

        $rental = $this->rentals_model->get_rental_by_id($id);
        if ($rental === null) {
            return ['success' => false, 'message' => 'La reserva no existe'];
        }
        if ($rental->get_status() !== 'activa') {
            return ['success' => false, 'message' => 'Solo se pueden entregar reservas en estado activa'];
        }

        $vehicle = $this->vehicles_model->get_vehicle_by_id($rental->get_vehicle_id());
        if ($vehicle === null || $vehicle->get_status() !== 'disponible') {
            return ['success' => false, 'message' => 'El vehiculo no esta disponible para ser entregado'];
        }

        $ok = $this->rentals_model->start_rental($id, $rental->get_vehicle_id());
        return [
            'success' => $ok,
            'message' => $ok ? 'Vehiculo entregado al cliente' : 'No se pudo registrar la entrega del vehiculo'
        ];
    }

    public function rental_return(array $post): array
    {
        $id = (int) ($post['id'] ?? 0);
        $return_date = trim($post['return_date'] ?? date('Y-m-d'));

        if ($id <= 0) {
            return ['success' => false, 'message' => 'Reserva no valida'];
        }
        if (!$this->is_valid_date($return_date)) {
            $return_date = date('Y-m-d');
        }

        $rental = $this->rentals_model->get_rental_by_id($id);
        if ($rental === null) {
            return ['success' => false, 'message' => 'La reserva no existe'];
        }
        if ($rental->get_status() !== 'activa') {
            return ['success' => false, 'message' => 'La reserva no esta activa'];
        }

        $vehicle = $this->vehicles_model->get_vehicle_by_id($rental->get_vehicle_id());
        if ($vehicle === null || $vehicle->get_status() !== 'alquilado') {
            return ['success' => false, 'message' => 'El vehiculo no figura como alquilado'];
        }

        $ok = $this->rentals_model->return_rental($id, $rental->get_vehicle_id(), $return_date);
        return [
            'success' => $ok,
            'message' => $ok ? 'Devolucion registrada y vehiculo disponible nuevamente' : 'No se pudo registrar la devolucion'
        ];
    }

    public function rental_cancel(array $post): array
    {
        $id = (int) ($post['id'] ?? 0);
        if ($id <= 0) {
            return ['success' => false, 'message' => 'Reserva no valida'];
        }

        $rental = $this->rentals_model->get_rental_by_id($id);
        if ($rental === null) {
            return ['success' => false, 'message' => 'La reserva no existe'];
        }
        if ($rental->get_status() !== 'activa') {
            return ['success' => false, 'message' => 'Solo se pueden cancelar reservas activas'];
        }

        $vehicle = $this->vehicles_model->get_vehicle_by_id($rental->get_vehicle_id());
        if ($vehicle !== null && $vehicle->get_status() === 'alquilado') {
            return ['success' => false, 'message' => 'El vehiculo ya fue entregado; debe registrar la devolucion en lugar de cancelar'];
        }

        $ok = $this->rentals_model->cancel_rental($id);
        return [
            'success' => $ok,
            'message' => $ok ? 'Reserva cancelada' : 'No se pudo cancelar la reserva'
        ];
    }

    private function is_valid_date(string $date): bool
    {
        $dt = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
    }
}

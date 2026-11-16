<?php

namespace app\models\repositories;

use app\models\database\RentalDBInterface;
use app\models\entities\Rental;
use app\models\mappers\RentalsMappers;
use RuntimeException;
use Throwable;

class RentalsRepository implements RentalsRepositoryInterface
{
    private const SELECT_WITH_NAMES = "select a.*,
        concat(v.marca, ' ', v.modelo) as vehiculo,
        c.nombre as cliente
    from alquileres a
    join vehiculos v on v.id = a.vehiculo_id
    join clientes c on c.id = a.cliente_id";

    private RentalDBInterface $rental_db;

    public function __construct(RentalDBInterface $rental_db)
    {
        $this->rental_db = $rental_db;
    }

    public function get_rentals_all(): array
    {
        $sql = self::SELECT_WITH_NAMES . ' order by a.fecha_inicio desc, a.id desc';
        return $this->to_entities($this->rental_db->exc_select($sql));
    }

    public function get_rental_by_id(int $id): ?Rental
    {
        $sql = self::SELECT_WITH_NAMES . ' where a.id = ?';
        $rows_db = $this->rental_db->exc_select($sql, 'i', [$id]);
        if (count($rows_db) === 0) {
            return null;
        }
        return RentalsMappers::to_entity($rows_db[0]);
    }

    public function get_history_by_vehicle(int $vehicle_id): array
    {
        $sql = self::SELECT_WITH_NAMES . ' where a.vehiculo_id = ? order by a.fecha_inicio desc';
        return $this->to_entities($this->rental_db->exc_select($sql, 'i', [$vehicle_id]));
    }

    public function get_history_by_customer(int $customer_id): array
    {
        $sql = self::SELECT_WITH_NAMES . ' where a.cliente_id = ? order by a.fecha_inicio desc';
        return $this->to_entities($this->rental_db->exc_select($sql, 'i', [$customer_id]));
    }


    public function has_overlap(int $vehicle_id, string $start_date, string $end_date): bool
    {
        $sql = "select count(*) as total from alquileres
                where vehiculo_id = ?
                and estado in ('reservado', 'en_curso')
                and fecha_inicio <= ?
                and fecha_fin >= ?";
        $rows_db = $this->rental_db->exc_select($sql, 'iss', [$vehicle_id, $end_date, $start_date]);
        return (int) $rows_db[0]['total'] > 0;
    }

    public function count_by_status(string $status): int
    {
        $sql = 'select count(*) as total from alquileres where estado = ?';
        $rows_db = $this->rental_db->exc_select($sql, 's', [$status]);
        return (int) $rows_db[0]['total'];
    }

    // Toda reserva nueva nace en estado 'reservado'
    public function save_new_rental(Rental $rental): bool
    {
        $sql = "insert into alquileres(vehiculo_id, cliente_id, fecha_inicio, fecha_fin, estado)
                values (?, ?, ?, ?, 'reservado')";
        return $this->rental_db->exc_query($sql, 'iiss', [
            $rental->get_vehicle_id(),
            $rental->get_customer_id(),
            $rental->get_start_date(),
            $rental->get_end_date()
        ]);
    }

    // Solo se puede cancelar una reserva que siga en estado 'reservado'
    public function cancel_rental(int $rental_id): bool
    {
        $sql = 'update alquileres set estado = ? where id = ? and estado = ?';
        $ok = $this->rental_db->exc_query($sql, 'sis', ['cancelado', $rental_id, 'reservado']);
        return $ok && $this->rental_db->affected_rows() === 1;
    }

    // ENTREGAR:
    public function start_rental(int $rental_id, int $vehicle_id): bool
    {
        $this->rental_db->begin_transaction();
        try {
            $ok1 = $this->rental_db->exc_query(
                'update alquileres set estado = ? where id = ? and estado = ?',
                'sis',
                ['en_curso', $rental_id, 'reservado']
            );
            if (!$ok1 || $this->rental_db->affected_rows() !== 1) {
                throw new RuntimeException('El alquiler ya no esta en estado reservado');
            }
            $ok2 = $this->rental_db->exc_query(
                'update vehiculos set estado = ? where id = ? and estado = ?',
                'sis',
                ['alquilado', $vehicle_id, 'disponible']
            );
            if (!$ok2 || $this->rental_db->affected_rows() !== 1) {
                throw new RuntimeException('El vehiculo ya no esta disponible');
            }
            $this->rental_db->commit();
            return true;
        } catch (Throwable $e) {
            $this->rental_db->rollback();
            return false;
        }
    }

    // DEVOLVER: 
    public function return_rental(int $rental_id, int $vehicle_id, string $return_date): bool
    {
        $this->rental_db->begin_transaction();
        try {
            $ok1 = $this->rental_db->exc_query(
                'update alquileres set estado = ?, fecha_devolucion = ? where id = ? and estado = ?',
                'ssis',
                ['devuelto', $return_date, $rental_id, 'en_curso']
            );
            if (!$ok1 || $this->rental_db->affected_rows() !== 1) {
                throw new RuntimeException('El alquiler ya no esta en curso');
            }
            $ok2 = $this->rental_db->exc_query(
                'update vehiculos set estado = ? where id = ?',
                'si',
                ['disponible', $vehicle_id]
            );
            if (!$ok2) {
                throw new RuntimeException('No se pudo actualizar el vehiculo');
            }
            $this->rental_db->commit();
            return true;
        } catch (Throwable $e) {
            $this->rental_db->rollback();
            return false;
        }
    }

    // Convierte un array de filas en un array de entidades Rental
    private function to_entities(array $rows_db): array
    {
        $rentals = [];
        foreach ($rows_db as $row) {
            $rentals[] = RentalsMappers::to_entity($row);
        }
        return $rentals;
    }
}

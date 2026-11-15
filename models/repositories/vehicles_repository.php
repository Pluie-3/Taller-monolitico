<?php
namespace app\models\repositories;
use app\models\database\RentalDBInterface;
use app\models\entities\Vehicle;
use app\models\mappers\VehiclesMappers;
class VehiclesRepository implements VehiclesRepositoryInterface
{
 private RentalDBInterface $rental_db;
 public function __construct(RentalDBInterface $rental_db) // inyeccion de dependencias
 {
 $this->rental_db = $rental_db;
 }
 public function get_vehicles_all(): array
 {
 $rows = $this->rental_db->exc_select('select * from vehiculos order by marca, modelo');
 $vehicles = [];
 foreach ($rows as $row) { $vehicles[] = VehiclesMappers::to_entity($row); }
 return $vehicles;
 }
 public function get_vehicle_by_id(int $id): ?Vehicle
 {
 $rows = $this->rental_db->exc_select('select * from vehiculos where id = ?', 'i', [$id]);
 return count($rows) === 0 ? null : VehiclesMappers::to_entity($rows[0]);
 }
 // Vehiculos libres en una fecha (consulta explicada en el capitulo 6.3)
 public function get_available_on(string $date): array
 {
 $sql = "select * from vehiculos v
 where v.estado <> 'mantenimiento'
 and not exists (
 select 1 from alquileres a
 where a.vehiculo_id = v.id
 and a.estado in ('reservado','en_curso')
 and ? between a.fecha_inicio and a.fecha_fin)
 order by v.marca, v.modelo";
 $rows = $this->rental_db->exc_select($sql, 's', [$date]);
 $vehicles = [];
 foreach ($rows as $row) { $vehicles[] = VehiclesMappers::to_entity($row); }
 return $vehicles;
 }
 public function save_new_vehicle(Vehicle $vehicle): bool
 {
 $sql = 'insert into vehiculos(placa, marca, modelo, anio, categoria, estado) values
 (?,?,?,?,?,?)';
 return $this->rental_db->exc_query($sql, 'sssiss', [
 $vehicle->get_year(), $vehicle->get_category(), $vehicle->get_status()
 ]);
 }
 public function update_vehicle(Vehicle $vehicle): bool
 {
 $sql = 'update vehiculos set placa=?, marca=?, modelo=?, anio=?, categoria=?, estado=? where
 id=?';
 return $this->rental_db->exc_query($sql, 'sssissi', [
 $vehicle->get_year(), $vehicle->get_category(), $vehicle->get_status(), $vehicle->get_id()
 ]);
 }
 public function delete_vehicle(int $id): bool
 {
 return $this->rental_db->exc_query('delete from vehiculos where id = ?', 'i', [$id]);
 }
}

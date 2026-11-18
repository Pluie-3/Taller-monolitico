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
 // Vehiculos libres en una fecha
 public function get_available_on(string $date): array
 {
  $sql = "select * from vehiculos v
  where v.estado <> 'mantenimiento'
  and not exists (
   select 1 from reservas r
   where r.vehiculo_id = v.id
   and r.estado = 'activa'
   and ? between r.fecha_inicio and r.fecha_fin
  )
  order by v.marca, v.modelo";
  $rows = $this->rental_db->exc_select($sql, 's', [$date]);
  $vehicles = [];
  foreach ($rows as $row) { $vehicles[] = VehiclesMappers::to_entity($row); }
  return $vehicles;
 }
 public function save_new_vehicle(Vehicle $vehicle): bool
 {
  $sql = 'insert into vehiculos(marca, modelo, anio, categoria, estado) values (?,?,?,?,?)';
  return $this->rental_db->exc_query($sql, 'ssiss', [
   $vehicle->get_brand(),
   $vehicle->get_model(),
   $vehicle->get_year(),
   $vehicle->get_category(),
   $vehicle->get_status()
  ]);
 }
 public function update_vehicle(Vehicle $vehicle): bool
 {
  $sql = 'update vehiculos set marca=?, modelo=?, anio=?, categoria=?, estado=? where id=?';
  return $this->rental_db->exc_query($sql, 'ssissi', [
   $vehicle->get_brand(),
   $vehicle->get_model(),
   $vehicle->get_year(),
   $vehicle->get_category(),
   $vehicle->get_status(),
   $vehicle->get_id()
  ]);
 }
 public function has_rentals(int $id): bool
 {
  $rows = $this->rental_db->exc_select('select count(*) as total from reservas where vehiculo_id = ?', 'i', [$id]);
  return (int) $rows[0]['total'] > 0;
 }
 public function delete_vehicle(int $id): bool
 {
  return $this->rental_db->exc_query('delete from vehiculos where id = ?', 'i', [$id]);
 }
 }
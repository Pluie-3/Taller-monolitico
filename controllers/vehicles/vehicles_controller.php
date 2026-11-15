<?php
// controllers/vehicles/vehicles_controller.php
namespace app\controllers\vehicles;
use app\models\entities\Vehicle;
use app\models\repositories\VehiclesRepositoryInterface;
class VehiclesController implements VehiclesControllerInterface
{
 private const STATUSES = ['disponible', 'alquilado', 'mantenimiento'];
 private const CATEGORIES = ['Economico', 'SUV', 'Camioneta', 'Lujo'];
 private VehiclesRepositoryInterface $vehicles_model;
 public function __construct(VehiclesRepositoryInterface $vehicles_model)
 {
 $this->vehicles_model = $vehicles_model; }
 public function get_vehicles(): array { return $this->vehicles_model->get_vehicles_all(); }
 public function get_vehicle_by_id(int $id): ?Vehicle { return
 $this->vehicles_model->get_vehicle_by_id($id); }
 public function get_available_vehicles(string $date): array
 {
 return $this->vehicles_model->get_available_on($date);
 }
 public function vehicle_create(array $post): array
 {
 $error = $this->validate($post, 0);
 if ($error !== null) { return ['success' => false, 'message' => $error]; }
$vehicle = new Vehicle(0, trim($post['brand']), trim($post['model']), (int) $post['year'], $post['category'], $post['status']);
 $ok = $this->vehicles_model->save_new_vehicle($vehicle);
 return ['success' => $ok, 'message' => $ok ? 'Vehiculo registrado' : 'No se pudo guardar el
 vehiculo'];
 }
 public function vehicle_update(array $post): array
 {
 $id = (int) ($post['id'] ?? 0);
 $error = $this->validate($post, $id);
 if ($id <= 0 || $error !== null) { return ['success' => false, 'message' => $error ?? 'Vehiculo
 no valido']; }
 // Regla: no se puede pasar a mantenimiento un vehiculo que esta alquilado (completen esta regla)
 $vehicle = new Vehicle($id, trim($post['brand']), trim($post['model']),
 (int) $post['year'], $post['category'], $post['status']);
 $ok = $this->vehicles_model->update_vehicle($vehicle);
 return ['success' => $ok, 'message' => $ok ? 'Vehiculo actualizado' : 'No se pudo actualizar'];
 }
 public function vehicle_delete(array $post): array
 {
 $id = (int) ($post['id'] ?? 0);
 $ok = $id > 0 && $this->vehicles_model->delete_vehicle($id);
 return ['success' => $ok, 'message' => $ok ? 'Vehiculo eliminado'
 : 'No se puede eliminar: el vehiculo tiene alquileres en su historial'];
 }
 // Devuelve el mensaje de error, o null si todo esta bien
 private function validate(array $post, int $except_id): ?string
 {
 $brand = trim($post['brand'] ?? '');
 $model = trim($post['model'] ?? '');
 $year = (int) ($post['year'] ?? 0);
 if ($brand === '' || $model === '') { return 'Placa, marca y modelo son
 obligatorios'; }
 if ($year < 1990 || $year > (int) date('Y') + 1) { return 'El anio no es valido'; }
 if (!in_array($post['category'] ?? '', self::CATEGORIES, true)) { return 'Categoria no valida';
 }
 if (!in_array($post['status'] ?? '', self::STATUSES, true)) { return 'Estado no valido'; }
 return null;
 }
}
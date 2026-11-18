<?php
namespace app\controllers\customers;

use app\models\entities\Customer;
use app\models\repositories\CustomersRepositoryInterface;
use app\models\repositories\RentalsRepositoryInterface;

class CustomersController implements CustomersControllerInterface
{
    private CustomersRepositoryInterface $customers_model;
    private RentalsRepositoryInterface $rentals_model;

    public function __construct(
        CustomersRepositoryInterface $customers_model,
        RentalsRepositoryInterface $rentals_model
    ) {
        $this->customers_model = $customers_model;
        $this->rentals_model = $rentals_model;
    }

    public function get_customers(): array
    {
        return $this->customers_model->get_customers_all();
    }

    public function get_customer_by_id(int $id): ?Customer
    {
        return $this->customers_model->get_customer_by_id($id);
    }

    public function customer_create(array $post): array
    {
        $error = $this->validate($post, 0);
        if ($error !== null) {
            return ['success' => false, 'message' => $error];
        }

        $customer = new Customer(
            0,
            trim($post['name']),
            trim($post['phone'] ?? ''),
            trim($post['email'] ?? ''),
            trim($post['license_number'])
        );

        $ok = $this->customers_model->save_new_customer($customer);
        return [
            'success' => $ok,
            'message' => $ok ? 'Cliente registrado correctamente' : 'No se pudo guardar el cliente'
        ];
    }

    public function customer_update(array $post): array
    {
        $id = (int) ($post['id'] ?? 0);
        $error = $this->validate($post, $id);
        if ($id <= 0 || $error !== null) {
            return ['success' => false, 'message' => $error ?? 'Cliente no valido'];
        }

        if ($this->customers_model->get_customer_by_id($id) === null) {
            return ['success' => false, 'message' => 'El cliente no existe'];
        }

        $customer = new Customer(
            $id,
            trim($post['name']),
            trim($post['phone'] ?? ''),
            trim($post['email'] ?? ''),
            trim($post['license_number'])
        );

        $ok = $this->customers_model->update_customer($customer);
        return [
            'success' => $ok,
            'message' => $ok ? 'Cliente actualizado correctamente' : 'No se pudo actualizar el cliente'
        ];
    }

    public function customer_delete(array $post): array
    {
        $id = (int) ($post['id'] ?? 0);
        if ($id <= 0) {
            return ['success' => false, 'message' => 'Cliente no valido'];
        }

        if (count($this->rentals_model->get_history_by_customer($id)) > 0) {
            return [
                'success' => false,
                'message' => 'No se puede eliminar: el cliente tiene reservas en su historial'
            ];
        }

        $ok = $this->customers_model->delete_customer($id);
        return [
            'success' => $ok,
            'message' => $ok ? 'Cliente eliminado' : 'No se pudo eliminar el cliente'
        ];
    }

    private function validate(array $post, int $except_id): ?string
    {
        $name = trim($post['name'] ?? '');
        $phone = trim($post['phone'] ?? '');
        $email = trim($post['email'] ?? '');
        $license = trim($post['license_number'] ?? '');

        if ($name === '') {
            return 'El nombre del cliente es obligatorio';
        }
        if (mb_strlen($name) > 150) {
            return 'El nombre no puede superar los 150 caracteres';
        }
        if ($license === '') {
            return 'El numero de licencia es obligatorio';
        }
        if (mb_strlen($license) > 50) {
            return 'El numero de licencia no puede superar los 50 caracteres';
        }
        if ($this->customers_model->license_exists($license, $except_id)) {
            return 'Ya existe un cliente registrado con ese numero de licencia';
        }
        if ($phone !== '' && mb_strlen($phone) > 50) {
            return 'El telefono no puede superar los 50 caracteres';
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'El correo electronico no es valido';
        }

        return null;
    }
}
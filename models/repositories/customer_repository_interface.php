<?php
namespace app\models\repositories;
use app\models\entities\Customer;
interface CustomersRepositoryInterface
{
    public function get_customers_all(): array;
    public function get_customer_by_id(int $id): ?Customer;
    public function license_exists(string $license_number,int $except_id=0): bool;
    public function save_new_customer(Customer $customer): bool;
    public function update_customer(Customer $customer): bool;
    public function delete_customer(int $id): bool;

}

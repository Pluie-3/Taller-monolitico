<?php
namespace app\controllers\customers;

use app\models\entities\Customer;

interface CustomersControllerInterface
{
    public function get_customers(): array;
    public function get_customer_by_id(int $id): ?Customer;
    public function customer_create(array $post): array;
    public function customer_update(array $post): array;
    public function customer_delete(array $post): array;
}
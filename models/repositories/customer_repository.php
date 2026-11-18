<?php

namespace app\models\repositories;

use app\models\database\RentalDBInterface;
use app\models\entities\Customer;
use app\models\mappers\CustomersMappers;

class CustomersRepository implements CustomersRepositoryInterface
{
    private RentalDBInterface $rental_db;

    public function __construct(RentalDBInterface $rental_db)
    {
        $this->rental_db = $rental_db;
    }

    public function get_customers_all(): array
    {
        $sql = 'select * from clientes order by nombre';
        $rows_db = $this->rental_db->exc_select($sql);
        $customers = [];
        foreach ($rows_db as $row) {
            $customers[] = CustomersMappers::to_entity($row);
        }
        return $customers;
    }

    public function get_customer_by_id(int $id): ?Customer
    {
        $sql = 'select * from clientes where id = ?';
        $rows_db = $this->rental_db->exc_select($sql, 'i', [$id]);
        if (count($rows_db) === 0) {
            return null;
        }
        return CustomersMappers::to_entity($rows_db[0]);
    }

    public function license_exists(string $license_number, int $except_id = 0): bool
    {
        $sql = 'select count(*) as total from clientes where numero_licencia = ? and id <> ?';
        $rows_db = $this->rental_db->exc_select($sql, 'si', [$license_number, $except_id]);
        return (int) $rows_db[0]['total'] > 0;
    }

   public function save_new_customer(Customer $customer): bool
    {
        $sql = 'insert into clientes(nombre, telefono, correo, numero_licencia) values (?, ?, ?, ?)';
        return $this->rental_db->exc_query($sql, 'ssss', [
            $customer->get_name(),
            $customer->get_phone(),
            $customer->get_email(),
            $customer->get_license_number()
        ]);
    }

    public function update_customer(Customer $customer): bool
    {
        $sql = 'update clientes set nombre = ?, telefono = ?, correo = ?, numero_licencia = ? where id = ?';
        return $this->rental_db->exc_query($sql, 'ssssi', [
            $customer->get_name(),
            $customer->get_phone(),
            $customer->get_email(),
            $customer->get_license_number(),
            $customer->get_id()
        ]);
    }
    
    public function delete_customer(int $id): bool
    {
        return $this->rental_db->exc_query('delete from clientes where id = ?', 'i', [$id]);
    }
}

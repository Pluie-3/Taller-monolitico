<?php

namespace app\models\entities;

class Rental
{
    private int $id;
    private int $vehicle_id;
    private int $customer_id;
    private string $start_date;
    private string $end_date;
    private string $return_date;   
    private string $status;          // 'reservado' | 'en_curso' | 'devuelto' | 'cancelado'
    private string $vehicle_label;   // solo para mostrar 
    private string $customer_name;   // solo para mostrar
    
    public function __construct(
        int $id,
        int $vehicle_id,
        int $customer_id,
        string $start_date,
        string $end_date,
        string $return_date,
        string $status,
        string $vehicle_label = '',
        string $customer_name = ''
    ) {
        $this->id = $id;
        $this->vehicle_id = $vehicle_id;
        $this->customer_id = $customer_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->return_date = $return_date;
        $this->status = $status;
        $this->vehicle_label = $vehicle_label;
        $this->customer_name = $customer_name;
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_vehicle_id(): int
    {
        return $this->vehicle_id;
    }

    public function get_customer_id(): int
    {
        return $this->customer_id;
    }

    public function get_start_date(): string
    {
        return $this->start_date;
    }

    public function get_end_date(): string
    {
        return $this->end_date;
    }

    public function get_return_date(): string
    {
        return $this->return_date;
    }

    public function get_status(): string
    {
        return $this->status;
    }

    public function get_vehicle_label(): string
    {
        return $this->vehicle_label;
    }

    public function get_customer_name(): string
    {
        return $this->customer_name;
    }
}

<?php
namespace app\models\database;
use mysqli;
use mysqli_sql_exception;

class RentalDB implements RentalDBInterface
{
    private string $host_db = 'localhost';
    private string $user_db = 'root';
    private string $pwd_db = '1234';
    private string $name_db = 'alquiler_vehiculos_db';
    private int $port_db = 3306;
    private mysqli $conex;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->conex = new mysqli($this->host_db, $this->user_db, $this->pwd_db, $this->name_db, $this->port_db);
        $this->conex->set_charset('utf8mb4');
    }

    public function __destruct()
    {
        $this->conex->close();
    }

    public function exc_select(string $sql, string $types = '', array $params = []): array
    {
        $stmt = $this->conex->prepare($sql);
        if ($types !== '') { $stmt->bind_param($types, ...$params); }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) { $rows[] = $row; }
        $stmt->close();
        return $rows;
    }

    public function exc_query(string $sql, string $types = '', array $params = []): bool
    {
        try {
            $stmt = $this->conex->prepare($sql);
            if ($types !== '') { $stmt->bind_param($types, ...$params); }
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    public function last_insert_id(): int { return $this->conex->insert_id; }
    public function begin_transaction(): void { $this->conex->begin_transaction(); }
    public function commit(): void { $this->conex->commit(); }
    public function rollback(): void { $this->conex->rollback(); }
}
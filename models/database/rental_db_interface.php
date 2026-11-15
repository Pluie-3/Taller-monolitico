<?php
namespace app\models\database;

interface RentalDBInterface
{
    public function exc_select(string $sql, string $types = '', array $params = []): array;
    public function exc_query(string $sql, string $types = '', array $params = []): bool;
    public function last_insert_id(): int;
    public function begin_transaction(): void;
    public function commit(): void;
    public function rollback(): void;
}

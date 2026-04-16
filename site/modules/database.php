<?php

class Database
{
    private PDO $pdo;

    public function __construct($path)
    {
        // Подключаемся к SQLite-файлу и включаем исключения при ошибках SQL.
        $this->pdo = new PDO("sqlite:" . $path);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function Execute($sql)
    {
        return $this->pdo->exec($sql);
    }

    public function Fetch($sql)
    {
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Create($table, $data)
    {
        // Формируем INSERT динамически на основе переданных полей.
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function Read($table, $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function Update($table, $id, $data)
    {
        // Преобразуем массив в "column = :column" для подготовленного UPDATE.
        $set = implode(", ", array_map(fn($k) => "$k = :$k", array_keys($data)));

        $sql = "UPDATE $table SET $set WHERE id = :id";
        $data['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function Delete($table, $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function Count($table)
    {
        // Быстрый подсчет строк таблицы.
        $stmt = $this->pdo->query("SELECT COUNT(*) as cnt FROM $table");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['cnt'];
    }
}
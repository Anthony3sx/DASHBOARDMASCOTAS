<?php
declare(strict_types=1);

class Conexion
{
    protected function conectar(): PDO
    {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            return new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log('Error de conexión: ' . $e->getMessage());
            throw new RuntimeException('No fue posible conectar con la base de datos. Verifica que MySQL esté iniciado y que hayas importado el archivo SQL.');
        }
    }
}


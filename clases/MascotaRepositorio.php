<?php
declare(strict_types=1);

class MascotaRepositorio extends Conexion
{
    public function guardar(Mascota $mascota): int
    {
        try {
            $sql = 'INSERT INTO Mascotas
                (nombre, especie, raza, edad, peso_actual, color_senas, responsable, telefono_emergencia,
                 dni_propietario, observaciones)
                VALUES (:nombre, :especie, :raza, :edad, :peso, :color, :responsable, :telefono,
                        :dni, :observaciones)';
            $conexion = $this->conectar();
            $consulta = $conexion->prepare($sql);
            $consulta->execute([
                ':nombre' => $mascota->getNombre(),
                ':especie' => $mascota->getEspecie(),
                ':raza' => $mascota->getRaza(),
                ':edad' => $mascota->getEdad(),
                ':peso' => $mascota->getPesoActual(),
                ':color' => $mascota->getColorSenas(),
                ':responsable' => $mascota->getResponsable(),
                ':telefono' => $mascota->getTelefonoEmergencia(),
                ':dni' => $mascota->getDniPropietario(),
                ':observaciones' => $mascota->getObservaciones(),
            ]);
            return (int) $conexion->lastInsertId();
        } catch (PDOException $e) {
            error_log('Error al guardar mascota: ' . $e->getMessage());
            throw new RuntimeException('No se pudo guardar la mascota. Inténtalo nuevamente.');
        }
    }

    public function listar(): array
    {
        try {
            $sql = 'SELECT id, nombre, especie, raza, edad, peso_actual, color_senas,
                    responsable, telefono_emergencia, dni_propietario, observaciones, creado_en
                    FROM Mascotas ORDER BY id DESC';
            return $this->conectar()->query($sql)->fetchAll();
        } catch (PDOException $e) {
            error_log('Error al listar mascotas: ' . $e->getMessage());
            throw new RuntimeException('No se pudieron cargar los registros.');
        }
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->conectar()->prepare('SELECT * FROM Mascotas WHERE id = :id');
        $consulta->execute([':id' => $id]);
        return $consulta->fetch() ?: null;
    }
}

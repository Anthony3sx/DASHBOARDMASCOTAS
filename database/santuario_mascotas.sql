CREATE DATABASE IF NOT EXISTS santuario_mascotas
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE santuario_mascotas;

CREATE TABLE IF NOT EXISTS Mascotas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  especie VARCHAR(60) NOT NULL,
  raza VARCHAR(100) NOT NULL,
  edad TINYINT UNSIGNED NOT NULL,
  peso_actual DECIMAL(7,2) UNSIGNED NOT NULL,
  color_senas VARCHAR(100) NOT NULL,
  responsable VARCHAR(100) NOT NULL,
  telefono_emergencia VARCHAR(20) NOT NULL,
  dni_propietario VARCHAR(15) NOT NULL,
  observaciones VARCHAR(500) NOT NULL DEFAULT '',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT chk_peso_positivo CHECK (peso_actual > 0)
) ENGINE=InnoDB;

USE santuario_mascotas;

ALTER TABLE Mascotas
  ADD COLUMN dni_propietario VARCHAR(15) NOT NULL DEFAULT '' AFTER telefono_emergencia,
  ADD COLUMN observaciones VARCHAR(500) NOT NULL DEFAULT '' AFTER dni_propietario;

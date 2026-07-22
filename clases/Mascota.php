<?php
declare(strict_types=1);

class Mascota
{
    protected string $nombre;
    protected string $especie;
    protected string $raza;
    protected int $edad;
    protected float $pesoActual;
    protected string $colorSenas;
    protected string $responsable;
    protected string $telefonoEmergencia;
    protected string $dniPropietario;
    protected string $observaciones;

    public function __construct(
        string $nombre,
        string $especie,
        string $raza,
        int $edad,
        float $pesoActual,
        string $colorSenas,
        string $responsable,
        string $telefonoEmergencia,
        string $dniPropietario,
        string $observaciones
    ) {
        $this->setNombre($nombre);
        $this->setEspecie($especie);
        $this->setRaza($raza);
        $this->setEdad($edad);
        $this->setPesoActual($pesoActual);
        $this->setColorSenas($colorSenas);
        $this->setResponsable($responsable);
        $this->setTelefonoEmergencia($telefonoEmergencia);
        $this->setDniPropietario($dniPropietario);
        $this->setObservaciones($observaciones);
    }

    public function getNombre(): string { return $this->nombre; }
    public function getEspecie(): string { return $this->especie; }
    public function getRaza(): string { return $this->raza; }
    public function getEdad(): int { return $this->edad; }
    public function getPesoActual(): float { return $this->pesoActual; }
    public function getColorSenas(): string { return $this->colorSenas; }
    public function getResponsable(): string { return $this->responsable; }
    public function getTelefonoEmergencia(): string { return $this->telefonoEmergencia; }
    public function getDniPropietario(): string { return $this->dniPropietario; }
    public function getObservaciones(): string { return $this->observaciones; }

    public function setNombre(string $valor): void { $this->nombre = $this->validarTexto($valor, 'nombre'); }
    public function setEspecie(string $valor): void { $this->especie = $this->validarTexto($valor, 'especie'); }
    public function setRaza(string $valor): void { $this->raza = $this->validarTexto($valor, 'raza'); }
    public function setColorSenas(string $valor): void { $this->colorSenas = $this->validarTexto($valor, 'color o señas físicas'); }
    public function setResponsable(string $valor): void { $this->responsable = $this->validarTexto($valor, 'responsable'); }

    public function setDniPropietario(string $valor): void
    {
        if (!preg_match('/^[0-9-]{13,15}$/', $valor)) {
            throw new InvalidArgumentException('Ingresa un DNI válido de 13 dígitos.');
        }
        $this->dniPropietario = $valor;
    }

    public function setObservaciones(string $valor): void
    {
        if (mb_strlen($valor) > 500) {
            throw new InvalidArgumentException('Las alergias u observaciones no pueden superar 500 caracteres.');
        }
        $this->observaciones = $valor;
    }

    public function setEdad(int $valor): void
    {
        if ($valor < 0 || $valor > 100) {
            throw new InvalidArgumentException('La edad debe estar entre 0 y 100 años.');
        }
        $this->edad = $valor;
    }

    public function setPesoActual(float $valor): void
    {
        if (!is_finite($valor) || $valor <= 0) {
            throw new InvalidArgumentException('El peso debe ser numérico y mayor que cero.');
        }
        $this->pesoActual = $valor;
    }

    public function setTelefonoEmergencia(string $valor): void
    {
        if (!preg_match('/^[0-9+() -]{8,20}$/', $valor)) {
            throw new InvalidArgumentException('Ingresa un teléfono de emergencia válido.');
        }
        $this->telefonoEmergencia = $valor;
    }

    private function validarTexto(string $valor, string $campo): string
    {
        if ($valor === '') {
            throw new InvalidArgumentException('El campo ' . $campo . ' es obligatorio.');
        }
        if (mb_strlen($valor) > 100) {
            throw new InvalidArgumentException('El campo ' . $campo . ' es demasiado largo.');
        }
        return $valor;
    }
}

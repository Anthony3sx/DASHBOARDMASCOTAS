<?php
declare(strict_types=1);

function limpiarEntrada(string $dato): string
{
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = strip_tags($dato);
    return preg_replace('/\s+/', ' ', $dato) ?? $dato;
}

function escapar(string $dato): string
{
    return htmlspecialchars($dato, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}


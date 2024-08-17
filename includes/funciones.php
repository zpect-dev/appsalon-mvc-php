<?php

function debugear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

// Funcion que revisa que el usuario este autenticado
function isAuth(): bool
{
    if (!isset($_SESSION['login'])) {
        header("Location: /");
        return false;
    }
    return true;
}

function isAdmin(): void
{
    if (!isset($_SESSION['admin'])) {
        if (isAuth()) {
            header("Location: /cita");
        } else {
            header("Location: /");
        }
    }
}

function esUltimo(string $actual, string $siguiente): bool
{
    if ($actual !== $siguiente) {
        return true;
    }
    return false;
}

<h1 class="nombre-pagina">Panel de Administracion</h1>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario" method="POST">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha ?>">
        </div>
    </form>
</div>

<?php
if (count($citas) === 0) {
    echo "<h2>No hay citas para esta fecha</h2>";
}
?>

<div class="citas-admin">
    <ul class="citas">
        <?php $citaActual = 0; ?>
        <?php foreach ($citas as $key => $cita) : ?>
            <?php if ($citaActual !== $cita->id) : ?>
                <?php $precioTotal = 0; ?>
                <li>
                    <p>ID: <span><?php echo $cita->id; ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                    <p>Email: <span><?php echo $cita->email; ?></span></p>
                    <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>
                    <h3>Servicios</h3>
                    <?php $citaActual = $cita->id; ?>
                <?php endif; ?>
                <p class="servicios"><?php echo $cita->servicio . ' ' . '$' . $cita->precio; ?></p>

                <?php
                $actual = $cita->id;
                $siguiente = $citas[$key + 1]->id ?? '';
                $precioTotal += $cita->precio;
                ?>

                <?php if (esUltimo($actual, $siguiente)) : ?>
                    <p class="total">Total: <span>$<?php echo $precioTotal; ?></span></p>
                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
                <?php endif; ?>
            <?php endforeach; ?>
    </ul>
</div>

<?php

$script = "<script src='build/js/buscador.js'></script>";

?>
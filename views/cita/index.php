<h1 class="nombre-pagina">Crear nueva cita</h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<div id="app">
    <nav class="tabs">
        <button type="button" data-paso="1" class="actual">Servicios</button>
        <button type="button" data-paso="2">Informacion Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="texto-centrado">Elige tus servicios a continuacion</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    <div id="paso-2" class="seccion">
        <h2>Tus datos y cita</h2>
        <p class="texto-centrado">Coloca tus datos y fecha de tu cita</p>
        <div></div>
        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre" value="<?php echo $nombre; ?>" disabled>
            </div>

            <div class="campo">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>

            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" id="hora">
            </div>
            <input type="hidden" id="id" value="<?php echo $id; ?>">
        </form>
    </div>

    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="texto-centrado">Verifica que la informacion sea correcta</p>
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">&laquo; Anterior</button>
        <button id="siguiente" class="boton">Siguiente &raquo;</button>
    </div>

    <?php
    $script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='build/js/app.js'></script>
";
    ?>
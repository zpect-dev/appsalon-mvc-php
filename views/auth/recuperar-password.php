<h1 class="nombre-pagina">Restablecer Password</h1>
<p class="descripcion-pagina">Escribe tu nueva password</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<?php if (!$error) : ?>
    <form class="formulario" method="POST">
        <div class="campo">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Tu Password">
        </div>

        <input type="submit" value="Restablecer Password" class="boton">
    </form>
<?php endif; ?>
<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Iniciar sesion</a>
    <a href="/crear-cuenta">¿Aun no tienes cuenta? Crear una</a>
</div>
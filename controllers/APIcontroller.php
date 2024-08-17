<?php

namespace Controllers;

use Model\cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar()
    {
        // Almacena la cita y devuelve el ID
        $cita = new cita($_POST);
        $resultado = $cita->guardar();
        $idCita = $resultado['id'];

        // Almacena la cita y el servicio
        $idServicios = explode(',', $_POST['servicios']);

        // Almacena los servicios con el id de la cita
        foreach ($idServicios as $idServicio) {
            $args = [
                'cita_id' => $idCita,
                'servicio_id' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        // Retornamos una respuesta

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header("Location:" . $_SERVER['HTTP_REFERER']);
        }
    }
}

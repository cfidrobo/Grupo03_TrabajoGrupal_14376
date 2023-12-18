<?php
class recetas
{
    private $IdReceta;
    private $IdConsulta;
    private $IdMedicamento;
    private $Cantidad;
    private $con;

    function __construct($cn)
    {
        $this->con = $cn;
    }

    //******** 3.1 METODO update_consulta() *****************
    public function update_receta()
    {
        $this->IdReceta = $_POST['id'];
        $this->IdConsulta = $_POST['consulta'];
        $this->IdMedicamento = $_POST['medicamento'];
        $this->Cantidad = $_POST['cantidad'];

        $sql = "UPDATE recetas SET 
                IdConsulta='$this->IdConsulta',
                IdMedicamento='$this->IdMedicamento',
                Cantidad='$this->Cantidad'
                WHERE IdReceta = $this->IdReceta;";

        echo $sql;

        if ($this->con->query($sql)) {
            echo $this->_message_ok("modificó");
        } else {
            echo $this->_message_error("al modificar");
        }
    }



    //******** 3.2 METODO save_consulta() *****************	
    public function save_receta()
    {
        $this->IdReceta = $_POST['id'];
        $this->IdConsulta = $_POST['consulta'];
        $this->IdMedicamento = $_POST['medicamento'];
        $this->Cantidad = $_POST['cantidad'];

        $sql = "INSERT INTO recetas VALUES(
                    NULL, 
                    '$this->IdConsulta',
                    '$this->IdMedicamento',
                    '$this->Cantidad'
                );";

        if ($this->con->query($sql)) {
            echo $this->_message_ok("guardó");
        } else {
            echo $this->_message_error("guardar");
        }
    }

    
    //************* PARTE I ********************
    //Aquí se agregó el parámetro:  $defecto/
    private function _get_combo_db($tabla, $valor, $etiqueta, $nombre, $defecto)
    {
        $html = '<select name="' . $nombre . '">';
        $sql = "SELECT $valor, $etiqueta FROM $tabla;";
        $res = $this->con->query($sql);

        while ($row = $res->fetch_assoc()) {
            $html .= ($defecto == $row[$valor]) ? '<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
        }

        $html .= '</select>';
        return $html;
    }
    
    //************* PARTE II ******************	

    public function get_form($id = NULL)
{
    if ($id == NULL) {
        $this->IdConsulta = NULL;
        $this->IdMedicamento = NULL;
        $this->Cantidad = NULL;

        $flag = NULL;  // VARIABLES AUXILIARES
        $op = "new";
    } else {
        $sql = "SELECT * FROM recetas WHERE IdReceta=$id;";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();

        $num = $res->num_rows;
        if ($num == 0) {
            $mensaje = "tratar de actualizar la consulta con id= " . $id;
            echo $this->_message_error($mensaje);
        } else {
            // ** TUPLA ENCONTRADA **
            $this->IdConsulta = $row['IdConsulta'];
            $this->IdMedicamento = $row['IdMedicamento'];
            $this->Cantidad = $row['Cantidad'];

            $flag = "enabled";
            $op = "update";
        }
    }

    $html = '
    <form name="Form_recetas" method="POST" action="receta.php" enctype="multipart/form-data">
        <!-- Agrego dos líneas -> hidden oculto -->
        <input type="hidden" name="id" value="' . $id . '">
        <input type="hidden" name="op" value="' . $op . '">
        <div class="container ">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <a href="receta.php" class="btn btn-primary">Regresar</a>
                    <table class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th colspan="2">DATOS CONSULTA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label for="IdConsulta" class="col-md-4 col-form-label">Consulta:</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" size="15" name="consulta" value="' . $this->IdConsulta . '">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="IdMedicamento" class="col-md-4 col-form-label">Medicamento:</label>
                                </td>
                                <td>
                                    ' . $this->_get_combo_db("medicamentos", "IdMedicamento", "Nombre", "medicamento", $this->IdMedicamento) . '
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="Cantidad" class="col-md-4 col-form-label">Cantidad:</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" size="15" name="cantidad" value="' . $this->Cantidad . '">
                                </td>
                            </tr>

                            <tr>
                                <th colspan="2"><input type="submit" class="btn btn-primary" name="Guardar" value="GUARDAR"></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>';
    return $html;
}


    public function get_list()
    {
        $d_new = "new/0";
        $d_new_final = base64_encode($d_new);
        $html = '
        <div class="container">
        <form action="../login/recursos/index2.php" method="get">
        
            <th colspan="2"><input type="submit" class="btn btn-primary" name="" value="REGRESAR"></th>
        </form>  
      
            <table class="table table-bordered text-center table-striped" align="center">
                <thead >
                    <tr class= "active">
                        <th colspan="8">Lista de Recetas</th>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <th>Paciente</th>
                        <th>Medicamento</th>
                        <th>Cantidad</th>
                        <th colspan="5">Acciones</th>
                    </tr>
                </thead>
            <tbody>
        </div>';
        $sql = "SELECT
        r.IdReceta,
        p.Nombre AS Paciente,
        m.Nombre AS Medicamento,
        r.Cantidad
        FROM
            recetas r
        INNER JOIN medicamentos m ON m.IdMedicamento = r.IdMedicamento
        INNER JOIN consultas c ON c.IdConsulta = r.IdConsulta
        LEFT JOIN pacientes p ON c.IdPaciente = p.IdPaciente;";
        $res = $this->con->query($sql);
        while ($row = $res->fetch_assoc()) {
            $d_del = "del/" . $row['IdReceta'];
            $d_del_final = base64_encode($d_del);
            $d_act = "act/" . $row['IdReceta'];
            $d_act_final = base64_encode($d_act);
            $d_det = "det/" . $row['IdReceta'];
            $d_det_final = base64_encode($d_det);
            $html .= '
            <tr>
                <td>' . $row['Paciente'] . '</td>
                <td>' . $row['Medicamento'] . '</td>
                <td>' . $row['Cantidad'] . '</td>
                <td class="text-center"><button class="btn btn-info"><a href="receta.php?d=' . $d_det_final . '">Detalle</a></button></td>
            </tr>';
        }
        $html .= '</table>';
        return $html;
    }

    public function get_detail_receta($id)
    {

        $sql = "SELECT
        p.Nombre AS nombrepaciente,
        m.Nombre AS Medicamento,
        r.Cantidad
        FROM
            recetas r
        INNER JOIN medicamentos m ON m.IdMedicamento = r.IdMedicamento
        INNER JOIN (
            SELECT
                c.IdConsulta AS consulta,
                p.Nombre
            FROM
                consultas c
            LEFT JOIN pacientes p ON c.IdPaciente = p.IdPaciente
        ) p ON p.consulta = r.IdConsulta
        WHERE
            r.IdReceta = $id;";

        // $sql = "SELECT IdConsulta AS nombrepaciente, IdMedicamento, Cantidad
        //     FROM recetas
        //     WHERE IdReceta = $id;";

        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();

        $num = $res->num_rows;


        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el consulta con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas

        if ($num == 0) {
            $mensaje = "tratar de editar el consulta con id= " . $id;
            echo $this->_message_error($mensaje);
        } else {
            $html = '<div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            DATOS DE RECETAS
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <td>Paciente:</td>
                                    <td>' . $row['nombrepaciente'] . '</td>
                                </tr>
                                <tr>
                                    <td>Medicamento:</td>
                                    <td>' . $row['Medicamento'] . '</td>
                                </tr>
                                <tr>
                                    <td>Cantidad:</td>
                                    <td>' . $row['Cantidad'] . '</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a href="receta.php" class="btn btn-primary">Regresar</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
            return $html;
        }
    }

    public function delete_receta($id)
    {
        $sql = "DELETE FROM recetas WHERE IdReceta=$id;";
        if ($this->con->query($sql)) {
            echo $this->_message_ok("ELIMINÓ");
        } else {
            echo $this->_message_error("eliminar");
        }
    }

    //*************************	

    private function _message_error($tipo)
    {
        $html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="receta.php">Regresar</a></th>
			</tr>
		</table>';
        return $html;
    }


    private function _message_ok($tipo)
    {
        $html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="receta.php">Regresar</a></th>
			</tr>
		</table>';
        return $html;
    }

    //**************************	

} // FIN SCRPIT
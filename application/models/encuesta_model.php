<?php


class encuesta_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getEncuestas()
    {
        
        $query = $this->db->where('estado', 'act')->get('Encuestas');

        if ($query->num_rows()> 0) {
            return $query->result_array();
        }
        return 0;
        
    }

    public function getEncuestaID($id)
    {
        $query = $this->db->where('estado', 'act')->where('IdEncuesta', $id)->get('encuestas');

        if ($query->num_rows()>0) {
            return $query->result_array();
        }
        return 0;
    }

    public function getAreas()
    {
        $query = $this->db->where('estado', 'act')->get('CatAreas');
        //echo json_encode($query->result_array());
        if ($query->num_rows()> 0) {
            return $query->result_array();
        }
        return 0;
    }

    public function getPreguntaAResolver($id)
    {
        $query = $this->db->query("SELECT t0.IdPregunta,t0.IdEncuesta,t0.Descripcion,t0.IdTipoPregunta
                            from EncuestasPreguntas t0                            
                            where t0.IdEncuesta = ".$id." and t0.Estado = 'act'");
        //echo json_encode($query->result_array());
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return 0;

    }
    public function getRespuestasAResolver($id)
    {
        $query = $this->db->query("SELECT IdValorPregunta,Descripcion DescRespuesta,IdTipoPregunta,valor 
                                FROM CatValorPregunta 
                                WHERE IdTipoPregunta IN (SELECT IdTipoPregunta FROM EncuestasPreguntas WHERE IdEncuesta = ".$id.")
                                AND estado = 'act';");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return 0;
    }

    public function guardarEncuesta($enc,$det)
    {
        //echo json_encode($det);
        try {
            
        
        $this->db->trans_begin();

        date_default_timezone_set("America/Managua");
        $mensaje = array();

        $bandera=false;
        $det = json_decode($det, true);
        $id = $this->db->query("SELECT ISNULL(MAX(IdUsuario),0)+1 AS ID FROM UsuarioPregunta");
        foreach ($det as $obj) {
            $UsuarioRespuestas = array(
              "IdUsuario" => $id->result_array()[0]["ID"],//$this->session->userdata("id"),
              "IdPregunta" => $obj[0],
              "Resultado" => $obj[1], 
              "Respuesta" => '',
              "IdArea" => $enc[1],
              "IdValorPregunta" => $obj[1],
              "Fecha" => date('Y-m-d H:m:s')
            );


            $inserto = $this->db->insert("UsuarioPregunta",$UsuarioRespuestas);
            $bandera=false;
            if($inserto){
                $bandera = true;
            }
        }
                if($bandera == true){
                    $mensaje[0]["mensaje"] = "Datos guardados correctamente";
                    $mensaje[0]["tipo"] = "success";
                    $this->db->trans_commit();
                    echo json_encode($mensaje);
                    return;
                }else{
                    $mensaje[0]["mensaje"] = "Error al guardar los datos de la encuesta cod -1";
                    $mensaje[0]["tipo"] = "error";
                    $this->db->trans_rollback();
                    echo json_encode($mensaje);
                    return;
                }


        } catch (Exception $e) {
            $mensaje[0]["mensaje"] = $e->getMessage();
            $mensaje[0]["tipo"] = "error";
            $this->db->trans_rollback();
            echo json_encode($mensaje);
            return;
        }
    }

    public function getTiposPreguntas()
    {
        $query = $this->db->query("SELECT *from CatTipoPregunta
                                WHERE estado = 'act';");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return 0;
    }


    public function guardarEncuestaNueva($enc,$datos)
    {
        $this->db->trans_begin();
        date_default_timezone_set("America/Managua");
        $mensaje = array();
        $IdArea = null;
        try{
            if ($enc[2] != '-1') {
                $IdArea = $enc[2];
            }
            $encabezado = array(
              "Titulo" => $enc[0],
              "Descripcion" => $enc[1],
              "TipoTest" => '',
              "Estado" => 'act',
              "Fecha" => gmdate(date("Y-m-d H:i:s")),
              "IdUsuario" => $this->session->userdata("id"),
              "IdArea" => $IdArea
            );

            $inserto = $this->db->insert("Encuestas",$encabezado);

            if ($inserto) {
                $bandera = false;
                $det = json_decode($datos, true);

                foreach ($det as $obj) {                    
                    $idencuesta = $this->db->query("SELECT ISNULL(MAX(IdEncuesta),0) AS IDENCUESTA FROM Encuestas");

                    $rpt = array(                        
                        "IdEncuesta" => $idencuesta->result_array()[0]["IDENCUESTA"],
                        "Descripcion" => $obj[0],
                        "Descripcion2" => $obj[1],
                        "IdTipoPregunta" => $obj[2],
                        "Fecha" => gmdate(date("Y-m-d H:i:s")),
                        "Estado" => 'act'
                    );
                    
                    $guardarRpt = $this->db->insert("EncuestasPreguntas",$rpt);
                    if($guardarRpt){
                        $bandera = true;
                    }
                }
            }
            if($bandera == true){
                    $mensaje[0]["mensaje"] = "Datos guardados correctamente";
                    $mensaje[0]["tipo"] = "success";
                    $this->db->trans_commit();
                    echo json_encode($mensaje);
                    return;
                }else{
                    $mensaje[0]["mensaje"] = "Error al guardar los datos de la encuesta cod (det)";
                    $mensaje[0]["tipo"] = "error";
                    $this->db->trans_rollback();
                    echo json_encode($mensaje);                 
                    return;
                }


        }catch(Exception $ex){
            $mensaje[0]["mensaje"] = $ex->getMessage();
            $mensaje[0]["tipo"] = "error";
            echo json_encode($mensaje);
            $this->db->trans_rollback();
            return;
        }
    }
}
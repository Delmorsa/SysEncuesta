<?php


class Informes_Encuestas_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

  public function getEncuestas(){
      $encuestas = $this->db->get("Encuestas");
      if($encuestas->num_rows() > 0){
        return $encuestas->result_array();
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

	public function cantUsersEncuesta($idPregunta,$idArea){
		$json = array(); $area = "";
		if($idArea != "-1"){
			$area = "and IdArea = '".$idArea."' ";
		}
		$query = $this->db->query("SELECT COUNT(DISTINCT IdUsuario) as Encuestados FROM UsuarioPregunta
															WHERE IdPregunta = '".$idPregunta."' ".$area." ");
		if($query->num_rows() > 0){
				$json[0]["usuarios"] = $query->result_array()[0]["Encuestados"];
		}else{
			$json[0]["usuarios"] = 0;
		}
		echo json_encode($json);
	}

  public function resultadosEncuesta($idPregunta,$idArea){
    $tipo = array(); $tipo1 = array();
		$area = ""; $group_by_area = "";
		if($idArea != "-1"){
			$area = "and IdArea = '".$idArea."' ";
			$group_by_area = "dbo.UsuarioPregunta.IdArea,";
		}
    //Mostrar encabezado de respuesta
    $tipo_query = $this->db->query("select Descripcion from CatValorPregunta
     where IdTipoPregunta in (select IdTipoPregunta from EncuestasPreguntas where IdPregunta = ".$idPregunta.")");

     foreach ($tipo_query->result_array() as $key) {
       $tipo[] = "[".$key["Descripcion"]."]"; //pivot
       $tipo1[] = $key["Descripcion"];
     }

     //traer cantidad de cada respuesta
     $json = array();
     $query = $this->db->query("select ".implode(",",$tipo)." from
      (
      	SELECT
      		dbo.CatValorPregunta.IdTipoPregunta,
      		dbo.CatValorPregunta.Descripcion as Respuetas,
      		dbo.UsuarioPregunta.IdPregunta,
					".$group_by_area."
      		Count(dbo.UsuarioPregunta.IdValorPregunta) as Cant_Respuestas
      	FROM
      		dbo.UsuarioPregunta
      	RIGHT JOIN dbo.CatValorPregunta ON dbo.UsuarioPregunta.IdValorPregunta = dbo.CatValorPregunta.IdValorPregunta

      	GROUP BY
      		dbo.CatValorPregunta.IdTipoPregunta,
      		dbo.UsuarioPregunta.IdValorPregunta,
      		dbo.UsuarioPregunta.IdPregunta,
					".$group_by_area."
      		dbo.CatValorPregunta.Descripcion
      ) as tabla
      pivot(
      	sum(Cant_Respuestas)
      	for Respuetas in (".implode(",",$tipo).")
      ) as p where IdPregunta = ".$idPregunta." ".$area." ");

      foreach ($query->result_array() as $item) {
        for($i = 0; $i<count($item);$i++){
          if($item["".$tipo1[$i].""] == null){
            $json[$i][$tipo1[$i]] = 0;
          }else{
            $json[$i][$tipo1[$i]] = $item["".$tipo1[$i].""];
          }
        }
      }

      echo json_encode($json);
  }

  //Cargar preguntas segun la encuesta
  public function getPregPorEnc($idEncuesta){
      $preguntas = $this->db->where("IdEncuesta",$idEncuesta)->get("EncuestasPreguntas");
      $i = 0; $json = array();
      if($preguntas->num_rows() > 0){
        foreach ($preguntas->result_array() as $key) {
          $json[$i]["id"] = $key["IdPregunta"];
          $json[$i]["nombre"] = $key["Descripcion"];
          $json[$i]["tipo"] = $key["IdTipoPregunta"];
          $i++;
        }
      }else{
        $json[0]["nombre"] ="No hay datos disponibles";
      }
      echo json_encode($json);
  }


	//Mostrar cantidad de resp por cada area
	public function respPorAreas($idPregunta){
		$json = array(); $i = 0; //$area = "";
		/*if($idArea != "-1"){
			$area = "and dbo.CatAreas.IdArea = '".$idArea."' ";
		}*/
		$query = $this->db->query(
			"SELECT
				dbo.CatAreas.IdArea,
				dbo.CatAreas.Descripcion,
				dbo.UsuarioPregunta.IdPregunta,
				Count(dbo.UsuarioPregunta.IdArea) as Cant_Respuestas
			FROM
				dbo.UsuarioPregunta
			RIGHT JOIN dbo.CatAreas ON dbo.UsuarioPregunta.IdArea= dbo.CatAreas.IdArea
			WHERE dbo.UsuarioPregunta.IdPregunta = '".$idPregunta." '
			GROUP BY
				dbo.CatAreas.IdArea,
				dbo.CatAreas.Descripcion,
				dbo.UsuarioPregunta.IdPregunta");

				if($query->num_rows() > 0){
						foreach ($query->result_array() as $key) {
							$json[$i]["Area"] = $key["Descripcion"];
							$json[$i]["Respuestas"] = $key["Cant_Respuestas"];
							$i++;
						}
						echo json_encode($json);
				}
	 }

	public function detalleEncuestasAreas($idPregunta){
		 $tipo = array(); $tipo1 = array();
		 //Mostrar encabezado de respuesta
		 $tipo_query = $this->db->query("select Descripcion from CatValorPregunta
			where IdTipoPregunta in (select IdTipoPregunta from EncuestasPreguntas where IdPregunta = ".$idPregunta.")");

			foreach ($tipo_query->result_array() as $key) {
				$tipo[] = "[".$key["Descripcion"]."]"; //pivot
				$tipo1[] = $key["Descripcion"];
			}

			//traer cantidad de cada respuesta
			$json = array(); $it = 0;
			$query = $this->db->query("
						select
						dbo.CatAreas.Descripcion as Areas,
						".implode(",",$tipo)." from
					(select * from
						(
							SELECT
								dbo.CatValorPregunta.IdTipoPregunta,
								dbo.CatValorPregunta.Descripcion as Respuetas,
								dbo.UsuarioPregunta.IdPregunta,
								dbo.UsuarioPregunta.IdArea,
								Count(dbo.UsuarioPregunta.IdValorPregunta) as Cant_Respuestas
							FROM
								dbo.UsuarioPregunta
							RIGHT JOIN dbo.CatValorPregunta ON dbo.UsuarioPregunta.IdValorPregunta = dbo.CatValorPregunta.IdValorPregunta

							GROUP BY
								dbo.CatValorPregunta.IdTipoPregunta,
								dbo.UsuarioPregunta.IdValorPregunta,
								dbo.UsuarioPregunta.IdPregunta,
								dbo.UsuarioPregunta.IdArea,
								dbo.CatValorPregunta.Descripcion
							) as tabla
					pivot(
								sum(Cant_Respuestas)
								for Respuetas in (".implode(",",$tipo).")
							) as p
				) as tabla1
				RIGHT JOIN dbo.CatAreas ON tabla1.IdArea= dbo.CatAreas.IdArea
				where tabla1.IdPregunta = ".$idPregunta."
				GROUP BY
				dbo.CatAreas.IdArea,
						dbo.CatAreas.Descripcion,
						".implode(",",$tipo)."
				 ");

			/* foreach ($query->result_array() as $item) {
				 $json[$it] = $item;
				 $it++;
			 }*/

			 echo json_encode($query->result_array());
	 }
}

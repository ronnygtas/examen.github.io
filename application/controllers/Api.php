<?php
require(APPPATH.'/libraries/REST_Controller.php');

class Api extends REST_Controller
{
	public function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', 'El campo %s es requerido');
		$this->form_validation->set_message('numeric', 'El campo %s debe ser numerico');
    }

	//Acción POST
	public function alta_post(){
		$id_alumno = $this->post("id_alumno");
		$id_materia = $this->post("id_materia");
		$calificacion = $this->post("calificacion");
		$this->form_validation->set_rules('id_alumno', 'id_alumno', 'required|numeric');
		$this->form_validation->set_rules('id_materia', 'id_materia', 'required|numeric');
		$this->form_validation->set_rules('calificacion', 'calificacion', 'required|numeric');
		
		if ($this->form_validation->run() != false){
			$this->load->model("api_model");
			$alta = $this->api_model->alta_calificacion($this->post("id_alumno"), $this->post("id_materia"), $this->post("calificacion"));
			if ($alta === false){
				$this->response(array("status" => "failed"));
			}else{
				$this->response(json_encode(array("success" => "ok", "msg" => "calificacion registrada")));
			}
		}else{
			$this->response(validation_errors());
		}
	}
	
	//Acción GET
	public function listado_get(){
		$this->load->model("api_model");
		$listado = $this->api_model->listado_calificaciones($this->get("id_alumno"));
		if($listado){
			$this->response($listado, 200);
		}else{
			$this->response(NULL, 400);
		}
	}
	
	//Acción PUT
	public function actualizar_put(){
		$id_alumno = $this->put("id_alumno");
		$id_materia = $this->put("id_materia");
		$calificacion = $this->put("calificacion");
		$datos = array(	'id_alumno' => $id_alumno, 'id_materia' => $id_materia,	'calificacion' => $calificacion);
		$this->form_validation->set_data($datos);
		$this->form_validation->set_rules('id_alumno', 'id_alumno', 'required|numeric');
		$this->form_validation->set_rules('id_materia', 'id_materia', 'required|numeric');
		$this->form_validation->set_rules('calificacion', 'calificacion', 'required|numeric');
		
		if($this->form_validation->run() != false){
			$this->load->model("api_model");
			$actualizacion = $this->api_model->actualizacion($this->put("id_alumno"), $this->put("id_materia"), $this->put("calificacion"));
			if ($actualizacion === false){
				$this->response(array("status" => "failed"));
			}else{
				$this->response(json_encode(array("success" => "ok", "msg" => "calificacion actualizada")));
			}
		}else{
			$this->response(validation_errors());
		}
	}
	
	//Acción DELETE
	public function eliminar_delete(){
		$this->load->model("api_model");
		$eliminar = $this->api_model->eliminar_calificacion($this->delete("id_alumno"), $this->delete("id_materia"));
		if ($eliminar === false){
			$this->response(array("status" => "failed"));
		}else{
			$this->response(json_encode(array("success" => "ok", "msg" => "calificacion eliminada")));
		}
	}
}
?>

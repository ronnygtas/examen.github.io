<?php

class Api_model extends CI_Model
{
 
  //Alta Calificacion
  public function alta_calificacion($id_alumno, $id_materia, $calificacion){
	  $this->db->set("id_t_materias", $id_materia);
	  $this->db->set("id_t_usuarios", $id_alumno);
	  $this->db->set("calificacion", $calificacion);
	  $this->db->set("fecha_registro", date("Y/m/d"));
	  $this->db->insert("t_calificaciones");
  }
  
  //Listado calificaciones
  public function listado_calificaciones($id_alumno){
	  $respuesta = array();
	  $this->db->select("t_alumnos.id_t_usuarios, t_alumnos.nombre, concat(ap_paterno, ' ', ap_materno) AS apellidos, t_materias.nombre AS materia, calificacion, date_format(fecha_registro, '%d/%m/%Y') AS fecha_registro");
	  $this->db->join("t_calificaciones", "t_alumnos.id_t_usuarios = t_calificaciones.id_t_usuarios", "INNER");
	  $this->db->join("t_materias", "t_calificaciones.id_t_materias = t_materias.id_t_materias", "INNER");
	  $this->db->where("t_calificaciones.id_t_usuarios", $id_alumno);
	  $query = $this->db->get("t_alumnos");
	  if ($query->num_rows() > 0){
		  array_push($respuesta, $query->result());
	  }else{
		  return "Alumno No Valido";
	  }
	  $this->db->select("AVG(calificacion) AS promedio");
	  $this->db->where("t_calificaciones.id_t_usuarios", $id_alumno);
	  $query2 = $this->db->get("t_calificaciones");
	  if ($query2->num_rows() > 0){
		  array_push($respuesta, $query2->result());
	  }
	  return json_encode($respuesta);
  }
  
  //Actualizar calificacion
  public function actualizacion($id_alumno, $id_materia, $calificacion){
	  $this->db->set("calificacion", $calificacion);
	  $this->db->where("id_t_usuarios", $id_alumno);
	  $this->db->where("id_t_materias", $id_materia);
	  $this->db->update("t_calificaciones");
  }
  
  //Eliminar calificacion
  public function eliminar_calificacion($id_alumno, $id_materia){
	  $this->db->where("id_t_usuarios", $id_alumno);
	  $this->db->where("id_t_materias", $id_materia);
	  $this->db->delete("t_calificaciones");
  }
}

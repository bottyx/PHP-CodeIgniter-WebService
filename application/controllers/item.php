<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item extends CI_Controller {


	public function getMarcaCars()
	{
		$query = $this->db->get('marcas');
		$count = 0;
		foreach ($query->result() as $row)
		{
			$json[$count]['id'] = $row->id;
			$json[$count]['marca'] =  $row->marca;
			$count++;
		}

		$datos['data'] = $json;
		echo json_encode($datos);
	}

	public function getItems()
	{
		$this->db->order_by("id_pub", "desc");
		$query = $this->db->get('mex_pubs', 20, 0);

		//var_dump($query);
		$count = 0;
		foreach ($query->result() as $row)
		{
			$json[$count]['id_pub'] =  $row->id_pub;
			$json[$count]['v_telefono'] =  $row->v_telefono;
			$json[$count]['v_marcas'] =  $row->v_marcas;
			$json[$count]['v_modelo'] =  $row->v_modelo;
			$json[$count]['v_anos'] =  $row->v_anos;
			$json[$count]['v_cilindros'] =  $row->v_cilindros;
			$json[$count]['v_desc'] =  $row->v_desc;
			$json[$count]['v_precio'] =  $row->v_precio;
			$json[$count]['v_im1'] =  $row->v_im1;
			$json[$count]['v_im2'] =  $row->v_im2;
			$json[$count]['v_im3'] =  $row->v_im3;
			$json[$count]['v_im4'] =  $row->v_im4;
			$json[$count]['v_im5'] =  $row->v_im5;
			$json[$count]['v_alta'] =  $row->v_alta;
			$json[$count]['v_Mun'] =  $row->v_Mun;
			$json[$count]['v_Est'] =  $row->v_Est;
			$json[$count]['v_Pai'] =  $row->v_Pai;
			$json[$count]['v_kil'] =  $row->v_kil;

			$json[$count]['id_usuario'] =  $row->id_usuario;
			
			$count++;
		}

		$datos['data'] = $json;
		echo json_encode($datos);
	}

	public function addItem()
	{

		$date = date('YmdHiss');
		//Receive the data from android
		$id_usuario = $_POST['id_usuario'];
		$v_telefono = $_POST['v_telefono'];
		$v_marcas = $_POST['v_marcas'];
		$v_modelo = $_POST['v_modelo'];
		$v_anos = $_POST['v_anos'];
		$v_cilindros = $_POST['v_cilindros'];
		$v_desc = $_POST['v_desc'];
		$v_precio = $_POST['v_precio'];
		$municipio = utf8_encode($_POST['v_Municipio']);
		$estado = utf8_encode($_POST['v_Estado']);
		$pais = utf8_encode($_POST['v_Pais']);
		$kilometros = $_POST['v_Kilolmetros'];
		$GeoLocation = $municipio.' '.$estado.' '.$pais;

		setlocale(LC_TIME, 'spanish');
		$alta=strftime("%d de %B de %Y");

		//$alta = date('YYYY/mm/dd');

		$uploaddir = "./assets/Uploads/";
		$uploadfile = $uploaddir . $date.basename($_FILES['image']['name']);
		$uploadfile2 = $uploaddir . $date.basename($_FILES['image2']['name']);
		$uploadfile3 = $uploaddir . $date.basename($_FILES['image3']['name']);
		$uploadfile4 = $uploaddir . $date.basename($_FILES['image4']['name']);
		$uploadfile5 = $uploaddir . $date.basename($_FILES['image5']['name']);


		$data = array(
		   'id_usuario' => $id_usuario ,
		   'GeoLocation' => $GeoLocation,
		   'v_telefono' => $v_telefono,
		   'v_marcas' => $v_marcas ,
		   'v_modelo' => $v_modelo ,
		   'v_anos' => $v_anos,
		   'v_cilindros' => $v_cilindros,
		   'v_desc' => $v_desc,
		   'v_precio' => $v_precio,
		   'v_im1' => $uploadfile,
		   'v_im2' => $uploadfile2,
		   'v_im3' => $uploadfile3,
		   'v_im4' => $uploadfile4,
		   'v_im5' => $uploadfile5,
		   'v_Mun' => $municipio,
		   'v_Est' => $estado,
		   'v_Pai' => $pais,
		   'v_kil' => $kilometros,
		   'v_alta' => $alta
		);

		
		if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile) && move_uploaded_file($_FILES['image2']['tmp_name'], $uploadfile2) && move_uploaded_file($_FILES['image3']['tmp_name'], $uploadfile3) && move_uploaded_file($_FILES['image4']['tmp_name'], $uploadfile4) && move_uploaded_file($_FILES['image5']['tmp_name'], $uploadfile5)) {
		  if($this->db->insert('mex_pubs', $data))
		  {
		  	echo json_encode(
	            array(
	                'result'=>'success',
	                'msg'=> "Datos subidos con exito"
	                )
	            );
		  }
		  else
		  {
		  	 echo json_encode(
	            array(
	                'result'=>'success',
	                'msg'=> "Ocurrio un error vuelva a intentarlo"
	                )
	            );
		  }
		  
		} else {
			  echo json_encode(
	            array(
	                'result'=>'success',
	                'msg'=> "Ocurrio un error vuelva a intentarlo"
	                )
	            );
		}




		

			

	}

	
}
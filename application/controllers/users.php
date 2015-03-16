<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	public function login()
	{
		$correo = $_POST['correo'];
		$password = $_POST['password'];

		//Verifica que no exista ningun usuario con ese correo
		$this->db->where('correo', $correo);
    	$this->db->where('status', 1);
    	$this->db->where('password', MD5($password));
    	$query = $this->db->get('mex_users');

    	$json = array();

	   	if($query->num_rows == '1')
	   	{
	     	foreach ($query->result() as $row)
			{
				$json['error'] = '0';
				$json['correo'] =  $row->correo;
				$json['id_usuario'] =  $row->id_usuario;
    			$json['message'] = 'Ingreso correctamente.';
			}
	   	}
	   else
  	 	{
	     	$json['error'] = '1';
			$json['correo'] =  '';
			$json['id_usuario'] =  '';
    		$json['message'] = 'Login incorrecto, verifique sus datos.';
	   	}

	   	$data['data'] = $json;

	   	echo json_encode($data);
	}

	public function addUser()
	{
		$correo = $_POST['correo'];
		$password = $_POST['password'];

		$status = 0;
		$fecha_alta = date("m/d/Y h:i:s a", time());
		$token = md5(uniqid($correo.$password, true));
		$url = base_url()."index.php/users/activate?token=".$token."&correo=".$correo;

		//Verifica que no exista ningun usuario con ese correo
		$this->db->where('correo', $correo);
    	$this->db->where('status', 1);
    	$query = $this->db->get('mex_users');


    	$json = array();

    	//Aqui hace la validacion de que no exista ningun usuario con ese correo y que este activo
		if($query->num_rows == '1')
		{
			$json['error'] = '1';
    		$json['message'] = 'Ya existe un usuario con este correo. Si no recuerda su contraseña puede recuperarla en la aplicacion.';
		}else
		{
			$data = array(
			   'correo' => $correo ,
			   'password' => MD5($password) ,
			   'telefono' => '',
			   'status' => $status,
			   'fecha_alta' => $fecha_alta,
			   'token' => $token
			);

			// si no existe incerta el correo en la base de datos
			if($this->db->insert('mex_users', $data))
			{
				$this->sendMail('mexicarro@novus-code.com',$correo,$url);
				$json['error'] = '0';
    			$json['message'] = 'Le enviamos un correo electronico para activar la cuenta.';
			}
			else
			{
				$json['error'] = '1';
    			$json['message'] = 'No se pudo agregar el usuario, intentelo mas tarde.';
			}


		}


		$datos['data'] = $json;
		echo json_encode($datos);

	}

	public function activate()
	{

		//activa el correo, obtiene un token generado a la hora de almacenar el usuario en la base de datos
		//si el token coinside se pone el status del usuario en 1 para poder entrar a la app
		$token = $_GET['token'];
		$correo = $_GET['correo'];

		//Verifica que no exista ningun usuario con ese correo
		$this->db->where('correo', $correo);
    	$this->db->where('status', 1);
    	$query = $this->db->get('mex_users');


    	$json = array();

    	//Aqui hace la validacion de que no exista ningun usuario con ese correo y que este activo
		if($query->num_rows == '1')
		{
			$json['error'] = '1';
    		$json['message'] = 'Ya existe una cuenta activada con este correo electronico. <br/><br/>Si no recuerdas tu contraseña en la aplicacion puedes restablecerla.';
    		$this->load->view('confirmation',$json);
		}else
		{
			$data = array('status' => 1);

			$this->db->where('token', $token);

			if($this->db->update('mex_users', $data))
			{
				$json['error'] = '0';
    			$json['message'] = 'Cuenta activada correctamente, ahora puede acceder a la aplicacion con el usuario y contraseña que indico.';

				$this->load->view('confirmation',$json);
			}else
			{
				$json['error'] = '1';
    			$json['message'] = 'Ocurrio un error intentelo mas tarde.';

				$this->load->view('confirmation',$json);
			}
		}

	}

	public function sendMail($from,$to,$url)
	{
		$data['url'] = $url;
		//se le envia un correo al usuario.
		$this->email->from($from, 'Mexi Carro');
		$this->email->to($to);
		$this->email->subject('Confirmacion de cuenta - MexiCarro ');
		$message=$this->load->view('template',$data,TRUE);
		$this->email->message($message);
		//	$this->email->message('<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"> <html xmlns=\"http://www.w3.org/1999/xhtml\"> <head> <meta name=\"viewport\" content=\"width=device-width\" /> <meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\" /> </head> <style> *{margin:0;padding:0}*{font-family:\"Helvetica Neue\",\"Helvetica\",Helvetica,Arial,sans-serif}img{max-width:100%}.collapse{padding:0}body{-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100%!important;height:100%}a{color:#2BA6CB}.btn{text-decoration:none;color:#FFF;background-color:#666;padding:10px 16px;font-weight:700;margin-right:10px;text-align:center;cursor:pointer;display:inline-block}p.callout{padding:15px;background-color:#ECF8FF;margin-bottom:15px}.callout a{font-weight:700;color:#2BA6CB}table.social{background-color:#ebebeb}.social .soc-btn{padding:3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color:#FFF;font-weight:700;display:block;text-align:center}a.fb{background-color:#3B5998!important}a.tw{background-color:#1daced!important}a.gp{background-color:#DB4A39!important}a.ms{background-color:#000!important}.sidebar .soc-btn{display:block;width:100%}table.head-wrap{width:100%}.header.container table td.logo{padding:15px}.header.container table td.label{padding:15px;padding-left:0}table.body-wrap{width:100%}table.footer-wrap{width:100%;clear:both!important}.footer-wrap .container td.content p{border-top:1px solid #d7d7d7;padding-top:15px;font-size:10px;font-weight:700}h1,h2,h3,h4,h5,h6{font-family:\"HelveticaNeue-Light\",\"Helvetica Neue Light\",\"Helvetica Neue\",Helvetica,Arial,\"Lucida Grande\",sans-serif;line-height:1.1;margin-bottom:15px;color:#000}h1 small,h2 small,h3 small,h4 small,h5 small,h6 small{font-size:60%;color:#6f6f6f;line-height:0;text-transform:none}h1{font-weight:200;font-size:44px}h2{font-weight:200;font-size:37px}h3{font-weight:500;font-size:27px}h4{font-weight:500;font-size:23px}h5{font-weight:900;font-size:17px}h6{font-weight:900;font-size:14px;text-transform:uppercase;color:#444}.collapse{margin:0!important}p,ul{margin-bottom:10px;font-weight:400;font-size:14px;line-height:1.6}p.lead{font-size:17px}p.last{margin-bottom:0}ul li{margin-left:5px;list-style-position:inside}ul.sidebar{background:#ebebeb;display:block;list-style-type:none}ul.sidebar li{display:block;margin:0}ul.sidebar li a{text-decoration:none;color:#666;padding:10px 16px;cursor:pointer;border-bottom:1px solid #777;border-top:1px solid #FFF;display:block;margin:0}ul.sidebar li a.last{border-bottom-width:0}ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p{margin-bottom:0!important}.container{display:block!important;max-width:600px!important;margin:0 auto!important;clear:both!important}.content{padding:15px;max-width:600px;margin:0 auto;display:block}.content table{width:100%}.column{width:300px;float:left}.column tr td{padding:15px}.column-wrap{padding:0!important;margin:0 auto;max-width:600px!important}.column table{width:100%}.social .column{width:280px;min-width:279px;float:left}.clear{display:block;clear:both}@media only screen and (max-width:600px){a[class=\"btn\"]{display:block!important;margin-bottom:10px!important;background-image:none!important;margin-right:0!important}div[class=\"column\"]{width:auto!important;float:none!important}table.social div[class=\"column\"]{width:auto!important}}</style> <body bgcolor=\"#FFFFFF\"> <!-- HEADER --> <table class=\"head-wrap\" bgcolor=\"#0F98BF\"> <tr> <td></td> <td class=\"header container\"><div class=\"content\"> </div> </td> <td></td> </tr> </table><!-- /HEADER --> <!-- BODY --> <table class=\"body-wrap\"> <tr> <td></td> <td class=\"container\" bgcolor=\"#FFFFFF\"> <div class=\"content\"> <table> <tr> <td> <h3>Hola,Gracias por registrarte en MexiCarro. </h3> <p class=\"lead\">Ahora podras publicar,buscar,guardar y administrar de una manera mas facil tus automoviles en venta. Solo tienes que dar Click para activar tu cuenta en el siguiente link:</p> <!-- Callout Panel --> <p class=\"callout\"> '.$url.' </p><!-- /Callout Panel --> <p>Podras acceder a la aplicacion con el correo y contraseña que indicaste en el registro.</p> </td> </tr> </table> </div><!-- /content --> </td> <td></td> </tr> </table><!-- /BODY --> </body> </html>');
		$this->email->set_mailtype("html");
		$this->email->send();
	}
}

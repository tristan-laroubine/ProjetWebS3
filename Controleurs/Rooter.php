<?php
class Rooter
{
	private $_ctrl;
	private $_view;

	public function routeReq()
	{
		try
		{
			//CHARGEMENT AUTOMATIQUE DES CLASSES
			spl_autoload_register(function($class){
			    if(strpos($class, "Controller") === true)
			        require_once ('Controleurs/'.$class.'.php');
			    else
				    require_once('Modeles/'.$class.'.php');
			});

			$url = filter_input(INPUT_GET,'url',FILTER_SANITIZE_URL);
//			var_dump($url);
//			die;

			//LE CONTROLER EST INCLUS SELON L'ACTION DE L'UTILISATEUR
			if(!is_null($url))
			{

                $url = explode('/', $url);

				$controller = ucfirst($url[0]);
				$controlerClass = "Controller".$controller;
				$controllerFile = 	"Controleurs/".$controlerClass.".php";


				if(file_exists($controllerFile))
				{
					require_once($controllerFile);
					$this->_ctrl = new $controlerClass($url);
				}
				else
                {
					throw new Exception('Page introuvable ' . $controllerFile .' ');
                }
			}
			else
			{
				require_once('Controleurs/ControllerIndex.php');
				$this->_ctrl = new ControllerIndex();
			}
		}
		//GESTION DES EXCEPTIONS
		catch(Exception $e)
		{
			$errorMsg = $e->getMessage();
			require_once('Views/viewError.php');
		}
	}
}

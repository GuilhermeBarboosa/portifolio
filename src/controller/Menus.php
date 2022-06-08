<?php

	/**
	 * Generated by Getz Framework
	 *
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz
	 */
	 
	use lib\getz;
	use src\logic;	 
	use src\model;	 
	
	require_once($_DOCUMENT_ROOT . "/lib/getz/Activator.php");
	
	/*
	 * Filters
	 */
	$where = "";
	
	if ($search != "")
		$where = "menus.menu LIKE \"%" . $search . "%\"";	
		
	if ($code != "")
		$where = "menus.id = " . $code;
	
	if (isset($_GET["friendly"]))
		$where = "menus.menu = \"" . removeLine($_GET["friendly"]) . "\"";	
		
	$limit = "";	
	
	if ($order != "") {
		$o = explode("<gz>", $order);
		if ($method == "stateReadAll" || $method == "stateCalledAll") {
			$limit = $o[0] . " " . $o[1];
		} else {
			$limit = $o[0] . " " . $o[1] . " LIMIT " . 
					(($position * $itensPerPage) - $itensPerPage) . ", " . $itensPerPage;
		}		
	} else {
		if ($method == "stateReadAll" || $method == "stateCalledAll") {
			$limit = "menus.ordem ASC";	
		} else {
			if ($position > 0 && $itensPerPage > 0) {
				$limit = "menus.ordem ASC LIMIT " . 
						(($position * $itensPerPage) - $itensPerPage) . ", " . $itensPerPage;	
			}
		}
	}
	
	/**************************************************
	 * Webpage
	 **************************************************/		
	
	/*
	 * Page
	 */
	if ($method == "page") {
		/*
		 * SEO
		 */
		$view->setTitle(ucfirst($screen));
		$view->setDescription("");
		$view->setKeywords("");
		
		$daoFactory->beginTransaction();
		$response["menus"] = $daoFactory->getMenusDao()->read($where, $limit, true);
		$daoFactory->close();
		
		if (isset($_GET["friendly"]))
			$view->setTitle($response["menus"][0]["menus.menu"]);

		echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/header.html", $response);
		
		echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . 
				(isset($_GET["friendly"]) ? "/html/menus.html" : "/html/menus.html"), $response);
		
		echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/footer.html", $response);
	}
	
	/**************************************************
	 * Webservice
	 **************************************************/	

	/*
	 * Create
	 */
	else if ($method == "api-create") {
		enableCORS();
		if (isset($_POST["request"])) {
			$request = json_decode($_POST["request"], true);
			// $request[0]["@_PARAM"] = $daoFactory->prepare($request[0]["@_PARAM"]); // Prepare with sql injection.

			$daoFactory->beginTransaction();
			$menus = new model\Menus();
			$menus->setMenu(logicNull($request["menus.menu"]));
			$menus->setLink(logicNull($request["menus.link"]));
			
			$menusDao = $daoFactory->getMenusDao()->read("", "menus.ordem DESC LIMIT 0, 1", false);
			$menus->setOrdem($menusDao[0]["menus.ordem"] + 1);
				
			$menus->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$menus->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$menus->setTipo_menu(2);
			$menus->setSituacao_registro($request["menus.situacao_registro"]);
			
			$resultDao = $daoFactory->getMenusDao()->create($menus);

			if ($resultDao) {
				$daoFactory->commit();
				$response["message"] = "success";
			} else {							
				$daoFactory->rollback();
				$response["message"] = "error";
			}

			$daoFactory->close();
		} else {
			$response["message"] = "error";
		}
		
		echo $view->json($response);
	}
	
	/*
	 * Read
	 */
	else if ($method == "api-read") {
		enableCORS();
		
		if (isset($_POST["request"])) {
			$request = json_decode($_POST["request"], true);
			
			$limit = "menus.id DESC LIMIT " . 
					(($request[0]["page"] * $request[0]["pageSize"]) - 
					$request[0]["pageSize"]) . ", " . $request[0]["pageSize"];	
		}
		
		$daoFactory->beginTransaction();
		$menus = $daoFactory->getMenusDao()->read("", $limit, false);
		$daoFactory->close();
		
		echo $view->json($menus);
	}
	
	/*
	 * Update
	 */
	else if ($method == "api-update") {	
		enableCORS();
		if (isset($_POST["request"])) {
			$request = json_decode($_POST["request"], true);
			// $request[0]["@_PARAM"] = $daoFactory->prepare($request[0]["@_PARAM"]); // Prepare with sql injection.
			
			$menus = new model\Menus();
			$menus->setId($request["menus.id"]);
			$menus->setMenu(logicNull($request["menus.menu"]));
			$menus->setLink(logicNull($request["menus.link"]));
			
			$where = "menus.id = " . $request["menus.id"];
			
			$daoFactory->beginTransaction();
			$menusDao = $daoFactory->getMenusDao()->read($where, "", false);
			$daoFactory->close();

			$menus->setOrdem($menusDao[0]["menus.ordem"]);
				
			$menus->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$menus->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$menus->setTipo_menu(2);
			$menus->setSituacao_registro($request["menus.situacao_registro"]);
			
			$daoFactory->beginTransaction();
			$resultDao = $daoFactory->getMenusDao()->update($menus);

			if ($resultDao) {
				$daoFactory->commit();
				$response["message"] = "success";
			} else {							
				$daoFactory->rollback();
				$response["message"] = "error";
			}

			$daoFactory->close();
		} else {
			$response["message"] = "error";
		}
		
		echo $view->json($response);
	}
	
	/* 
	 * Delete
	 */
	else if ($method == "api-delete") {
		enableCORS();
		if (isset($_POST["request"])) {
			$request = json_decode($_POST["request"], true);
			$request["menus.id"] = $daoFactory->prepare($request["menus.id"]); // Prepare with sql injection.
				
			$result = true;
			$lines = explode("<gz>", $request["menus.id"]);

			$daoFactory->beginTransaction();

			for ($i = 0; $i < sizeof($lines); $i++) {
				$where = "menus.id = " . $lines[$i];
				
				$resultDao = $daoFactory->getMenusDao()->delete($where);
				$result = !$result ? false : (!$resultDao ? false : true);
			}

			if ($result) {
				$daoFactory->commit();
				$response["message"] = "success";
			} else {							
				$daoFactory->rollback();
				$response["message"] = "error";
			}

			$daoFactory->close();
		} else {
			$response["message"] = "error";
		} 

		echo $view->json($response);
	}
	
	else if ($method == "changeOrder") {		
		$result = true;
		$daoFactory->beginTransaction();
		$call = $daoFactory->getMenusDao()->read("menus.id = " . $form[0], "", false);
		$answer = $daoFactory->getMenusDao()->read("menus.id = " . $form[1], "", false);
		$menusDao = $daoFactory->getMenusDao()->read("menus.ordem >= " . $answer[0]["menus.ordem"], "", false);
		if (is_array($menusDao) && sizeof($menusDao) > 0) {
			for ($x = 0; $x < sizeof($menusDao); $x++) {
				$menus = new model\Menus();
				$menus->setId($menusDao[$x]["menus.id"]);
				$menus->setMenu(logicNull($menusDao[$x]["menus.menu"]));
			$menus->setLink(logicNull($menusDao[$x]["menus.link"]));
			
			$menus->setOrdem($menusDao[$x]["menus.ordem"] + 1);
				
			$menus->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$menus->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$menus->setTipo_menu($menusDao[$x]["menus.tipo_menu"]);
			$menus->setSituacao_registro($menusDao[$x]["menus.situacao_registro"]);
			
				$resultDao = $daoFactory->getMenusDao()->update($menus);			
				$result = !$result ? false : (!$resultDao ? false : true);
			}
			$menus = new model\Menus();
			$menus->setId($call[0]["menus.id"]);
			$menus->setMenu(logicNull($call[0]["menus.menu"]));
			$menus->setLink(logicNull($call[0]["menus.link"]));
			
			$menus->setOrdem($answer[0]["menus.ordem"]);
				
			$menus->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$menus->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$menus->setTipo_menu($call[0]["menus.tipo_menu"]);
			$menus->setSituacao_registro($call[0]["menus.situacao_registro"]);
			
			$resultDao = $daoFactory->getMenusDao()->update($menus);			
			$result = !$result ? false : (!$resultDao ? false : true);
		}
		if ($result) {
			$daoFactory->commit();
			$response[0]["message"] = "success";
		} else {							
			$daoFactory->rollback();
			$response[0]["message"] = "error";
		}
		$daoFactory->close();
		echo $darth->json($response);
	}
	
	/**************************************************
	 * System
	 **************************************************/	
	
	else {
		if (!getActiveSession($_ROOT . $_MODULE)) 
			echo "<script>goTo(\"/login/1\");</script>";
		else {
			/*
			 * Create
			 */
			if ($method == "stateCreate") {
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method))
					echo "<script>goTo(\"/login/1\");</script>";	
				else {
					$daoFactory->beginTransaction();
					$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
					$response["tipos_menus"] = $daoFactory->getTipos_menusDao()->read("", "tipos_menus.id ASC", false);
					$response["situacoes_registros"] = $daoFactory->getSituacoes_registrosDao()->read("", "situacoes_registros.id ASC", false);
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCRT.html", $response);
				}
			}

			/*
			 * Read
			 */
			else if ($method == "stateRead" || $method == "stateReadAll") {
				if ($method == "stateReadAll") {
					$method = "stateRead";
				}
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method))
					echo "<script>goTo(\"/login/1\");</script>";	
				else {
					$daoFactory->beginTransaction();
					$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
					if ($where != "") {
						$where .= " AND menus.tipo_menu = 2";
					} else {
						$where = "menus.tipo_menu = 2";
					}
					$response["menus"] = $daoFactory->getMenusDao()->read($where, $limit, true);
					if (!is_array($response["menus"])) {
						$response["data_not_found"][0]["value"] = "<p>Não possui registro.</p>";
					}
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusRD.html", $response);
				}
			}

			/*
			 * Update
			 */
			else if ($method == "stateUpdate") {
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method))
					echo "<script>goTo(\"/login/1\");</script>";	
				else {
					$daoFactory->beginTransaction();
					$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
					$response["menus"] = $daoFactory->getMenusDao()->read($where, "", true);
					$response["menus"][0]["menus.tipos_menus"] = $daoFactory->getTipos_menusDao()->read("", "tipos_menus.id ASC", false);
					for ($x = 0; $x < sizeof($response["menus"][0]["menus.tipos_menus"]); $x++) {
						if ($response["menus"][0]["menus.tipos_menus"][$x]["tipos_menus.id"] == 
								$response["menus"][0]["menus.tipo_menu"]) {
							$response["menus"][0]["menus.tipos_menus"][$x]["tipos_menus.selected"] = "selected";
						}
					}
					$response["menus"][0]["menus.situacoes_registros"] = $daoFactory->getSituacoes_registrosDao()->read("", "situacoes_registros.id ASC", false);
					for ($x = 0; $x < sizeof($response["menus"][0]["menus.situacoes_registros"]); $x++) {
						if ($response["menus"][0]["menus.situacoes_registros"][$x]["situacoes_registros.id"] == 
								$response["menus"][0]["menus.situacao_registro"]) {
							$response["menus"][0]["menus.situacoes_registros"][$x]["situacoes_registros.selected"] = "selected";
						}
					}
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusUPD.html", $response);
				}
			}

			/*
			 * Called
			 */
			else if ($method == "stateCalled" || $method == "stateCalledAll") {
				if ($method == "stateCalledAll") {
					$method = "stateCalled";
				}
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method))
					echo "<script>goTo(\"/login/1\");</script>";	
				else {
					/*
					 * Insert your foreign key here
					 */
					if ($where != "")
						$where .= " AND menus.@_FOREIGN_KEY = " . $base;
					else 
						$where = "menus.@_FOREIGN_KEY = " . $base;
						
					$daoFactory->beginTransaction();
					$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
					$response["menus"] = $daoFactory->getMenusDao()->read($where, $limit, true);
					if (!is_array($response["menus"])) {
						$response["data_not_found"][0]["value"] = "<p>Não possui registro.</p>";
					}
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCLL.html", $response);
				}
			}

			/*
			 * Screen
			 */
			else if ($method == "screen") {
				if ($base != "") {
					$arrBase = explode("<gz>", $base);
					
					if (sizeof($arrBase) > 1) {
						if ($where != "")
							$where .= " AND menus.@_FOREIGN_KEY = " . $arrBase[1];
						else
							$where = "menus.@_FOREIGN_KEY = " . $arrBase[1];
					}
				}
				
				$limit = "menus.id DESC LIMIT " . (($position * 5) - 5) . ", 5";

				$daoFactory->beginTransaction();
				$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
				$response["menus"] = $daoFactory->getMenusDao()->read($where, $limit, true);
				$daoFactory->close();

				echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusSCR.html", $response) . 
						"<size>" . (is_array($response["menus"]) ? $response["menus"][0]["menus.size"] : 0) . "<theme>455a64";
			}

			/*
			 * Screen handler
			 */
			else if ($method == "screenHandler") {	
				$where = "";

				// Get value from combo
				$cmb = explode("<gz>", $search);

				if ($cmb[1] != "")
					$where = "menus.id = " . $cmb[1];

				$daoFactory->beginTransaction();
				$response["menus"] = $daoFactory->getMenusDao()->comboScr($where);
				$daoFactory->close();

				echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCMB.html", $response);
			}

			/*
			 * Create
			 */
			else if ($method == "create") {
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method)) {
					$response["message"] = "permission";
					
					echo $view->json($response);
				} else {
					$menus = new model\Menus();
					$menus->setMenu(logicNull($form[0]));
					$menus->setLink(logicNull($form[1]));
					
					$daoFactory->beginTransaction();
					$menusDao = $daoFactory->getMenusDao()->read("", "menus.ordem DESC LIMIT 0, 1", false);
					$daoFactory->close();
						
					$menus->setOrdem($menusDao[0]["menus.ordem"] + 1);
						
					$menus->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					$menus->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					$menus->setTipo_menu(2);
					$menus->setSituacao_registro($form[2]);
					
					$daoFactory->beginTransaction();
					$resultDao = $daoFactory->getMenusDao()->create($menus);

					if ($resultDao) {
						$daoFactory->commit();
						$response["message"] = "success";				
					} else {							
						$daoFactory->rollback();
						$response["message"] = "error";
					}

					$daoFactory->close();

					echo $view->json($response);
				}
			}

			/*
			 * Action update
			 */
			else if ($method == "update") {	
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method)) {
					$response["message"] = "permission";
					
					echo $view->json($response);
				} else {
					$menus = new model\Menus();
					$menus->setId($code);
					$menus->setMenu(logicNull($form[0]));
					$menus->setLink(logicNull($form[1]));
					
					$where = "menus.id = " . $code;
					
					$daoFactory->beginTransaction();
					$menusDao = $daoFactory->getMenusDao()->read($where, "", false);
					$daoFactory->close();
						
					$menus->setOrdem($menusDao[0]["menus.ordem"]);
						
					$menus->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					$menus->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					$menus->setTipo_menu(2);
					$menus->setSituacao_registro($form[2]);
					
					$daoFactory->beginTransaction();
					$resultDao = $daoFactory->getMenusDao()->update($menus);

					if ($resultDao) {
						$daoFactory->commit();
						$response["message"] = "success";
					} else {							
						$daoFactory->rollback();
						$response["message"] = "error";
					}

					$daoFactory->close();

					echo $view->json($response);
				}
			}
			
			/* 
			 * Action delete
			 */
			else if ($method == "delete") {
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method)) {
					$response["message"] = "permission";
					
					echo $view->json($response);
				} else {
					$result = true;
					$lines = explode("<gz>", $code);

					$daoFactory->beginTransaction();

					for ($i = 1; $i < sizeof($lines); $i++) {
						$where = "menus.id = " . $lines[$i];
						
						$resultDao = $daoFactory->getMenusDao()->delete($where);
						$result = !$result ? false : (!$resultDao ? false : true);
					}

					if ($result) {
						$daoFactory->commit();
						$response["message"] = "success";
					} else {							
						$daoFactory->rollback();
						$response["message"] = "error";
					}

					$daoFactory->close();

					echo $view->json($response);	
				}
			}
		}
	}

?>
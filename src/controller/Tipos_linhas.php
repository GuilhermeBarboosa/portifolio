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
		$where = "tipos_linhas.tipo_linha LIKE \"%" . $search . "%\"";	
		
	if ($code != "")
		$where = "tipos_linhas.id = " . $code;
	
	if (isset($_GET["friendly"]))
		$where = "tipos_linhas.tipo_linha = \"" . removeLine($_GET["friendly"]) . "\"";	
		
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
			$limit = "tipos_linhas.ordem ASC";	
		} else {
			if ($position > 0 && $itensPerPage > 0) {
				$limit = "tipos_linhas.id DESC LIMIT " . 
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
		$response["tipos_linhas"] = $daoFactory->getTipos_linhasDao()->read($where, $limit, true);
		$daoFactory->close();
		
		if (isset($_GET["friendly"]))
			$view->setTitle($response["tipos_linhas"][0]["tipos_linhas.tipo_linha"]);

		echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/header.html", $response);
		
		echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . 
				(isset($_GET["friendly"]) ? "/html/tipos_linhas.html" : "/html/tipos_linhas.html"), $response);
		
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
			$tipos_linhas = new model\Tipos_linhas();
			$tipos_linhas->setTipo_linha(logicNull($request["tipos_linhas.tipo_linha"]));
			$tipos_linhas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$tipos_linhas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			
			$resultDao = $daoFactory->getTipos_linhasDao()->create($tipos_linhas);

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
			
			$limit = "tipos_linhas.id DESC LIMIT " . 
					(($request[0]["page"] * $request[0]["pageSize"]) - 
					$request[0]["pageSize"]) . ", " . $request[0]["pageSize"];	
		}
		
		$daoFactory->beginTransaction();
		$tipos_linhas = $daoFactory->getTipos_linhasDao()->read("", $limit, false);
		$daoFactory->close();
		
		echo $view->json($tipos_linhas);
	}
	
	/*
	 * Update
	 */
	else if ($method == "api-update") {	
		enableCORS();
		if (isset($_POST["request"])) {
			$request = json_decode($_POST["request"], true);
			// $request[0]["@_PARAM"] = $daoFactory->prepare($request[0]["@_PARAM"]); // Prepare with sql injection.
			
			$tipos_linhas = new model\Tipos_linhas();
			$tipos_linhas->setId($request["tipos_linhas.id"]);
			$tipos_linhas->setTipo_linha(logicNull($request["tipos_linhas.tipo_linha"]));
			$tipos_linhas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$tipos_linhas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			
			$daoFactory->beginTransaction();
			$resultDao = $daoFactory->getTipos_linhasDao()->update($tipos_linhas);

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
			$request["tipos_linhas.id"] = $daoFactory->prepare($request["tipos_linhas.id"]); // Prepare with sql injection.
				
			$result = true;
			$lines = explode("<gz>", $request["tipos_linhas.id"]);

			$daoFactory->beginTransaction();

			for ($i = 0; $i < sizeof($lines); $i++) {
				$where = "tipos_linhas.id = " . $lines[$i];
				
				$resultDao = $daoFactory->getTipos_linhasDao()->delete($where);
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
		$call = $daoFactory->getTipos_linhasDao()->read("tipos_linhas.id = " . $form[0], "", false);
		$answer = $daoFactory->getTipos_linhasDao()->read("tipos_linhas.id = " . $form[1], "", false);
		$tipos_linhasDao = $daoFactory->getTipos_linhasDao()->read("tipos_linhas.ordem >= " . $answer[0]["tipos_linhas.ordem"], "", false);
		if (is_array($tipos_linhasDao) && sizeof($tipos_linhasDao) > 0) {
			for ($x = 0; $x < sizeof($tipos_linhasDao); $x++) {
				$tipos_linhas = new model\Tipos_linhas();
				$tipos_linhas->setId($tipos_linhasDao[$x]["tipos_linhas.id"]);
				$tipos_linhas->setTipo_linha(logicNull($tipos_linhasDao[$x]["tipos_linhas.tipo_linha"]));
			$tipos_linhas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$tipos_linhas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			
				$resultDao = $daoFactory->getTipos_linhasDao()->update($tipos_linhas);			
				$result = !$result ? false : (!$resultDao ? false : true);
			}
			$tipos_linhas = new model\Tipos_linhas();
			$tipos_linhas->setId($call[0]["tipos_linhas.id"]);
			$tipos_linhas->setTipo_linha(logicNull($call[0]["tipos_linhas.tipo_linha"]));
			$tipos_linhas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$tipos_linhas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			
			$resultDao = $daoFactory->getTipos_linhasDao()->update($tipos_linhas);			
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
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_linhas/tipos_linhasCRT.html", $response);
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
					$response["tipos_linhas"] = $daoFactory->getTipos_linhasDao()->read($where, $limit, true);
					if (!is_array($response["tipos_linhas"])) {
						$response["data_not_found"][0]["value"] = "<p>N??o possui registro.</p>";
					}
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_linhas/tipos_linhasRD.html", $response);
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
					$response["tipos_linhas"] = $daoFactory->getTipos_linhasDao()->read($where, "", true);
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_linhas/tipos_linhasUPD.html", $response);
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
						$where .= " AND tipos_linhas.@_FOREIGN_KEY = " . $base;
					else 
						$where = "tipos_linhas.@_FOREIGN_KEY = " . $base;
						
					$daoFactory->beginTransaction();
					$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
					$response["tipos_linhas"] = $daoFactory->getTipos_linhasDao()->read($where, $limit, true);
					if (!is_array($response["tipos_linhas"])) {
						$response["data_not_found"][0]["value"] = "<p>N??o possui registro.</p>";
					}
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_linhas/tipos_linhasCLL.html", $response);
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
							$where .= " AND tipos_linhas.@_FOREIGN_KEY = " . $arrBase[1];
						else
							$where = "tipos_linhas.@_FOREIGN_KEY = " . $arrBase[1];
					}
				}
				
				$limit = "tipos_linhas.id DESC LIMIT " . (($position * 5) - 5) . ", 5";

				$daoFactory->beginTransaction();
				$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
				$response["tipos_linhas"] = $daoFactory->getTipos_linhasDao()->read($where, $limit, true);
				$daoFactory->close();

				echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_linhas/tipos_linhasSCR.html", $response) . 
						"<size>" . (is_array($response["tipos_linhas"]) ? $response["tipos_linhas"][0]["tipos_linhas.size"] : 0) . "<theme>455a64";
			}

			/*
			 * Screen handler
			 */
			else if ($method == "screenHandler") {	
				$where = "";

				// Get value from combo
				$cmb = explode("<gz>", $search);

				if ($cmb[1] != "")
					$where = "tipos_linhas.id = " . $cmb[1];

				$daoFactory->beginTransaction();
				$response["tipos_linhas"] = $daoFactory->getTipos_linhasDao()->comboScr($where);
				$daoFactory->close();

				echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_linhas/tipos_linhasCMB.html", $response);
			}

			/*
			 * Create
			 */
			else if ($method == "create") {
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method)) {
					$response["message"] = "permission";
					
					echo $view->json($response);
				} else {
					$tipos_linhas = new model\Tipos_linhas();
					$tipos_linhas->setTipo_linha(logicNull($form[0]));
					$tipos_linhas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					$tipos_linhas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					
					$daoFactory->beginTransaction();
					$resultDao = $daoFactory->getTipos_linhasDao()->create($tipos_linhas);

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
					$tipos_linhas = new model\Tipos_linhas();
					$tipos_linhas->setId($code);
					$tipos_linhas->setTipo_linha(logicNull($form[0]));
					$tipos_linhas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					$tipos_linhas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					
					$daoFactory->beginTransaction();
					$resultDao = $daoFactory->getTipos_linhasDao()->update($tipos_linhas);

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
						$where = "tipos_linhas.id = " . $lines[$i];
						
						$resultDao = $daoFactory->getTipos_linhasDao()->delete($where);
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
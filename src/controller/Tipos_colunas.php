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
		$where = "tipos_colunas.tipo_coluna LIKE \"%" . $search . "%\"";	
		
	if ($code != "")
		$where = "tipos_colunas.id = " . $code;
	
	if (isset($_GET["friendly"]))
		$where = "tipos_colunas.tipo_coluna = \"" . removeLine($_GET["friendly"]) . "\"";	
		
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
			$limit = "tipos_colunas.ordem ASC";	
		} else {
			if ($position > 0 && $itensPerPage > 0) {
				$limit = "tipos_colunas.id DESC LIMIT " . 
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
		$response["tipos_colunas"] = $daoFactory->getTipos_colunasDao()->read($where, $limit, true);
		$daoFactory->close();
		
		if (isset($_GET["friendly"]))
			$view->setTitle($response["tipos_colunas"][0]["tipos_colunas.tipo_coluna"]);

		echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/header.html", $response);
		
		echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . 
				(isset($_GET["friendly"]) ? "/html/tipos_colunas.html" : "/html/tipos_colunas.html"), $response);
		
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
			$tipos_colunas = new model\Tipos_colunas();
			$tipos_colunas->setTipo_coluna(logicNull($request["tipos_colunas.tipo_coluna"]));
			$tipos_colunas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$tipos_colunas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			
			$resultDao = $daoFactory->getTipos_colunasDao()->create($tipos_colunas);

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
			
			$limit = "tipos_colunas.id DESC LIMIT " . 
					(($request[0]["page"] * $request[0]["pageSize"]) - 
					$request[0]["pageSize"]) . ", " . $request[0]["pageSize"];	
		}
		
		$daoFactory->beginTransaction();
		$tipos_colunas = $daoFactory->getTipos_colunasDao()->read("", $limit, false);
		$daoFactory->close();
		
		echo $view->json($tipos_colunas);
	}
	
	/*
	 * Update
	 */
	else if ($method == "api-update") {	
		enableCORS();
		if (isset($_POST["request"])) {
			$request = json_decode($_POST["request"], true);
			// $request[0]["@_PARAM"] = $daoFactory->prepare($request[0]["@_PARAM"]); // Prepare with sql injection.
			
			$tipos_colunas = new model\Tipos_colunas();
			$tipos_colunas->setId($request["tipos_colunas.id"]);
			$tipos_colunas->setTipo_coluna(logicNull($request["tipos_colunas.tipo_coluna"]));
			$tipos_colunas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$tipos_colunas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			
			$daoFactory->beginTransaction();
			$resultDao = $daoFactory->getTipos_colunasDao()->update($tipos_colunas);

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
			$request["tipos_colunas.id"] = $daoFactory->prepare($request["tipos_colunas.id"]); // Prepare with sql injection.
				
			$result = true;
			$lines = explode("<gz>", $request["tipos_colunas.id"]);

			$daoFactory->beginTransaction();

			for ($i = 0; $i < sizeof($lines); $i++) {
				$where = "tipos_colunas.id = " . $lines[$i];
				
				$resultDao = $daoFactory->getTipos_colunasDao()->delete($where);
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
		$call = $daoFactory->getTipos_colunasDao()->read("tipos_colunas.id = " . $form[0], "", false);
		$answer = $daoFactory->getTipos_colunasDao()->read("tipos_colunas.id = " . $form[1], "", false);
		$tipos_colunasDao = $daoFactory->getTipos_colunasDao()->read("tipos_colunas.ordem >= " . $answer[0]["tipos_colunas.ordem"], "", false);
		if (is_array($tipos_colunasDao) && sizeof($tipos_colunasDao) > 0) {
			for ($x = 0; $x < sizeof($tipos_colunasDao); $x++) {
				$tipos_colunas = new model\Tipos_colunas();
				$tipos_colunas->setId($tipos_colunasDao[$x]["tipos_colunas.id"]);
				$tipos_colunas->setTipo_coluna(logicNull($tipos_colunasDao[$x]["tipos_colunas.tipo_coluna"]));
			$tipos_colunas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$tipos_colunas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			
				$resultDao = $daoFactory->getTipos_colunasDao()->update($tipos_colunas);			
				$result = !$result ? false : (!$resultDao ? false : true);
			}
			$tipos_colunas = new model\Tipos_colunas();
			$tipos_colunas->setId($call[0]["tipos_colunas.id"]);
			$tipos_colunas->setTipo_coluna(logicNull($call[0]["tipos_colunas.tipo_coluna"]));
			$tipos_colunas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			$tipos_colunas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
			
			$resultDao = $daoFactory->getTipos_colunasDao()->update($tipos_colunas);			
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
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_colunas/tipos_colunasCRT.html", $response);
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
					$response["tipos_colunas"] = $daoFactory->getTipos_colunasDao()->read($where, $limit, true);
					if (!is_array($response["tipos_colunas"])) {
						$response["data_not_found"][0]["value"] = "<p>N??o possui registro.</p>";
					}
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_colunas/tipos_colunasRD.html", $response);
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
					$response["tipos_colunas"] = $daoFactory->getTipos_colunasDao()->read($where, "", true);
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_colunas/tipos_colunasUPD.html", $response);
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
						$where .= " AND tipos_colunas.@_FOREIGN_KEY = " . $base;
					else 
						$where = "tipos_colunas.@_FOREIGN_KEY = " . $base;
						
					$daoFactory->beginTransaction();
					$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
					$response["tipos_colunas"] = $daoFactory->getTipos_colunasDao()->read($where, $limit, true);
					if (!is_array($response["tipos_colunas"])) {
						$response["data_not_found"][0]["value"] = "<p>N??o possui registro.</p>";
					}
					$daoFactory->close();

					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/menus/menusCST.html", getMenu($daoFactory, $_USER, $screen));
					echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_colunas/tipos_colunasCLL.html", $response);
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
							$where .= " AND tipos_colunas.@_FOREIGN_KEY = " . $arrBase[1];
						else
							$where = "tipos_colunas.@_FOREIGN_KEY = " . $arrBase[1];
					}
				}
				
				$limit = "tipos_colunas.id DESC LIMIT " . (($position * 5) - 5) . ", 5";

				$daoFactory->beginTransaction();
				$response["titles"] = $daoFactory->getTelasDao()->read("telas.identificador = \"" . $screen . "\"", "", true);
				$response["tipos_colunas"] = $daoFactory->getTipos_colunasDao()->read($where, $limit, true);
				$daoFactory->close();

				echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_colunas/tipos_colunasSCR.html", $response) . 
						"<size>" . (is_array($response["tipos_colunas"]) ? $response["tipos_colunas"][0]["tipos_colunas.size"] : 0) . "<theme>455a64";
			}

			/*
			 * Screen handler
			 */
			else if ($method == "screenHandler") {	
				$where = "";

				// Get value from combo
				$cmb = explode("<gz>", $search);

				if ($cmb[1] != "")
					$where = "tipos_colunas.id = " . $cmb[1];

				$daoFactory->beginTransaction();
				$response["tipos_colunas"] = $daoFactory->getTipos_colunasDao()->comboScr($where);
				$daoFactory->close();

				echo $view->parse($_DOCUMENT_ROOT . $_PACKAGE . "/html/tipos_colunas/tipos_colunasCMB.html", $response);
			}

			/*
			 * Create
			 */
			else if ($method == "create") {
				if (!getPermission($_ROOT . $_MODULE, $daoFactory, $screen, $method)) {
					$response["message"] = "permission";
					
					echo $view->json($response);
				} else {
					$tipos_colunas = new model\Tipos_colunas();
					$tipos_colunas->setTipo_coluna(logicNull($form[0]));
					$tipos_colunas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					$tipos_colunas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					
					$daoFactory->beginTransaction();
					$resultDao = $daoFactory->getTipos_colunasDao()->create($tipos_colunas);

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
					$tipos_colunas = new model\Tipos_colunas();
					$tipos_colunas->setId($code);
					$tipos_colunas->setTipo_coluna(logicNull($form[0]));
					$tipos_colunas->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					$tipos_colunas->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
					
					$daoFactory->beginTransaction();
					$resultDao = $daoFactory->getTipos_colunasDao()->update($tipos_colunas);

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
						$where = "tipos_colunas.id = " . $lines[$i];
						
						$resultDao = $daoFactory->getTipos_colunasDao()->delete($where);
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
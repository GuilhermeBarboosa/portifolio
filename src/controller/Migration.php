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
	$daoFactory->beginTransaction();
	$file = fopen($_DOCUMENT_ROOT . "/_dev/doc/migration.txt", "r");
	$result = true;
	while(!feof($file)) {
		$tipos_menus = new model\Tipos_menus();
		$tipos_menus->setTipo_menu(fgets($line));
		$tipos_menus->setCadastrado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
		$tipos_menus->setModificado(date("Y-m-d H:i:s", (time() - 3600 * 3)));
		$resultDao = $daoFactory->getTipos_menusDao()->create($tipos_menus);
		$result = !$result ? false : (!$resultDao ? false : true);
    }
	fclose($file);
	if ($result) {
		$daoFactory->commit();			
	} else {							
		$daoFactory->rollback();
	}
	$daoFactory->close();

?>
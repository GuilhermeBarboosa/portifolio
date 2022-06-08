<?php 

	/**
	 * Generated by Getz Framework 
	 *
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz
	 */
	 
	/*
	 * SPL
	 */	 	
	spl_autoload_register(function ($class) {
		require_once("../../" . $class . ".php");
	});	 
			
	use lib\getz;
			
	$table = "paginas";
			
	/*
	 * $fields = array("field" => "type");
	 *
	 * types: string16, string32, string64,
	 * integer, double,
	 * date, datetime, time, new, now,
	 * email, cpf, cnpj, cep, phone, cellphone,
	 * photo, photoWithPosition, position, upload, order, link
	 */ 
	$fields = array("id" => "integer",
			"pagina" => "string32",
			"conteudo" => "string64",
			"palavras_chaves" => "string32",
			"cadastrado" => "new",
			"modificado" => "now"
	);
				
	/*
	 *$fk = array("table" => "field");
	 */
	$fk = array("situacoes_registros" => "situacao_registro"
	);
			
	/*
	 * $fkFields = array("field" => "type");
	 *
	 * types: session, base, standard, advanced, autocomplete
	 */ 
	$fkFields = array("situacoes_registros" => "standard"
	);
				
	// Set the table if this screen call another
	$call = "";
	
	// Set the column for answer after the call
	$answer = "";

	/*
	 * Builder
	 */
	$builder = new getz\Builder();
	$builder->model($table, $fields, $fk);
	$builder->view($table, $fields, $fk, $fkFields, $call);
	$builder->controller($table, $fields, $fk, $fkFields, $answer);
	$builder->dao($table, $fields, $fk);	 
	$builder->handler($table, $fields, $fk);
	$builder->response($table, $fields, $fk);
	$builder->daoFactory($table, $fields, $fk);
				
?>
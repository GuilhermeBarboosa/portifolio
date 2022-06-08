<?php
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz 
	 */
	 
	namespace src\model; 
	
	use src\model;
	
	class PaginasDao {
	
		private $connection;
		
		/*
		 * Constant variables
		 */
		private $create = "INSERT INTO paginas (
				pagina
				, conteudo
				, palavras_chaves
				, cadastrado
				, modificado
				, situacao_registro
				) VALUES";
				
		public $read = 
				"paginas.id AS \"paginas.id\"
				, paginas.pagina AS \"paginas.pagina\"
				, paginas.conteudo AS \"paginas.conteudo\"
				, paginas.palavras_chaves AS \"paginas.palavras_chaves\"
				, paginas.cadastrado AS \"paginas.cadastrado\"
				, paginas.modificado AS \"paginas.modificado\"
				, paginas.situacao_registro AS \"paginas.situacao_registro\"
				";
				
		private $update = "UPDATE paginas SET";
		private $delete = "DELETE FROM paginas";
		
		public $from = "paginas paginas";
		
		/*
		 * Parameters
		 */
		private $where;
		private $order;
		
		// Dynamic query
		private $sql;
		
		// Controller response
		private $response;	
		
		/**
		 * @param {Object} connection
		 */
		public function __construct($connection) {
			$this->connection = $connection;
		}

		/**
		 * @param {Paginas}paginas
		 */
		public function setCreate($paginas) {		
			$this->sql = $this->create . " (\"" . 
					$paginas->getPagina() .
					"\", \"" . $paginas->getConteudo() .
					"\", \"" . $paginas->getPalavras_chaves() .
					"\", \"" . $paginas->getCadastrado() .
					"\", \"" . $paginas->getModificado() .
					"\", \"" . $paginas->getSituacao_registro() .
					"\")";
		}
		
		/**
		 * @return {String}
		 */
		public function getCreate() {
			return $this->sql;
		}	
		
		/**
		 * @param {String} where
		 * @param {String} order
		 */
		public function setRead($where, $order) {
			$situacoes_registrosDao = new model\Situacoes_registrosDao($this->connection);
			
			$this->setWhere($where);
			$this->setOrder($order);
			
			$this->sql = "SELECT " . $this->read . ", " . $situacoes_registrosDao->read . 
					" FROM " . $this->getFrom() .", " . $situacoes_registrosDao->from . 
					($this->getWhere() == "" ? " WHERE paginas.situacao_registro = situacoes_registros.id" : $this->getWhere()) . 
					" AND paginas.situacao_registro = situacoes_registros.id" . $this->getOrder();
		}
		
		/**
		 * @return {String}
		 */
		public function getRead() {
			return $this->sql;
		}
		
		/**
		 * @param {Paginas}paginas  
		 * @param {String} where
		 */
		public function setUpdate($paginas, $where) {
			$this->setWhere($where);
			
			$this->sql = $this->update . 
					" id = \"" . $paginas->getId() . 
					"\", pagina = \"" . $paginas->getPagina() . 
					"\", conteudo = \"" . $paginas->getConteudo() . 
					"\", palavras_chaves = \"" . $paginas->getPalavras_chaves() . 
					"\", modificado = \"" . $paginas->getModificado() . 
					"\", situacao_registro = \"" . $paginas->getSituacao_registro() . 
					"\"" . $this->getWhere();
		}
		
		/**
		 * @return {String}
		 */
		public function getUpdate() {
			return $this->sql;
		}
		
		/**
		 * @param {String} where
		 */
		public function setDelete($where) {	
			$this->setWhere($where);
			
			$this->sql = $this->delete . $this->getWhere();
		}
		
		/**
		 * @return {String}
		 */
		public function getDelete() {
			return $this->sql;
		}
		
		/**
		 * @return {String}
		 */
		public function getFrom() {
			return $this->from;
		}
		
		/**
		 * @param {String} where
		 */
		public function setWhere($where) {
			if ($where != "")
				$this->where = " WHERE " . $where;
			else
				$this->where = "";
		}
		
		/**
		 * @return {String}
		 */
		public function getWhere() {
			return $this->where;
		}
		
		/**
		 * @param {String} order
		 */
		public function setOrder($order) {
			if ($order != "")
				$this->order = " ORDER BY " . $order;
			else
				$this->order = "";
		}
		
		/**
		 * @return {String}
		 */
		public function getOrder() {
			return $this->order;
		}
		
		/**
		 * @param {Integer} line
		 * @param column String
		 * @param value String
		 */
		private function setResponse($line, $column, $value) {
			$this->response[$line][$column] = $value;
		}

		/**
		 * @return {Array}
		 */
		private function getResponse() {
			return $this->response;
		}

		/**
		 * @param {String} where
		 */
		private function setSize($where) {
			$this->setWhere($where);
			
			$result = $this->connection->execute(
					"SELECT count(1) AS \"paginas.size\" from paginas" . $this->getWhere());

			while ($row = $result->fetch_assoc()) {		
				$this->setResponse(0, "paginas.size", $row["paginas.size"]);
				
				$pages = ceil($row["paginas.size"] / $this->connection->getItensPerPage());
				
				$this->setResponse(0, "paginas.page", $this->connection->getPosition());
				$this->setResponse(0, "paginas.pages", $pages);
				
				$pagination = "<select id='gz-select-pagination' onchange='goPage();'>";
				
				for ($i = 1; $i <= $pages; $i++) {
					if ($i == $this->connection->getPosition())
						$pagination .= "<option value='" . $i . "' selected>" . $i . "</option>";
					else
						$pagination .= "<option value='" . $i . "'>" . $i . "</option>";
				}	

				$pagination .= "</select>";
						
				$this->setResponse(0, "paginas.pagination", $pagination);
			}

			$this->connection->free($result);
		}
		
		/**
		 * @param {Integer} line
		 */
		public function setDivLine($line) {
			$this->setResponse($line - 1, "@_START_LINE_TWO", modelStartLine($line, 2));
			$this->setResponse($line - 1, "@_END_LINE_TWO", modelEndLine($line, 2));

			$this->setResponse($line - 1, "@_START_LINE_THREE", modelStartLine($line, 3));
			$this->setResponse($line - 1, "@_END_LINE_THREE", modelEndLine($line, 3));
			
			$this->setResponse($line - 1, "@_START_LINE_FOUR", modelStartLine($line, 4));
			$this->setResponse($line - 1, "@_END_LINE_FOUR", modelEndLine($line, 4));
		}
		
		/**
		 * @param {Integer} line
		 */
		public function checkDivLine($line) {
			if (modelCheckEndLine($line, 2) != "")
				$this->setResponse($line - 1, "@_END_LINE_TWO", modelCheckEndLine($line, 2));
			
			if (modelCheckEndLine($line, 3) != "")
				$this->setResponse($line - 1, "@_END_LINE_THREE", modelCheckEndLine($line, 3));		

			if (modelCheckEndLine($line, 4) != "")
				$this->setResponse($line - 1, "@_END_LINE_FOUR", modelCheckEndLine($line, 4));			
		}	

		/**
		 * @param {String} log
		 */
		private function setLog($log) {
			$this->setResponse(0, "log", $log);
		}
		
		/**
		 * @param {Paginas} paginas 
		 * @return {Boolean}
		 */
		public function create($paginas) {
			$result = "";

			$this->setCreate($paginas);
			$result = $this->connection->execute($this->getCreate());
			
			return $result;
		}

		/**
		 * @param {String} where
		 * @param {String} order
		 * @param {Boolean} wp
		 * @param {Array}
		 */
		public function read($where, $order, $wp) {
			$line = 0;

			$this->setRead($where, $order);
			$result = $this->connection->execute($this->getRead());

			while ($row = $result->fetch_assoc()) {
				$this->setResponse($line, "paginas.id", $row["paginas.id"]);
				$this->setResponse($line, "paginas.pagina", $row["paginas.pagina"]);
				$this->setResponse($line, "paginas.pagina.format.json", modelDoubleQuotesJson($row["paginas.pagina"]));
				$this->setResponse($line, "paginas.pagina.format", modelDoubleQuotes($row["paginas.pagina"]));
				$this->setResponse($line, "paginas.pagina.view", addLine($row["paginas.pagina"]));
				$this->setResponse($line, "paginas.conteudo", $row["paginas.conteudo"]);
				$this->setResponse($line, "paginas.conteudo.format", modelDoubleQuotes(modelTextArea($row["paginas.conteudo"])));
				$this->setResponse($line, "paginas.palavras_chaves", $row["paginas.palavras_chaves"]);
				$this->setResponse($line, "paginas.palavras_chaves.format.json", modelDoubleQuotesJson($row["paginas.palavras_chaves"]));
				$this->setResponse($line, "paginas.cadastrado", modelDateTime($row["paginas.cadastrado"]));
				$this->setResponse($line, "paginas.modificado", modelDateTime($row["paginas.modificado"]));
				$this->setResponse($line, "paginas.situacao_registro", $row["paginas.situacao_registro"]);
				$this->setResponse($line, "situacoes_registros.situacao_registro", $row["situacoes_registros.situacao_registro"]);
			
				$this->setResponse($line, "paginas.line", $line);
			
				$line++;
				
				if ($wp)
					$this->setDivLine($line);
			}

			$this->connection->free($result);
			
			if ($wp && $line > 0) {
				$this->checkDivLine($line);
				
				$this->setSize($where);
			}

			return $this->getResponse();
		}

		/**
		 * @param {Paginas} paginas 
		 * @return {Boolean}
		 */
		public function update($paginas) {
			$result = "";
			
			$this->setUpdate($paginas, "paginas.id = " . $paginas->getId());
			$result = $this->connection->execute($this->getUpdate());

			return $result;
		}

		/**
		 * @param {String} where
		 * @return {Boolean}
		 */
		public function delete($where) {
			$result = "";
			
			$this->setDelete($where);
			$result = $this->connection->execute($this->getDelete());

			return $result;
		}
		
		/**
		 * @param {Integer} selected
		 * @param {String} order
		 * @return {Array}
		 */
		public function combo($selected, $order) {
			$size = 0;

			$this->setRead("", $order);
			$result = $this->connection->execute($this->getRead());

			while ($row = $result->fetch_assoc()) {
				$this->setResponse($size, "paginas.id", $row["paginas.id"]);
				$this->setResponse($size, "paginas.pagina", $row["paginas.pagina"]);
			
				if ($row["paginas.id"] == $selected)
					$this->setResponse($size, "paginas.selected", "selected");
				else
					$this->setResponse($size, "paginas.selected", "");
					
				$size++;
			}
			
			$this->connection->free($result);
			
			$this->setResponse(0, "size", $size);

			return $this->getResponse();
		}
		
		/**
		 * @param {String} where
		 * @return {Array}
		 */
		public function comboScr($where) {
			$size = 0;

			$this->setRead($where, "");
			$result = $this->connection->execute($this->getRead());

			while ($row = $result->fetch_assoc()) {
				$this->setResponse($size, "paginas.id", $row["paginas.id"]);
				$this->setResponse($size, "paginas.pagina", $row["paginas.pagina"]);
				$this->setResponse($size, "paginas.selected", "selected");
					
				$size++;
			}
			
			$this->connection->free($result);
			
			$this->setResponse(0, "size", $size);

			return $this->getResponse();
		}

	}

?>
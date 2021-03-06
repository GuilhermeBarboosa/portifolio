<?php
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz 
	 */
	 
	namespace src\model; 
	
	use src\model;
	
	class Tipos_noticiasDao {
	
		private $connection;
		
		/*
		 * Constant variables
		 */
		private $create = "INSERT INTO tipos_noticias (
				tipo_noticia
				, cadastrado
				, modificado
				, cor
				, situacao_registro
				) VALUES";
				
		public $read = 
				"tipos_noticias.id AS \"tipos_noticias.id\"
				, tipos_noticias.tipo_noticia AS \"tipos_noticias.tipo_noticia\"
				, tipos_noticias.cadastrado AS \"tipos_noticias.cadastrado\"
				, tipos_noticias.modificado AS \"tipos_noticias.modificado\"
				, tipos_noticias.cor AS \"tipos_noticias.cor\"
				, tipos_noticias.situacao_registro AS \"tipos_noticias.situacao_registro\"
				";
				
		private $update = "UPDATE tipos_noticias SET";
		private $delete = "DELETE FROM tipos_noticias";
		
		public $from = "tipos_noticias tipos_noticias";
		
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
		 * @param {Tipos_noticias}tipos_noticias
		 */
		public function setCreate($tipos_noticias) {		
			$this->sql = $this->create . " (\"" . 
					$tipos_noticias->getTipo_noticia() .
					"\", \"" . $tipos_noticias->getCadastrado() .
					"\", \"" . $tipos_noticias->getModificado() .
					"\", \"" . $tipos_noticias->getCor() .
					"\", \"" . $tipos_noticias->getSituacao_registro() .
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
			$coresDao = new model\CoresDao($this->connection);
			$situacoes_registrosDao = new model\Situacoes_registrosDao($this->connection);
			
			$this->setWhere($where);
			$this->setOrder($order);
			
			$this->sql = "SELECT " . $this->read . ", " . $coresDao->read . ", " . $situacoes_registrosDao->read . 
					" FROM " . $this->getFrom() .", " . $coresDao->from . ", " . $situacoes_registrosDao->from . 
					($this->getWhere() == "" ? " WHERE tipos_noticias.cor = cores.id AND tipos_noticias.situacao_registro = situacoes_registros.id" : $this->getWhere()) . 
					" AND tipos_noticias.cor = cores.id AND tipos_noticias.situacao_registro = situacoes_registros.id" . $this->getOrder();
		}
		
		/**
		 * @return {String}
		 */
		public function getRead() {
			return $this->sql;
		}
		
		/**
		 * @param {Tipos_noticias}tipos_noticias  
		 * @param {String} where
		 */
		public function setUpdate($tipos_noticias, $where) {
			$this->setWhere($where);
			
			$this->sql = $this->update . 
					" id = \"" . $tipos_noticias->getId() . 
					"\", tipo_noticia = \"" . $tipos_noticias->getTipo_noticia() . 
					"\", modificado = \"" . $tipos_noticias->getModificado() . 
					"\", cor = \"" . $tipos_noticias->getCor() . 
					"\", situacao_registro = \"" . $tipos_noticias->getSituacao_registro() . 
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
					"SELECT count(1) AS \"tipos_noticias.size\" from tipos_noticias" . $this->getWhere());

			while ($row = $result->fetch_assoc()) {		
				$this->setResponse(0, "tipos_noticias.size", $row["tipos_noticias.size"]);
				
				$pages = ceil($row["tipos_noticias.size"] / $this->connection->getItensPerPage());
				
				$this->setResponse(0, "tipos_noticias.page", $this->connection->getPosition());
				$this->setResponse(0, "tipos_noticias.pages", $pages);
				
				$pagination = "<select id='gz-select-pagination' onchange='goPage();'>";
				
				for ($i = 1; $i <= $pages; $i++) {
					if ($i == $this->connection->getPosition())
						$pagination .= "<option value='" . $i . "' selected>" . $i . "</option>";
					else
						$pagination .= "<option value='" . $i . "'>" . $i . "</option>";
				}	

				$pagination .= "</select>";
						
				$this->setResponse(0, "tipos_noticias.pagination", $pagination);
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
		 * @param {Tipos_noticias} tipos_noticias 
		 * @return {Boolean}
		 */
		public function create($tipos_noticias) {
			$result = "";

			$this->setCreate($tipos_noticias);
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
				$this->setResponse($line, "tipos_noticias.id", $row["tipos_noticias.id"]);
				$this->setResponse($line, "tipos_noticias.tipo_noticia", $row["tipos_noticias.tipo_noticia"]);
				$this->setResponse($line, "tipos_noticias.tipo_noticia.format.json", modelDoubleQuotesJson($row["tipos_noticias.tipo_noticia"]));
				$this->setResponse($line, "tipos_noticias.tipo_noticia.format", modelDoubleQuotes($row["tipos_noticias.tipo_noticia"]));
				$this->setResponse($line, "tipos_noticias.tipo_noticia.view", addLine($row["tipos_noticias.tipo_noticia"]));
				$this->setResponse($line, "tipos_noticias.cadastrado", modelDateTime($row["tipos_noticias.cadastrado"]));
				$this->setResponse($line, "tipos_noticias.modificado", modelDateTime($row["tipos_noticias.modificado"]));
				$this->setResponse($line, "tipos_noticias.cor", $row["tipos_noticias.cor"]);
				$this->setResponse($line, "cores.cor", $row["cores.cor"]);
				$this->setResponse($line, "tipos_noticias.situacao_registro", $row["tipos_noticias.situacao_registro"]);
				$this->setResponse($line, "situacoes_registros.situacao_registro", $row["situacoes_registros.situacao_registro"]);
			
				$this->setResponse($line, "tipos_noticias.line", $line);
			
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
		 * @param {Tipos_noticias} tipos_noticias 
		 * @return {Boolean}
		 */
		public function update($tipos_noticias) {
			$result = "";
			
			$this->setUpdate($tipos_noticias, "tipos_noticias.id = " . $tipos_noticias->getId());
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
				$this->setResponse($size, "tipos_noticias.id", $row["tipos_noticias.id"]);
				$this->setResponse($size, "tipos_noticias.tipo_noticia", $row["tipos_noticias.tipo_noticia"]);
			
				if ($row["tipos_noticias.id"] == $selected)
					$this->setResponse($size, "tipos_noticias.selected", "selected");
				else
					$this->setResponse($size, "tipos_noticias.selected", "");
					
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
				$this->setResponse($size, "tipos_noticias.id", $row["tipos_noticias.id"]);
				$this->setResponse($size, "tipos_noticias.tipo_noticia", $row["tipos_noticias.tipo_noticia"]);
				$this->setResponse($size, "tipos_noticias.selected", "selected");
					
				$size++;
			}
			
			$this->connection->free($result);
			
			$this->setResponse(0, "size", $size);

			return $this->getResponse();
		}

	}

?>
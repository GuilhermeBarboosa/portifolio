<?php
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz 
	 */
	 
	namespace src\model; 
	
	use src\model;
	
	class Tipos_permissoesDao {
	
		private $connection;
		
		/*
		 * Constant variables
		 */
		private $create = "INSERT INTO tipos_permissoes (
				tipo_permissao
				, cadastrado
				, modificado
				, cor
				) VALUES";
				
		public $read = 
				"tipos_permissoes.id AS \"tipos_permissoes.id\"
				, tipos_permissoes.tipo_permissao AS \"tipos_permissoes.tipo_permissao\"
				, tipos_permissoes.cadastrado AS \"tipos_permissoes.cadastrado\"
				, tipos_permissoes.modificado AS \"tipos_permissoes.modificado\"
				, tipos_permissoes.cor AS \"tipos_permissoes.cor\"
				";
				
		private $update = "UPDATE tipos_permissoes SET";
		private $delete = "DELETE FROM tipos_permissoes";
		
		public $from = "tipos_permissoes tipos_permissoes";
		
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
		 * @param {Tipos_permissoes}tipos_permissoes
		 */
		public function setCreate($tipos_permissoes) {		
			$this->sql = $this->create . " (\"" . 
					$tipos_permissoes->getTipo_permissao() .
					"\", \"" . $tipos_permissoes->getCadastrado() .
					"\", \"" . $tipos_permissoes->getModificado() .
					"\", \"" . $tipos_permissoes->getCor() .
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
			
			$this->setWhere($where);
			$this->setOrder($order);
			
			$this->sql = "SELECT " . $this->read . ", " . $coresDao->read . 
					" FROM " . $this->getFrom() .", " . $coresDao->from . 
					($this->getWhere() == "" ? " WHERE tipos_permissoes.cor = cores.id" : $this->getWhere()) . 
					" AND tipos_permissoes.cor = cores.id" . $this->getOrder();
		}
		
		/**
		 * @return {String}
		 */
		public function getRead() {
			return $this->sql;
		}
		
		/**
		 * @param {Tipos_permissoes}tipos_permissoes  
		 * @param {String} where
		 */
		public function setUpdate($tipos_permissoes, $where) {
			$this->setWhere($where);
			
			$this->sql = $this->update . 
					" id = \"" . $tipos_permissoes->getId() . 
					"\", tipo_permissao = \"" . $tipos_permissoes->getTipo_permissao() . 
					"\", modificado = \"" . $tipos_permissoes->getModificado() . 
					"\", cor = \"" . $tipos_permissoes->getCor() . 
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
					"SELECT count(1) AS \"tipos_permissoes.size\" from tipos_permissoes" . $this->getWhere());

			while ($row = $result->fetch_assoc()) {		
				$this->setResponse(0, "tipos_permissoes.size", $row["tipos_permissoes.size"]);
				
				$pages = ceil($row["tipos_permissoes.size"] / $this->connection->getItensPerPage());
				
				$this->setResponse(0, "tipos_permissoes.page", $this->connection->getPosition());
				$this->setResponse(0, "tipos_permissoes.pages", $pages);
				
				$pagination = "<select id='gz-select-pagination' onchange='goPage();'>";
				
				for ($i = 1; $i <= $pages; $i++) {
					if ($i == $this->connection->getPosition())
						$pagination .= "<option value='" . $i . "' selected>" . $i . "</option>";
					else
						$pagination .= "<option value='" . $i . "'>" . $i . "</option>";
				}	

				$pagination .= "</select>";
						
				$this->setResponse(0, "tipos_permissoes.pagination", $pagination);
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
		 * @param {Tipos_permissoes} tipos_permissoes 
		 * @return {Boolean}
		 */
		public function create($tipos_permissoes) {
			$result = "";

			$this->setCreate($tipos_permissoes);
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
				$this->setResponse($line, "tipos_permissoes.id", $row["tipos_permissoes.id"]);
				$this->setResponse($line, "tipos_permissoes.tipo_permissao", $row["tipos_permissoes.tipo_permissao"]);
				$this->setResponse($line, "tipos_permissoes.tipo_permissao.format.json", modelDoubleQuotesJson($row["tipos_permissoes.tipo_permissao"]));
				$this->setResponse($line, "tipos_permissoes.tipo_permissao.format", modelDoubleQuotes($row["tipos_permissoes.tipo_permissao"]));
				$this->setResponse($line, "tipos_permissoes.tipo_permissao.view", addLine($row["tipos_permissoes.tipo_permissao"]));
				$this->setResponse($line, "tipos_permissoes.cadastrado", modelDateTime($row["tipos_permissoes.cadastrado"]));
				$this->setResponse($line, "tipos_permissoes.modificado", modelDateTime($row["tipos_permissoes.modificado"]));
				$this->setResponse($line, "tipos_permissoes.cor", $row["tipos_permissoes.cor"]);
				$this->setResponse($line, "cores.cor", $row["cores.cor"]);
			
				$this->setResponse($line, "tipos_permissoes.line", $line);
			
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
		 * @param {Tipos_permissoes} tipos_permissoes 
		 * @return {Boolean}
		 */
		public function update($tipos_permissoes) {
			$result = "";
			
			$this->setUpdate($tipos_permissoes, "tipos_permissoes.id = " . $tipos_permissoes->getId());
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
				$this->setResponse($size, "tipos_permissoes.id", $row["tipos_permissoes.id"]);
				$this->setResponse($size, "tipos_permissoes.tipo_permissao", $row["tipos_permissoes.tipo_permissao"]);
			
				if ($row["tipos_permissoes.id"] == $selected)
					$this->setResponse($size, "tipos_permissoes.selected", "selected");
				else
					$this->setResponse($size, "tipos_permissoes.selected", "");
					
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
				$this->setResponse($size, "tipos_permissoes.id", $row["tipos_permissoes.id"]);
				$this->setResponse($size, "tipos_permissoes.tipo_permissao", $row["tipos_permissoes.tipo_permissao"]);
				$this->setResponse($size, "tipos_permissoes.selected", "selected");
					
				$size++;
			}
			
			$this->connection->free($result);
			
			$this->setResponse(0, "size", $size);

			return $this->getResponse();
		}

	}

?>
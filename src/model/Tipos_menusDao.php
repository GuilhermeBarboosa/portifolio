<?php
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz 
	 */
	 
	namespace src\model; 
	
	class Tipos_menusDao {
	
		private $connection;
		
		/*
		 * Constant variables
		 */
		private $create = "INSERT INTO tipos_menus (
				tipo_menu
				, cadastrado
				, modificado
				) VALUES";
				
		public $read = 
				"tipos_menus.id AS \"tipos_menus.id\"
				, tipos_menus.tipo_menu AS \"tipos_menus.tipo_menu\"
				, tipos_menus.cadastrado AS \"tipos_menus.cadastrado\"
				, tipos_menus.modificado AS \"tipos_menus.modificado\"
				";
				
		private $update = "UPDATE tipos_menus SET";
		private $delete = "DELETE FROM tipos_menus";
		
		public $from = "tipos_menus tipos_menus";
		
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
		 * @param {Tipos_menus}tipos_menus
		 */
		public function setCreate($tipos_menus) {		
			$this->sql = $this->create . " (\"" . 
					$tipos_menus->getTipo_menu() .
					"\", \"" . $tipos_menus->getCadastrado() .
					"\", \"" . $tipos_menus->getModificado() .
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
			
			$this->setWhere($where);
			$this->setOrder($order);
			
			$this->sql = "SELECT " . $this->read . " FROM " . $this->getFrom() . 
					$this->getWhere() . "
				" . $this->getOrder();
		}
		
		/**
		 * @return {String}
		 */
		public function getRead() {
			return $this->sql;
		}
		
		/**
		 * @param {Tipos_menus}tipos_menus  
		 * @param {String} where
		 */
		public function setUpdate($tipos_menus, $where) {
			$this->setWhere($where);
			
			$this->sql = $this->update . 
					" id = \"" . $tipos_menus->getId() . 
					"\", tipo_menu = \"" . $tipos_menus->getTipo_menu() . 
					"\", modificado = \"" . $tipos_menus->getModificado() . 
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
					"SELECT count(1) AS \"tipos_menus.size\" from tipos_menus" . $this->getWhere());

			while ($row = $result->fetch_assoc()) {		
				$this->setResponse(0, "tipos_menus.size", $row["tipos_menus.size"]);
				
				$pages = ceil($row["tipos_menus.size"] / $this->connection->getItensPerPage());
				
				$this->setResponse(0, "tipos_menus.page", $this->connection->getPosition());
				$this->setResponse(0, "tipos_menus.pages", $pages);
				
				$pagination = "<select id='gz-select-pagination' onchange='goPage();'>";
				
				for ($i = 1; $i <= $pages; $i++) {
					if ($i == $this->connection->getPosition())
						$pagination .= "<option value='" . $i . "' selected>" . $i . "</option>";
					else
						$pagination .= "<option value='" . $i . "'>" . $i . "</option>";
				}	

				$pagination .= "</select>";
						
				$this->setResponse(0, "tipos_menus.pagination", $pagination);
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
		 * @param {Tipos_menus} tipos_menus 
		 * @return {Boolean}
		 */
		public function create($tipos_menus) {
			$result = "";

			$this->setCreate($tipos_menus);
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
				$this->setResponse($line, "tipos_menus.id", $row["tipos_menus.id"]);
				$this->setResponse($line, "tipos_menus.tipo_menu", $row["tipos_menus.tipo_menu"]);
				$this->setResponse($line, "tipos_menus.tipo_menu.format.json", modelDoubleQuotesJson($row["tipos_menus.tipo_menu"]));
				$this->setResponse($line, "tipos_menus.tipo_menu.format", modelDoubleQuotes($row["tipos_menus.tipo_menu"]));
				$this->setResponse($line, "tipos_menus.tipo_menu.view", addLine($row["tipos_menus.tipo_menu"]));
				$this->setResponse($line, "tipos_menus.cadastrado", modelDateTime($row["tipos_menus.cadastrado"]));
				$this->setResponse($line, "tipos_menus.modificado", modelDateTime($row["tipos_menus.modificado"]));
			
				$this->setResponse($line, "tipos_menus.line", $line);
			
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
		 * @param {Tipos_menus} tipos_menus 
		 * @return {Boolean}
		 */
		public function update($tipos_menus) {
			$result = "";
			
			$this->setUpdate($tipos_menus, "tipos_menus.id = " . $tipos_menus->getId());
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
				$this->setResponse($size, "tipos_menus.id", $row["tipos_menus.id"]);
				$this->setResponse($size, "tipos_menus.tipo_menu", $row["tipos_menus.tipo_menu"]);
			
				if ($row["tipos_menus.id"] == $selected)
					$this->setResponse($size, "tipos_menus.selected", "selected");
				else
					$this->setResponse($size, "tipos_menus.selected", "");
					
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
				$this->setResponse($size, "tipos_menus.id", $row["tipos_menus.id"]);
				$this->setResponse($size, "tipos_menus.tipo_menu", $row["tipos_menus.tipo_menu"]);
				$this->setResponse($size, "tipos_menus.selected", "selected");
					
				$size++;
			}
			
			$this->connection->free($result);
			
			$this->setResponse(0, "size", $size);

			return $this->getResponse();
		}

	}

?>
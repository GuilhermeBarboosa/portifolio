<?php
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz 
	 */
	 
	namespace src\model; 
	
	use src\model;
	
	class Perfil_telaDao {
	
		private $connection;
		
		/*
		 * Constant variables
		 */
		private $create = "INSERT INTO perfil_tela (
				cadastrado
				, modificado
				, perfil
				, tipo_permissao
				, tela
				) VALUES";
				
		public $read = 
				"perfil_tela.id AS \"perfil_tela.id\"
				, perfil_tela.cadastrado AS \"perfil_tela.cadastrado\"
				, perfil_tela.modificado AS \"perfil_tela.modificado\"
				, perfil_tela.perfil AS \"perfil_tela.perfil\"
				, perfil_tela.tipo_permissao AS \"perfil_tela.tipo_permissao\"
				, perfil_tela.tela AS \"perfil_tela.tela\"
				";
				
		private $update = "UPDATE perfil_tela SET";
		private $delete = "DELETE FROM perfil_tela";
		
		public $from = "perfil_tela perfil_tela";
		
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
		 * @param {Perfil_tela}perfil_tela
		 */
		public function setCreate($perfil_tela) {		
			$this->sql = $this->create . " (\"" . 
					$perfil_tela->getCadastrado() .
					"\", \"" . $perfil_tela->getModificado() .
					"\", \"" . $perfil_tela->getPerfil() .
					"\", \"" . $perfil_tela->getTipo_permissao() .
					"\", \"" . $perfil_tela->getTela() .
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
			$perfisDao = new model\PerfisDao($this->connection);
			$tipos_permissoesDao = new model\Tipos_permissoesDao($this->connection);
			$telasDao = new model\TelasDao($this->connection);
			
			$this->setWhere($where);
			$this->setOrder($order);
			
			$this->sql = "SELECT " . $this->read . ", " . $perfisDao->read . ", " . $tipos_permissoesDao->read . ", " . $telasDao->read . 
					" FROM " . $this->getFrom() .", " . $perfisDao->from . ", " . $tipos_permissoesDao->from . ", " . $telasDao->from . 
					($this->getWhere() == "" ? " WHERE perfil_tela.perfil = perfis.id AND perfil_tela.tipo_permissao = tipos_permissoes.id AND perfil_tela.tela = telas.id" : $this->getWhere()) . 
					" AND perfil_tela.perfil = perfis.id AND perfil_tela.tipo_permissao = tipos_permissoes.id AND perfil_tela.tela = telas.id" . $this->getOrder();
		}
		
		/**
		 * @return {String}
		 */
		public function getRead() {
			return $this->sql;
		}
		
		/**
		 * @param {Perfil_tela}perfil_tela  
		 * @param {String} where
		 */
		public function setUpdate($perfil_tela, $where) {
			$this->setWhere($where);
			
			$this->sql = $this->update . 
					" id = \"" . $perfil_tela->getId() . 
					"\", modificado = \"" . $perfil_tela->getModificado() . 
					"\", perfil = \"" . $perfil_tela->getPerfil() . 
					"\", tipo_permissao = \"" . $perfil_tela->getTipo_permissao() . 
					"\", tela = \"" . $perfil_tela->getTela() . 
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
					"SELECT count(1) AS \"perfil_tela.size\" from perfil_tela" . $this->getWhere());

			while ($row = $result->fetch_assoc()) {		
				$this->setResponse(0, "perfil_tela.size", $row["perfil_tela.size"]);
				
				$pages = ceil($row["perfil_tela.size"] / $this->connection->getItensPerPage());
				
				$this->setResponse(0, "perfil_tela.page", $this->connection->getPosition());
				$this->setResponse(0, "perfil_tela.pages", $pages);
				
				$pagination = "<select id='gz-select-pagination' onchange='goPage();'>";
				
				for ($i = 1; $i <= $pages; $i++) {
					if ($i == $this->connection->getPosition())
						$pagination .= "<option value='" . $i . "' selected>" . $i . "</option>";
					else
						$pagination .= "<option value='" . $i . "'>" . $i . "</option>";
				}	

				$pagination .= "</select>";
						
				$this->setResponse(0, "perfil_tela.pagination", $pagination);
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
		 * @param {Perfil_tela} perfil_tela 
		 * @return {Boolean}
		 */
		public function create($perfil_tela) {
			$result = "";

			$this->setCreate($perfil_tela);
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
				$this->setResponse($line, "perfil_tela.id", $row["perfil_tela.id"]);
				$this->setResponse($line, "perfil_tela.cadastrado", modelDateTime($row["perfil_tela.cadastrado"]));
				$this->setResponse($line, "perfil_tela.modificado", modelDateTime($row["perfil_tela.modificado"]));
				$this->setResponse($line, "perfil_tela.perfil", $row["perfil_tela.perfil"]);
				$this->setResponse($line, "perfis.perfil", $row["perfis.perfil"]);
				$this->setResponse($line, "perfil_tela.tipo_permissao", $row["perfil_tela.tipo_permissao"]);
				$this->setResponse($line, "tipos_permissoes.tipo_permissao", $row["tipos_permissoes.tipo_permissao"]);
				$this->setResponse($line, "perfil_tela.tela", $row["perfil_tela.tela"]);
				$this->setResponse($line, "telas.tela", $row["telas.tela"]);
			
				$this->setResponse($line, "perfil_tela.line", $line);
			
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
		 * @param {Perfil_tela} perfil_tela 
		 * @return {Boolean}
		 */
		public function update($perfil_tela) {
			$result = "";
			
			$this->setUpdate($perfil_tela, "perfil_tela.id = " . $perfil_tela->getId());
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
				$this->setResponse($size, "perfil_tela.id", $row["perfil_tela.id"]);
				$this->setResponse($size, "perfil_tela.cadastrado", $row["perfil_tela.cadastrado"]);
			
				if ($row["perfil_tela.id"] == $selected)
					$this->setResponse($size, "perfil_tela.selected", "selected");
				else
					$this->setResponse($size, "perfil_tela.selected", "");
					
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
				$this->setResponse($size, "perfil_tela.id", $row["perfil_tela.id"]);
				$this->setResponse($size, "perfil_tela.cadastrado", $row["perfil_tela.cadastrado"]);
				$this->setResponse($size, "perfil_tela.selected", "selected");
					
				$size++;
			}
			
			$this->connection->free($result);
			
			$this->setResponse(0, "size", $size);

			return $this->getResponse();
		}

	}

?>
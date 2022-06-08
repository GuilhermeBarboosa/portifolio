<?php
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz 
	 */
	 
	namespace src\model; 
	
	use src\model;
	
	class Elemento_conteudosDao {
	
		private $connection;
		
		/*
		 * Constant variables
		 */
		private $create = "INSERT INTO elemento_conteudos (
				elemento_conteudo
				, ordem
				, cadastrado
				, modificado
				, elemento
				, situacao_registro
				, tipo_alinhamento_horizontal
				, tipo_alinhamento_vertical
				) VALUES";
				
		public $read = 
				"elemento_conteudos.id AS \"elemento_conteudos.id\"
				, elemento_conteudos.elemento_conteudo AS \"elemento_conteudos.elemento_conteudo\"
				, elemento_conteudos.ordem AS \"elemento_conteudos.ordem\"
				, elemento_conteudos.cadastrado AS \"elemento_conteudos.cadastrado\"
				, elemento_conteudos.modificado AS \"elemento_conteudos.modificado\"
				, elemento_conteudos.elemento AS \"elemento_conteudos.elemento\"
				, elemento_conteudos.situacao_registro AS \"elemento_conteudos.situacao_registro\"
				, elemento_conteudos.tipo_alinhamento_horizontal AS \"elemento_conteudos.tipo_alinhamento_horizontal\"
				, elemento_conteudos.tipo_alinhamento_vertical AS \"elemento_conteudos.tipo_alinhamento_vertical\"
				";
				
		private $update = "UPDATE elemento_conteudos SET";
		private $delete = "DELETE FROM elemento_conteudos";
		
		public $from = "elemento_conteudos elemento_conteudos";
		
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
		 * @param {Elemento_conteudos}elemento_conteudos
		 */
		public function setCreate($elemento_conteudos) {		
			$this->sql = $this->create . " (\"" . 
					$elemento_conteudos->getElemento_conteudo() .
					"\", \"" . $elemento_conteudos->getOrdem() .
					"\", \"" . $elemento_conteudos->getCadastrado() .
					"\", \"" . $elemento_conteudos->getModificado() .
					"\", \"" . $elemento_conteudos->getElemento() .
					"\", \"" . $elemento_conteudos->getSituacao_registro() .
					"\", \"" . $elemento_conteudos->getTipo_alinhamento_horizontal() .
					"\", \"" . $elemento_conteudos->getTipo_alinhamento_vertical() .
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
			$elementosDao = new model\ElementosDao($this->connection);
			$situacoes_registrosDao = new model\Situacoes_registrosDao($this->connection);
			$tipos_alinhamentos_horizontaisDao = new model\Tipos_alinhamentos_horizontaisDao($this->connection);
			$tipos_alinhamentos_verticaisDao = new model\Tipos_alinhamentos_verticaisDao($this->connection);
			
			$this->setWhere($where);
			$this->setOrder($order);
			
			$this->sql = "SELECT " . $this->read . ", " . $elementosDao->read . ", " . $situacoes_registrosDao->read . ", " . $tipos_alinhamentos_horizontaisDao->read . ", " . $tipos_alinhamentos_verticaisDao->read . 
					" FROM " . $this->getFrom() .", " . $elementosDao->from . ", " . $situacoes_registrosDao->from . ", " . $tipos_alinhamentos_horizontaisDao->from . ", " . $tipos_alinhamentos_verticaisDao->from . 
					($this->getWhere() == "" ? " WHERE elemento_conteudos.elemento = elementos.id AND elemento_conteudos.situacao_registro = situacoes_registros.id AND elemento_conteudos.tipo_alinhamento_horizontal = tipos_alinhamentos_horizontais.id AND elemento_conteudos.tipo_alinhamento_vertical = tipos_alinhamentos_verticais.id" : $this->getWhere()) . 
					" AND elemento_conteudos.elemento = elementos.id AND elemento_conteudos.situacao_registro = situacoes_registros.id AND elemento_conteudos.tipo_alinhamento_horizontal = tipos_alinhamentos_horizontais.id AND elemento_conteudos.tipo_alinhamento_vertical = tipos_alinhamentos_verticais.id" . $this->getOrder();
		}
		
		/**
		 * @return {String}
		 */
		public function getRead() {
			return $this->sql;
		}
		
		/**
		 * @param {Elemento_conteudos}elemento_conteudos  
		 * @param {String} where
		 */
		public function setUpdate($elemento_conteudos, $where) {
			$this->setWhere($where);
			
			$this->sql = $this->update . 
					" id = \"" . $elemento_conteudos->getId() . 
					"\", elemento_conteudo = \"" . $elemento_conteudos->getElemento_conteudo() . 
					"\", ordem = \"" . $elemento_conteudos->getOrdem() . 
					"\", modificado = \"" . $elemento_conteudos->getModificado() . 
					"\", elemento = \"" . $elemento_conteudos->getElemento() . 
					"\", situacao_registro = \"" . $elemento_conteudos->getSituacao_registro() . 
					"\", tipo_alinhamento_horizontal = \"" . $elemento_conteudos->getTipo_alinhamento_horizontal() . 
					"\", tipo_alinhamento_vertical = \"" . $elemento_conteudos->getTipo_alinhamento_vertical() . 
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
					"SELECT count(1) AS \"elemento_conteudos.size\" from elemento_conteudos" . $this->getWhere());

			while ($row = $result->fetch_assoc()) {		
				$this->setResponse(0, "elemento_conteudos.size", $row["elemento_conteudos.size"]);
				
				$pages = ceil($row["elemento_conteudos.size"] / $this->connection->getItensPerPage());
				
				$this->setResponse(0, "elemento_conteudos.page", $this->connection->getPosition());
				$this->setResponse(0, "elemento_conteudos.pages", $pages);
				
				$pagination = "<select id='gz-select-pagination' onchange='goPage();'>";
				
				for ($i = 1; $i <= $pages; $i++) {
					if ($i == $this->connection->getPosition())
						$pagination .= "<option value='" . $i . "' selected>" . $i . "</option>";
					else
						$pagination .= "<option value='" . $i . "'>" . $i . "</option>";
				}	

				$pagination .= "</select>";
						
				$this->setResponse(0, "elemento_conteudos.pagination", $pagination);
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
		 * @param {Elemento_conteudos} elemento_conteudos 
		 * @return {Boolean}
		 */
		public function create($elemento_conteudos) {
			$result = "";

			$this->setCreate($elemento_conteudos);
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
				$this->setResponse($line, "elemento_conteudos.id", $row["elemento_conteudos.id"]);
				$this->setResponse($line, "elemento_conteudos.elemento_conteudo", $row["elemento_conteudos.elemento_conteudo"]);
				$this->setResponse($line, "elemento_conteudos.elemento_conteudo.format", modelDoubleQuotes(modelTextArea($row["elemento_conteudos.elemento_conteudo"])));
				$this->setResponse($line, "elemento_conteudos.ordem", $row["elemento_conteudos.ordem"]);
				$this->setResponse($line, "elemento_conteudos.cadastrado", modelDateTime($row["elemento_conteudos.cadastrado"]));
				$this->setResponse($line, "elemento_conteudos.modificado", modelDateTime($row["elemento_conteudos.modificado"]));
				$this->setResponse($line, "elemento_conteudos.elemento", $row["elemento_conteudos.elemento"]);
				$this->setResponse($line, "elementos.elemento", $row["elementos.elemento"]);
				$this->setResponse($line, "elemento_conteudos.situacao_registro", $row["elemento_conteudos.situacao_registro"]);
				$this->setResponse($line, "situacoes_registros.situacao_registro", $row["situacoes_registros.situacao_registro"]);
				$this->setResponse($line, "elemento_conteudos.tipo_alinhamento_horizontal", $row["elemento_conteudos.tipo_alinhamento_horizontal"]);
				$this->setResponse($line, "tipos_alinhamentos_horizontais.tipo_alinhamento_horizontal", $row["tipos_alinhamentos_horizontais.tipo_alinhamento_horizontal"]);
				$this->setResponse($line, "elemento_conteudos.tipo_alinhamento_vertical", $row["elemento_conteudos.tipo_alinhamento_vertical"]);
				$this->setResponse($line, "tipos_alinhamentos_verticais.tipo_alinhamento_vertical", $row["tipos_alinhamentos_verticais.tipo_alinhamento_vertical"]);
			
				$this->setResponse($line, "elemento_conteudos.line", $line);
			
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
		 * @param {Elemento_conteudos} elemento_conteudos 
		 * @return {Boolean}
		 */
		public function update($elemento_conteudos) {
			$result = "";
			
			$this->setUpdate($elemento_conteudos, "elemento_conteudos.id = " . $elemento_conteudos->getId());
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
				$this->setResponse($size, "elemento_conteudos.id", $row["elemento_conteudos.id"]);
				$this->setResponse($size, "elemento_conteudos.elemento_conteudo", $row["elemento_conteudos.elemento_conteudo"]);
			
				if ($row["elemento_conteudos.id"] == $selected)
					$this->setResponse($size, "elemento_conteudos.selected", "selected");
				else
					$this->setResponse($size, "elemento_conteudos.selected", "");
					
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
				$this->setResponse($size, "elemento_conteudos.id", $row["elemento_conteudos.id"]);
				$this->setResponse($size, "elemento_conteudos.elemento_conteudo", $row["elemento_conteudos.elemento_conteudo"]);
				$this->setResponse($size, "elemento_conteudos.selected", "selected");
					
				$size++;
			}
			
			$this->connection->free($result);
			
			$this->setResponse(0, "size", $size);

			return $this->getResponse();
		}

	}

?>
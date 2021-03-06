<?php
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz 
	 */
	 
	namespace src\model; 
	
	use src\model;
	
	class SlidesDao {
	
		private $connection;
		
		/*
		 * Constant variables
		 */
		private $create = "INSERT INTO slides (
				slide
				, foto
				, link
				, ordem
				, cadastrado
				, modificado
				, situacao_registro
				) VALUES";
				
		public $read = 
				"slides.id AS \"slides.id\"
				, slides.slide AS \"slides.slide\"
				, slides.foto AS \"slides.foto\"
				, slides.link AS \"slides.link\"
				, slides.ordem AS \"slides.ordem\"
				, slides.cadastrado AS \"slides.cadastrado\"
				, slides.modificado AS \"slides.modificado\"
				, slides.situacao_registro AS \"slides.situacao_registro\"
				";
				
		private $update = "UPDATE slides SET";
		private $delete = "DELETE FROM slides";
		
		public $from = "slides slides";
		
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
		 * @param {Slides}slides
		 */
		public function setCreate($slides) {		
			$this->sql = $this->create . " (\"" . 
					$slides->getSlide() .
					"\", \"" . $slides->getFoto() .
					"\", \"" . $slides->getLink() .
					"\", \"" . $slides->getOrdem() .
					"\", \"" . $slides->getCadastrado() .
					"\", \"" . $slides->getModificado() .
					"\", \"" . $slides->getSituacao_registro() .
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
					($this->getWhere() == "" ? " WHERE slides.situacao_registro = situacoes_registros.id" : $this->getWhere()) . 
					" AND slides.situacao_registro = situacoes_registros.id" . $this->getOrder();
		}
		
		/**
		 * @return {String}
		 */
		public function getRead() {
			return $this->sql;
		}
		
		/**
		 * @param {Slides}slides  
		 * @param {String} where
		 */
		public function setUpdate($slides, $where) {
			$this->setWhere($where);
			
			$this->sql = $this->update . 
					" id = \"" . $slides->getId() . 
					"\", slide = \"" . $slides->getSlide() . 
					"\", foto = \"" . $slides->getFoto() . 
					"\", link = \"" . $slides->getLink() . 
					"\", ordem = \"" . $slides->getOrdem() . 
					"\", modificado = \"" . $slides->getModificado() . 
					"\", situacao_registro = \"" . $slides->getSituacao_registro() . 
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
					"SELECT count(1) AS \"slides.size\" from slides" . $this->getWhere());

			while ($row = $result->fetch_assoc()) {		
				$this->setResponse(0, "slides.size", $row["slides.size"]);
				
				$pages = ceil($row["slides.size"] / $this->connection->getItensPerPage());
				
				$this->setResponse(0, "slides.page", $this->connection->getPosition());
				$this->setResponse(0, "slides.pages", $pages);
				
				$pagination = "<select id='gz-select-pagination' onchange='goPage();'>";
				
				for ($i = 1; $i <= $pages; $i++) {
					if ($i == $this->connection->getPosition())
						$pagination .= "<option value='" . $i . "' selected>" . $i . "</option>";
					else
						$pagination .= "<option value='" . $i . "'>" . $i . "</option>";
				}	

				$pagination .= "</select>";
						
				$this->setResponse(0, "slides.pagination", $pagination);
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
		 * @param {Slides} slides 
		 * @return {Boolean}
		 */
		public function create($slides) {
			$result = "";

			$this->setCreate($slides);
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
				$this->setResponse($line, "slides.id", $row["slides.id"]);
				$this->setResponse($line, "slides.slide", $row["slides.slide"]);
				$this->setResponse($line, "slides.slide.format.json", modelDoubleQuotesJson($row["slides.slide"]));
				$this->setResponse($line, "slides.slide.format", modelDoubleQuotes($row["slides.slide"]));
				$this->setResponse($line, "slides.slide.view", addLine($row["slides.slide"]));
				$this->setResponse($line, "slides.foto", $row["slides.foto"]);
				$this->setResponse($line, "slides.link", $row["slides.link"]);
				$this->setResponse($line, "slides.link.format.json", modelDoubleQuotesJson($row["slides.link"]));
				if ($row["slides.link"] == "") {
					$this->setResponse($line, "slides.link.view", "");
				} else {
					if (substr($row["slides.link"], 0, 1) == "/") {
						$this->setResponse($line, "slides.link.view", "href=\"@_ROOT" . $row["slides.link"] . "\"");
					} else {
						$this->setResponse($line, "slides.link.view", "href=\"" . $row["slides.link"] . "\" target=\"_blank\"");
					}
				}
				$this->setResponse($line, "slides.ordem", $row["slides.ordem"]);
				$this->setResponse($line, "slides.cadastrado", modelDateTime($row["slides.cadastrado"]));
				$this->setResponse($line, "slides.modificado", modelDateTime($row["slides.modificado"]));
				$this->setResponse($line, "slides.situacao_registro", $row["slides.situacao_registro"]);
				$this->setResponse($line, "situacoes_registros.situacao_registro", $row["situacoes_registros.situacao_registro"]);
			
				$this->setResponse($line, "slides.line", $line);
			
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
		 * @param {Slides} slides 
		 * @return {Boolean}
		 */
		public function update($slides) {
			$result = "";
			
			$this->setUpdate($slides, "slides.id = " . $slides->getId());
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
				$this->setResponse($size, "slides.id", $row["slides.id"]);
				$this->setResponse($size, "slides.slide", $row["slides.slide"]);
			
				if ($row["slides.id"] == $selected)
					$this->setResponse($size, "slides.selected", "selected");
				else
					$this->setResponse($size, "slides.selected", "");
					
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
				$this->setResponse($size, "slides.id", $row["slides.id"]);
				$this->setResponse($size, "slides.slide", $row["slides.slide"]);
				$this->setResponse($size, "slides.selected", "selected");
					
				$size++;
			}
			
			$this->connection->free($result);
			
			$this->setResponse(0, "size", $size);

			return $this->getResponse();
		}

	}

?>
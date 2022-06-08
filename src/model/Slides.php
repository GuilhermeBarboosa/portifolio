<?php 
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz
	 */
	 
	namespace src\model; 

	class Slides {
			
		private $id;
		private $slide;
		private $foto;
		private $link;
		private $ordem;
		private $cadastrado;
		private $modificado;
		private $situacao_registro;
			
		public function __construct() { }
			
		public function setId($id) {
			$this->id = $id;
		}
		
		public function getId() {
			return $this->id;
		}
					
		public function setSlide($slide) {
			$this->slide = $slide;
		}
		
		public function getSlide() {
			return $this->slide;
		}
					
		public function setFoto($foto) {
			$this->foto = $foto;
		}
		
		public function getFoto() {
			return $this->foto;
		}
					
		public function setLink($link) {
			$this->link = $link;
		}
		
		public function getLink() {
			return $this->link;
		}
					
		public function setOrdem($ordem) {
			$this->ordem = $ordem;
		}
		
		public function getOrdem() {
			return $this->ordem;
		}
					
		public function setCadastrado($cadastrado) {
			$this->cadastrado = $cadastrado;
		}
		
		public function getCadastrado() {
			return $this->cadastrado;
		}
					
		public function setModificado($modificado) {
			$this->modificado = $modificado;
		}
		
		public function getModificado() {
			return $this->modificado;
		}
					
		public function setSituacao_registro($situacao_registro) {
			$this->situacao_registro = $situacao_registro;
		}
		
		public function getSituacao_registro() {
			return $this->situacao_registro;
		}
					
	}
	
?>
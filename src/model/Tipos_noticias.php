<?php 
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz
	 */
	 
	namespace src\model; 

	class Tipos_noticias {
			
		private $id;
		private $tipo_noticia;
		private $cadastrado;
		private $modificado;
		private $cor;
		private $situacao_registro;
			
		public function __construct() { }
			
		public function setId($id) {
			$this->id = $id;
		}
		
		public function getId() {
			return $this->id;
		}
					
		public function setTipo_noticia($tipo_noticia) {
			$this->tipo_noticia = $tipo_noticia;
		}
		
		public function getTipo_noticia() {
			return $this->tipo_noticia;
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
					
		public function setCor($cor) {
			$this->cor = $cor;
		}
		
		public function getCor() {
			return $this->cor;
		}
					
		public function setSituacao_registro($situacao_registro) {
			$this->situacao_registro = $situacao_registro;
		}
		
		public function getSituacao_registro() {
			return $this->situacao_registro;
		}
					
	}
	
?>
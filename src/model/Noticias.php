<?php 
			
	/**
	 * Generated by Getz Framework
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz
	 */
	 
	namespace src\model; 

	class Noticias {
			
		private $id;
		private $noticia;
		private $foto;
		private $legenda_foto;
		private $resumo;
		private $conteudo;
		private $palavras_chaves;
		private $cadastrado;
		private $modificado;
		private $usuario;
		private $situacao_registro;
		private $tipo_noticia;
			
		public function __construct() { }
			
		public function setId($id) {
			$this->id = $id;
		}
		
		public function getId() {
			return $this->id;
		}
					
		public function setNoticia($noticia) {
			$this->noticia = $noticia;
		}
		
		public function getNoticia() {
			return $this->noticia;
		}
					
		public function setFoto($foto) {
			$this->foto = $foto;
		}
		
		public function getFoto() {
			return $this->foto;
		}
					
		public function setLegenda_foto($legenda_foto) {
			$this->legenda_foto = $legenda_foto;
		}
		
		public function getLegenda_foto() {
			return $this->legenda_foto;
		}
					
		public function setResumo($resumo) {
			$this->resumo = $resumo;
		}
		
		public function getResumo() {
			return $this->resumo;
		}
					
		public function setConteudo($conteudo) {
			$this->conteudo = $conteudo;
		}
		
		public function getConteudo() {
			return $this->conteudo;
		}
					
		public function setPalavras_chaves($palavras_chaves) {
			$this->palavras_chaves = $palavras_chaves;
		}
		
		public function getPalavras_chaves() {
			return $this->palavras_chaves;
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
					
		public function setUsuario($usuario) {
			$this->usuario = $usuario;
		}
		
		public function getUsuario() {
			return $this->usuario;
		}
					
		public function setSituacao_registro($situacao_registro) {
			$this->situacao_registro = $situacao_registro;
		}
		
		public function getSituacao_registro() {
			return $this->situacao_registro;
		}
					
		public function setTipo_noticia($tipo_noticia) {
			$this->tipo_noticia = $tipo_noticia;
		}
		
		public function getTipo_noticia() {
			return $this->tipo_noticia;
		}
					
	}
	
?>
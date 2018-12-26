<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function timeline() {
		
		$this->validaAutenticacao();

		$tweet = Container::getModel('tweet');
		$tweet->__set('id_usuario',$_SESSION['id']);

		$usuario = Container::getModel('usuario');
		$usuario->__set('id', $_SESSION['id']);

		$this->view->qtdetweets = $usuario->getTotalTweets();
		$this->view->qtdeseguindo = $usuario->getTotalSeguindo();
		$this->view->qtdeseguidores = $usuario->getTotalSeguidores();

		$this->view->tweets = $tweet->getAll();

		$this->render('timeline');
	}

	public function tweet(){
		
		$this->validaAutenticacao();

		$tweet = Container::getModel('tweet');
		$tweet->__set('tweet', $_POST['tweet']);
		$tweet->__set('id_usuario', $_SESSION['id']);
		$tweet->salvar();
		\header("Location: /timeline");
	}

	public function removerTweet(){
		$this->validaAutenticacao();
		$tweet = Container::getModel('tweet');
		$tweet->__set('id' , $_GET['id_tweet']);
		$tweet->__set('id_usuario' , $_SESSION['id']);
		$tweet->deletar();
		\header("Location: /timeline");

	}

	public function quemSeguir(){
		$this->validaAutenticacao();
		$usuario = Container::getModel('usuario');
		$usuario->__set('id', $_SESSION['id']);

		$this->view->usuarios = array();
		$this->view->qtdetweets = $usuario->getTotalTweets();
		$this->view->qtdeseguindo = $usuario->getTotalSeguindo();
		$this->view->qtdeseguidores = $usuario->getTotalSeguidores();
		
		if(isset($_GET['pequisarPor']) && $_GET['pequisarPor'] !=''){
			$usuarios = Container::getModel('usuario');
			$usuarios->__set('nome', $_GET['pequisarPor']);	
			$usuarios->__set('id', $_SESSION['id']);
			$this->view->usuarios = $usuarios->getUsuarioporNome();
			
		}
		$this->render('quemSeguir');

	}
	public function acao(){
		$this->validaAutenticacao();

		print_r($_GET);

		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';		
		$id_usuario_seguindo = isset($_GET['id_user']) ? $_GET['id_user'] : '';
		
		$usuario = Container::getModel('usuario');

		$usuario->__set('id', $_SESSION['id']);

		if($acao == 'seguir'){
			$usuario->seguirUsuario($id_usuario_seguindo);
		}else if($acao == 'deixar_de_seguir'){
			$usuario->deixarSeguirUsuario($id_usuario_seguindo);
		}
		\header("Location: /quemSeguir");
	}

	public function validaAutenticacao(){
		session_start();
		if(!isset($_SESSION['id']) || $_SESSION['id'] != '' || !isset($_SESSION['nome']) || $_SESSION['nome'] != ''  ){
				
		}else{ 
			\header("Location: /?login=erro");
		}
	}

}

?>
<?php 
namespace App\Models;
use MF\Model\Model;

class Usuario extends Model{
    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($atributo)
    {
        return $this->$atributo; 
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function salvar(){
        $query = "insert into usuarios (nome,email,senha) values (:nome,:email,:senha)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',$this->__get('nome'));
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->bindValue(':senha',$this->__get('senha'));
        $stmt->execute();
        return $this;
    }

    public function validarCadastro(){
        $valido = true;

        if(strlen($this->__get('nome'))< 3){
            $valido = false;
        }
        if(strlen($this->__get('email'))< 3){
            $valido = false;
        }
        if(strlen($this->__get('senha'))< 3){
            $valido = false;
        }

        return $valido;
    }

    public function getUsuarioporEmail(){
        $valido = true;
        $query = "select email from usuarios where email = :email ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUsuarioporNome(){
        $query = "
        select 
            u.id, u.nome, u.email, (
                select
                    count(*)
                from
                    usuarios_seguidores as us
                where 
                    us.id_usuario = :id_usuario and us.id_usuario_seguido = u.id
            ) as seguindo_sn 
        from 
            usuarios as u
        where 
            u.nome like :nome and u.id != :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',"%". $this->__get('nome') . "%");
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function autenticar(){
        $query = "select id,nome,email from usuarios where email = :email and senha= :senha";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->bindValue(':senha',$this->__get('senha'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($usuario['id'] != '' && $usuario['nome'] != '' ){
            $this->__set('id', $usuario['id'] );
            $this->__set('nome', $usuario['nome'] );
        }

        return $this;
    }
    public function seguirUsuario($id_usuario_seguindo){
        $query = "insert into usuarios_seguidores (id_usuario, id_usuario_seguido) values(:id_usuario, :id_usuario_seguido)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->bindValue(':id_usuario_seguido',$id_usuario_seguindo);
        $stmt->execute();
        return true;
             
    }

    public function deixarSeguirUsuario($id_usuario_seguindo){
        $query = "delete from usuarios_seguidores where id_usuario = :id_usuario &&  id_usuario_seguido = :id_usuario_seguido";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->bindValue(':id_usuario_seguido',$id_usuario_seguindo);
        $stmt->execute();
        return true;
    }

    public function getTotalTweets(){
        $query = "select count(*) as total_tweets from tweets where id_usuario = :id_usuario ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();
        $qtde = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $qtde[0]['total_tweets'];
    }

    public function getTotalSeguindo(){
        $query = "select count(*) as total_seguind from usuarios_seguidores where id_usuario = :id_usuario ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();
        $qtde = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $qtde[0]['total_seguind'];
    }

    public function getTotalSeguidores(){
        $query = "select count(*) as total_seguind from usuarios_seguidores where  id_usuario_seguido = :id_usuario ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();
        $qtde = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $qtde[0]['total_seguind'];
    }

    
}

?>
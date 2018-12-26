"Twitter-clone" 
Para testar a aplicação basta subir o servidor na pasta public da aplicação. E fazer as alterações necessárias no arquivo "Connection.php" para o seu banco de dados. E criar as tabelas a seguir: 

CREATE TABLE  usuarios (
    id INT(11) NOT NULL PRIMARY KEY auto_increment,
    nome VARCHAR(100),
    email VARCHAR(150),
    senha VARCHAR(32)
);

CREATE TABLE tweets (
    id INT(11) NOT NULL PRIMARY KEY auto_increment,
    id_usuario INT(11) NOT NULL,
    tweet VARCHAR(140),
    data DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios_seguidores (
    id INT(11) NOT NULL PRIMARY KEY auto_increment,
    id_usuario INT(11) NOT NULL,
    id_usuario_seguido INT(11) NOT NULL,
);

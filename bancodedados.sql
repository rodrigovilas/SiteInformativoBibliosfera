create database if not exists bibliosfera;
use bibliosfera;

create table if not exists login(
id_user int auto_increment primary key,
usuario varchar(50) not null unique,
email varchar(255) not null unique,
senha varchar(255) not null 
);


create table if not exists msgcontato(
id_usuario int not null auto_increment primary key,
nome_contato varchar (100) not null,
email_contato varchar (200) not null,
mensagem_contato varchar(200) not null
);

create table if not exists comentarioLeitor(
id_leitor int not null auto_increment primary key,
nome_leitor varchar (100) not null,
email_elitor varchar (200) not null,
comentario_leitor varchar(200) not null,
nome_livro_leitor varchar(200) not null
);

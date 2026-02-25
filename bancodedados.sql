create database bibliosfera;
use bibliosfera;

create table login
(id_user int auto_increment primary key,
usuario varchar(50) not null unique,
email varchar(255) not null unique,
senha varchar(255) not null 
);


create table msgcontato(
id_usuario int not null auto_increment primary key,
nome_contato varchar (100) not null,
email_contato varchar (200) not null,
mensagem_contato varchar(200) not null
);

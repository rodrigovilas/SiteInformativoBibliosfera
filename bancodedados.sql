/*o banco não está terminado ainda!! ainda falta conectar com o php*/
drop database if exists bibliosfera;
create database if not exists bibliosfera;
use bibliosfera;

create table if not exists login(
id_usuario int auto_increment primary key,
usuario varchar (50) unique not null,
email varchar (255) unique not null,
senha varchar (200) not null);

create table if not exists autor(
id_autor int auto_increment primary key,
nome varchar(100) not null,
biografia varchar(2000));

create table if not exists livro(
id_livro int auto_increment primary key,
titulo varchar (200) not null,
descricao varchar(2000),
capa varchar (200));

create table if not exists livropraautor(
id_livro int,
id_autor int,
primary key (id_livro, id_autor),
foreign key (id_livro) references livro(id_livro),
foreign key (id_autor) references autor(id_autor));

create table if not exists genero(
id_genero int auto_increment primary key,
nome_genero varchar(100));

create table if not exists generopralivro(
id_livro int,
id_genero int,
primary key (id_livro, id_genero),
foreign key (id_livro) references livro(id_livro),
foreign key (id_genero) references genero(id_genero));

create table if not exists categoria(
id_categoria int auto_increment primary key,
nome varchar (100) unique not null,
descricao varchar(500));

create table if not exists resenha(
id_resenha int auto_increment primary key,
id_usuario int,
id_livro int,
nota decimal(2,1),
resenha varchar(2000),
data_resenha timestamp,
check (nota>=0 and nota<=10),
foreign key (id_usuario) references login(id_usuario),
foreign key (id_livro) references livro(id_livro));

create table if not exists categoriapralivro(
id_categoria int,
id_livro int,
primary key (id_categoria, id_livro),
foreign key (id_categoria) references categoria(id_categoria),
foreign key (id_livro) references livro(id_livro));

create table if not exists listausuario(
id_usuario int,
id_livro int,
progresso enum('Lendo','Pausado','Terminado'),
primary key (id_usuario, id_livro),
foreign key (id_usuario) references login(id_usuario),
foreign key (id_livro) references livro(id_livro));

/*o banco não está terminado ainda!! ainda falta conectar com o php*/
/*vsf rodrigo*/
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

create table if not exists tag(
id_tag int auto_increment primary key,
nome_tag varchar (100) unique not null,
descricao_tag varchar(500));

create table if not exists resenha(
id_resenha int auto_increment primary key,
id_usuario int,
id_livro int,
nota decimal(2,1),
resenha varchar(2000),
data_resenha timestamp default current_timestamp,
check (nota>=0 and nota<=10),
foreign key (id_usuario) references login(id_usuario),
foreign key (id_livro) references livro(id_livro));

create table if not exists tagpralivro(
id_tag int,
id_livro int,
primary key (id_tag, id_livro),
foreign key (id_tag) references tag(id_tag),
foreign key (id_livro) references livro(id_livro));

create table if not exists listausuario(
id_usuario int,
id_livro int,
progresso enum('Lendo','Pausado','Terminado','Largado') default null,
primary key (id_usuario, id_livro),
foreign key (id_usuario) references login(id_usuario),
foreign key (id_livro) references livro(id_livro));

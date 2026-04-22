/*criação do banco removida porque o Aiven já cria o defaultdb para nós*/

create table if not exists login(
id_usuario int auto_increment primary key,
usuario varchar (50) unique not null,
email varchar (255) unique not null,
senha varchar (200) not null,
nome varchar (100),
avatar varchar (255),
bio varchar (1000));

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

create table if not exists msgcontato(
id_msgcontato int auto_increment primary key,
nome_contato varchar(50) not null,
email_contato varchar(155) not null,
mensagem_contato text not null); 


/*----------------------------------------------inserts----------------------------------------------*/


/*As capas são registradas como o caminho pros arquivos das fotos, pelas pastas do projeto*/
/*EX: O livro de ID 1 vai ter a capa como 'SiteInformativoBibliosfera/capas/1.jpg'*/


/*Livros de Biografia*/
insert into livro (titulo, descricao, capa) values
('Einstein: Biorafia de um gênio imperfeito','blank1','SiteInformativoBibliosfera/capas/1.jpg'),
('Eu Sou Malala: A história da garota que defendeu o direito à educação e foi baleada pelo Talibã','blank2','SiteInformativoBibliosfera/capas/2.jpg'),
('Leonardo da Vinci','blank3','SiteInformativoBibliosfera/capas/3.jpg'),
('Nelson Mandela: longa caminhada até a liberdade','blank4','SiteInformativoBibliosfera/capas/4.jpg'),
('Marie Curie No País Da Ciência','blank5','SiteInformativoBibliosfera/capas/5.jpg'),
('O Diário de Anne Frank','blank6','SiteInformativoBibliosfera/capas/6.jpg');

/*Livros de Fantasia*/
insert into livro (titulo, descricao, capa) values
('A Biblioteca da Meia-Noite','blank7','SiteInformativoBibliosfera/capas/7.jpg'),
('A Bússola de Ouro','blank8','SiteInformativoBibliosfera/capas/8.jpg'),
('As Crônicas de Nárnia: O Leão, a Feiticeira e o Guarda-roupa','blank9','SiteInformativoBibliosfera/capas/.9jpg'),
('De sangue e cinzas','blank10','SiteInformativoBibliosfera/capas/.10jpg'),
('Nevernight: a sombra do corvo','blank11','SiteInformativoBibliosfera/capas/11.jpg'),
('O feiticeiro de Terramar','blank12','SiteInformativoBibliosfera/capas/12.jpg'),
('O Hobbit','blank13','SiteInformativoBibliosfera/capas/13.jpg'),
('O ladrão de raios','blank14','SiteInformativoBibliosfera/capas/14.jpg'),
('O Senhor dos Anéis','blank15','SiteInformativoBibliosfera/capas/15.jpg'),
('O Último Desejo','blank16','SiteInformativoBibliosfera/capas/16.jpg'),
('Quarta asa','blank17','SiteInformativoBibliosfera/capas/17.jpg'),
('Trono de Vidro','blank18','SiteInformativoBibliosfera/capas/18.jpg');

/*Livros de Ficção*/
('1984','blank19','SiteInformativoBibliosfera/capas/19.jpg'),
('A Empregada','blank20','SiteInformativoBibliosfera/capas/20.jpg'),
('A Filha dos Rios','blank21','SiteInformativoBibliosfera/capas/21.jpg'),
('A Guerra dos Tronos : As Crônicas de Gelo e Fogo, volume 1','blank22','SiteInformativoBibliosfera/capas/22.jpg'),
('','blank23','SiteInformativoBibliosfera/capas/23.jpg'),
('','blank24','SiteInformativoBibliosfera/capas/24.jpg'),
('','blank25','SiteInformativoBibliosfera/capas/25.jpg'),
('','blank26','SiteInformativoBibliosfera/capas/26.jpg'),
('','blank27','SiteInformativoBibliosfera/capas/27.jpg'),
('','blank28','SiteInformativoBibliosfera/capas/28.jpg');

/*select * from livro;*/

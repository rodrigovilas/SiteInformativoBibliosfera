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
/*(Não sei nem se a gente vai usar isso, mas mesmo assim)*/

/*As capas são registradas como o caminho pros arquivos das fotos, pelas pastas do projeto*/
/*EX: O livro de ID 1 vai ter a capa como 'SiteInformativoBibliosfera/capas/1.jpg'*/

/*OBS:Esses são apenas inserts iniciais, por isso são separados por categorias.*/
/*Futuramente, se formos continuar fazendo inserts por esse arquivo, vamos fazer sem separar.*/

/*Livros de Biografia*/
insert into livro (titulo, descricao, capa) values
('Einstein: Biorafia de um gênio imperfeito','TBA1','SiteInformativoBibliosfera/capas/1.jpg'),
('Eu Sou Malala: A história da garota que defendeu o direito à educação e foi baleada pelo Talibã','TBA2','SiteInformativoBibliosfera/capas/2.jpg'),
('Leonardo da Vinci','TBA3','SiteInformativoBibliosfera/capas/3.jpg'),
('Nelson Mandela: longa caminhada até a liberdade','TBA4','SiteInformativoBibliosfera/capas/4.jpg'),
('Marie Curie No País Da Ciência','TBA5','SiteInformativoBibliosfera/capas/5.jpg'),
('O Diário de Anne Frank','TBA6','SiteInformativoBibliosfera/capas/6.jpg');

/*Livros de Fantasia*/
insert into livro (titulo, descricao, capa) values
('A Biblioteca da Meia-Noite','TBA7','SiteInformativoBibliosfera/capas/7.jpg'),
('A Bússola de Ouro','TBA8','SiteInformativoBibliosfera/capas/8.jpg'),
('As Crônicas de Nárnia: O Leão, a Feiticeira e o Guarda-roupa','TBA9','SiteInformativoBibliosfera/capas/.9jpg'),
('De sangue e cinzas','TBA10','SiteInformativoBibliosfera/capas/.10jpg'),
('Nevernight: a sombra do corvo','TBA11','SiteInformativoBibliosfera/capas/11.jpg'),
('O feiticeiro de Terramar','TBA12','SiteInformativoBibliosfera/capas/12.jpg'),
('O Hobbit','TBA13','SiteInformativoBibliosfera/capas/13.jpg'),
('O ladrão de raios','TBA14','SiteInformativoBibliosfera/capas/14.jpg'),
('O Senhor dos Anéis','TBA15','SiteInformativoBibliosfera/capas/15.jpg'),
('O Último Desejo','TBA16','SiteInformativoBibliosfera/capas/16.jpg'),
('Quarta asa','TBA17','SiteInformativoBibliosfera/capas/17.jpg'),
('Trono de Vidro','TBA18','SiteInformativoBibliosfera/capas/18.jpg');

/*Livros de Ficção*/
insert into livro (titulo, descricao, capa) values
('1984','TBA19','SiteInformativoBibliosfera/capas/19.jpg'),
('A Empregada','TBA20','SiteInformativoBibliosfera/capas/20.jpg'),
('A Filha dos Rios','TBA21','SiteInformativoBibliosfera/capas/21.jpg'),
('A Guerra dos Tronos : As Crônicas de Gelo e Fogo, volume 1','TBA22','SiteInformativoBibliosfera/capas/22.jpg'),
('Admirável mundo novo','TBA23','SiteInformativoBibliosfera/capas/23.jpg'),
('Bird Box','TBA24','SiteInformativoBibliosfera/capas/24.jpg'),
('Fahrenheit 451','TBA25','SiteInformativoBibliosfera/capas/25.jpg'),
('Jogos Vorazes','TBA26','SiteInformativoBibliosfera/capas/26.jpg'),
('Tudo é rio','TBA27','SiteInformativoBibliosfera/capas/27.jpg'),
('Verity','TBA28','SiteInformativoBibliosfera/capas/28.jpg');

/*Livros de Ficção Científica*/
insert into livro (titulo, descricao, capa) values
('2001: Uma odisséia no espaço','TBA29','SiteInformativoBibliosfera/capas/29.jpg'),
('As Crônicas Marcianas','TBA30','SiteInformativoBibliosfera/capas/30.jpg'),
('Blade Runner: Androides sonham com ovelhas elétricas?','TBA31','SiteInformativoBibliosfera/capas/31.jpg'),
('Devoradores de estrelas','TBA32','SiteInformativoBibliosfera/capas/32.jpg'),
('Duna','TBA33','SiteInformativoBibliosfera/capas/33.jpg'),
('Encontro com Rama','TBA34','SiteInformativoBibliosfera/capas/34.jpg'),
('Eu, Robô','TBA35','SiteInformativoBibliosfera/capas/35.jpg'),
('Frankenstein','TBA36','SiteInformativoBibliosfera/capas/36.jpg'),
('Kindred: laços de sangue','TBA37','SiteInformativoBibliosfera/capas/37.jpg'),
('O guia definitivo do mochileiro das galáxias','TBA38','SiteInformativoBibliosfera/capas/38.jpg'),
('Perdido em Marte','TBA39','SiteInformativoBibliosfera/capas/39.jpg'),
('Piranesi','TBA40','SiteInformativoBibliosfera/capas/40.jpg'),
('Vinte Mil Léguas Submarinas','TBA41','SiteInformativoBibliosfera/capas/41.jpg');

/*Livros de Poesia*/
insert into livro (titulo, descricao, capa) values
('A Divina Comédia','TBA42','SiteInformativoBibliosfera/capas/42.jpg'),
('Alguma poesia','TBA43','SiteInformativoBibliosfera/capas/43.jpg'),
('Arquitetura do Silêncio','TBA44','SiteInformativoBibliosfera/capas/44.jpg'),
('Cantos da Solidão','TBA45','SiteInformativoBibliosfera/capas/45.jpg'),
('Cartas a um jovem poeta','TBA46','SiteInformativoBibliosfera/capas/46.jpg'),
('Cem sonetos de amor','TBA47','SiteInformativoBibliosfera/capas/47.jpg'),
('Ilíada e Odisseia','TBA48','SiteInformativoBibliosfera/capas/48.jpg'),
('o que o sol faz com as flores','TBA49','SiteInformativoBibliosfera/capas/49.jpg'),
('Os Lusíadas','TBA50','SiteInformativoBibliosfera/capas/50.jpg'),
('Outros jeitos de usar a boca','TBA51','SiteInformativoBibliosfera/capas/51.jpg'),
('Poema sujo','TBA52','SiteInformativoBibliosfera/capas/52.jpg'),
('Toda poesia','TBA53','SiteInformativoBibliosfera/capas/53.jpg');

/*Livros de Romance*/
('A culpa é das estrelas','TBA54','SiteInformativoBibliosfera/capas/54.jpg'),
('A hipótese do amor','TBA55','SiteInformativoBibliosfera/capas/55.jpg'),
('Anna Karênina','TBA56','SiteInformativoBibliosfera/capas/56.jpg'),
('Como eu era antes de você','TBA57','SiteInformativoBibliosfera/capas/57.jpg'),
('Dom Casmurro','TBA58','SiteInformativoBibliosfera/capas/58.jpg'),
('Dom Quixote De La Mancha','TBA59','SiteInformativoBibliosfera/capas/59.jpg'),
('É Assim que Acaba','TBA60','SiteInformativoBibliosfera/capas/60.jpg'),
('Eleanor & Park','TBA61','SiteInformativoBibliosfera/capas/61.jpg'),
('Orgulho e Preconceito','TBA62','SiteInformativoBibliosfera/capas/62.jpg'),
('Os miseráveis','TBA63','SiteInformativoBibliosfera/capas/63.jpg'),
('Romeu e Julieta','TBA64','SiteInformativoBibliosfera/capas/64.jpg'),
('Simplesmente acontece','TBA65','SiteInformativoBibliosfera/capas/65.jpg');

/*select * from livro;*/

-- Generado por Isabel el 05/12/25
-- Tablas de la Web de Eventos de Comedia

/*	Script de la bbdd formado por 4 tablas: persona, entrada, evento y valorar
	Inluye claves foraneas de: 
    - persona en: entrada, evento y valorar
    - evento en: valorar
	La tabla valorar es una relacion muchos a muchos de evento y persona.
*/
create database standapp;

create table persona
(IDPersona varchar(25) primary key,
tipo varchar(25),
nombreApellido varchar(25),
nombreUsuario varchar(25) unique,
telefono varchar(9),
email varchar(25) unique,
contraseña varchar(25));

create table perfil
(IDperfil varchar(25) primary key,
nombrePerfil varchar(25));

create table entrada
(IDEntrada varchar(25) primary key,
precio int,
IDPersona varchar(25),
constraint fk_entrada_persona foreign key (IDPersona) 
references persona (IDPersona));

create table evento
(IDEvento varchar(25) primary key,
descripcion varchar(50),
comediante varchar(25),
fechahora datetime,
IDPersona varchar(25),
constraint fk_evento_persona foreign key (IDPersona) 
references persona (IDPersona));

create table valorar
(IDEvento varchar(25),
IDPersona varchar(25),
valoracion int,
comentario varchar(100),
constraint pk_valorar primary key(IDEvento, IDPersona),
constraint fk_valorar_evento foreign key (IDEvento) 
references evento (IDEvento) on delete cascade,
constraint fk_valorar_persona foreign key (IDPersona) 
references persona (IDPersona));









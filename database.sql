CREATE DATABASE IF NOT EXISTS tareas;

USE tareas;

CREATE TABLE IF NOT EXISTS users(
id          int(255) auto_increment not null,
role        varchar(50),
name        varchar(100),
surname     varchar(200),
email       varchar(255),
password    varchar(255),
created_at  datetime,
CONSTRAINT pk_users PRIMARY KEY (id)
)ENGINE=InnoDB;

INSERT INTO users VALUES(null, 'ROLE_USER', 'Juan', 'Perez', 'juan@juan.com', 'juan', CURTIME());
INSERT INTO users VALUES(null, 'ROLE_USER', 'Cata', 'Lopez', 'cata@cata.com', 'cata', CURTIME());
INSERT INTO users VALUES(null, 'ROLE_USER', 'Mario', 'Toro', 'mario@mario.com', 'mario', CURTIME());

CREATE TABLE IF NOT EXISTS tasks(
id          int(255) auto_increment not null,
user_id     int(255) not null,
title       varchar(100),
content     text,
priority    varchar(20),
hours       int(100),
created_at  datetime,
CONSTRAINT pk_tasks PRIMARY KEY (id),
CONSTRAINT fk_tasks_users FOREIGN KEY(user_id) REFERENCES users(id)
)ENGINE=InnoDB;


INSERT INTO tasks VALUES(null, 1, 'Tarea 6', 'Contenido de la tarea 6', 'high', 30, CURTIME());
INSERT INTO tasks VALUES(null, 1, 'Tarea 1', 'Contenido de la Tarea 1', 'high', 40, CURTIME());
INSERT INTO tasks VALUES(null, 1, 'Tarea 2', 'Contenido de la tarea 2', 'low', 20, CURTIME());
INSERT INTO tasks VALUES(null, 2, 'Tarea 3', 'Contenido de la tarea 3', 'madium', 10, CURTIME());
INSERT INTO tasks VALUES(null, 2, 'Tarea 5', 'Contenido de la tarea 5', 'high', 50, CURTIME());
INSERT INTO tasks VALUES(null, 3, 'Tarea 4', 'Contenido de la tarea 4', 'high', 50, CURTIME());
INSERT INTO tasks VALUES(null, 3, 'Tarea 7', 'Contenido de la tarea 7', 'high', 50, CURTIME());
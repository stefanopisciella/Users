drop database if exists users;
create database if not exists users;
use users;

create table user (
    ID smallint primary key auto_increment,
    name varchar(50),
    email varchar(50),
    birth_year smallint,
    is_male boolean,
    privacy_agreed boolean
);

INSERT INTO user(name, email, birth_year, is_male, privacy_agreed) VALUES ("Stefano", "stefano.pisciella1@student.univaq.it", 2000, True, True);
INSERT INTO user(name, email, birth_year, is_male, privacy_agreed) VALUES ("Pippo", "pippo@gmail.com", 1989, True, False);
INSERT INTO user(name, email, birth_year, is_male, privacy_agreed) VALUES ("Paperino", "paperino@gmail.com", 1991, True, True);
INSERT INTO user(name, email, birth_year, is_male, privacy_agreed) VALUES ("Minnie", "minnie@gmail.com", 1966, False, True);











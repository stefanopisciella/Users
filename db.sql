drop database if exists users;
create database if not exists users;
use users;

create table user (
    ID smallint primary key auto_increment,
    name varchar(50),
    email varchar(50),
    year smallint,
    male boolean,
    privacy_agreed boolean
);

# INSERT INTO article(title, content, creation_datetime, last_update_datetime) VALUES ("1/43 BURAGO RED BULL", "F1 RB19 TEAM ORACLE RED BULL RACING N 1 SEASON 2023 MAX VERSTAPPEN", '2023-09-10 14:30:15','2023-09-11 14:30:15');








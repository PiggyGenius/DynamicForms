CREATE TABLE USER(
login VARCHAR(15) PRIMARY KEY,
mail VARCHAR(254) NOT NULL,
pwdHash VARCHAR(254) NOT NULL,
privileges ENUM('ADMIN', 'USER', 'MODERATOR', 'NONE') NOT NULL,
lastName VARCHAR(30) NOT NULL,
firstName VARCHAR(30) NOT NULL,
birthDate DATE,
lastLogin DATE NOT NULL);

CREATE TABLE FORM(
login VARCHAR(15) NOT NULL REFERENCES USER(login),
formKey VARCHAR(40) PRIMARY KEY,
title VARCHAR(64) NOT NULL,
description VARCHAR(1024) NOT NULL,
creationDate DATE NOT NULL,
activity TINYINT(1) NOT NULL);

CREATE TABLE ELEMENT(
formKey VARCHAR(40) REFERENCES FORM(formKey),
indexElement INTEGER,
label VARCHAR(128) NOT NULL,
help VARCHAR(512) NOT NULL,
type ENUM('CHECKBOX','TEXTFIELD','TEXTAREA','RADIOBUTTON','DATE','LIST') NOT NULL,
PRIMARY KEY(formKey,indexElement));

CREATE TABLE CONTENT(
formKey VARCHAR(40),
indexElement INTEGER,
indexContent INTEGER,
value VARCHAR(1024) NOT NULL,
PRIMARY KEY(formKey,indexElement,indexContent),
FOREIGN KEY(formKey,indexElement) REFERENCES ELEMENT(formKey,indexElement));

CREATE TABLE ANSWERLIST(
formKey VARCHAR(40),
indexElement INTEGER,
indexAnswer INTEGER,
uselessId INTEGER,
PRIMARY KEY(formKey,indexElement,uselessId),
FOREIGN KEY(formKey,indexElement) REFERENCES ELEMENT(formKey,indexElement));

CREATE TABLE ANSWERTEXT(
formKey VARCHAR(40),
indexElement INTEGER,
value VARCHAR(512) NOT NULL,
uselessId INTEGER,
PRIMARY KEY(formKey,indexElement,uselessId),
FOREIGN KEY(formKey,indexElement) REFERENCES ELEMENT(formKey,indexElement));

CREATE TABLE ANSWERDATE(
formKey VARCHAR(40),
indexElement INTEGER,
value DATE NOT NULL,
uselessId INTEGER,
PRIMARY KEY(formKey,indexElement,uselessId),
FOREIGN KEY(formKey,indexElement) REFERENCES ELEMENT(formKey,indexElement));

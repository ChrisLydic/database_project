CREATE TABLE Users
(
    UserId INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(UserId),
    Username varchar(30),
    PasswordHash char(40),
	AuthCode varchar(20)
);

CREATE TABLE Characters
(
    CharacterId INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(CharacterId),
    Name varchar(30)
);

CREATE TABLE Permissions
(
	UserId INT NOT NULL,
	FOREIGN KEY (UserId) REFERENCES Users(UserId),
	CharacterId INT NOT NULL,
	FOREIGN KEY (CharacterId) REFERENCES Characters(CharacterId),
	CONSTRAINT PermId PRIMARY KEY (UserId, CharacterId)
);
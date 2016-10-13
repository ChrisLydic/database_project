CREATE TABLE Users
(
    UserID INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(UserID),
    Username varchar(30),
    PasswordHash char(40),
	GivenName varchar(30),
	Surname varchar(30),
	AuthCode varchar(20)
);

CREATE TABLE Chats
(
    ChatID INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(ChatID),
    DisplayName varchar(30),
	FileName varchar(30)
);

CREATE TABLE Permissions
(
	UserID INT NOT NULL,
	FOREIGN KEY (UserID) REFERENCES Users(UserID),
	ChatID INT NOT NULL,
	FOREIGN KEY (ChatID) REFERENCES Chats(ChatID),
	CONSTRAINT PermID PRIMARY KEY (UserID, ChatID)
);
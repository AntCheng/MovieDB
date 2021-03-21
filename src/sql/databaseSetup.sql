CREATE TABLE Ranking
(
    RankingName CHAR(20),
    RankingID   INT PRIMARY KEY
);

CREATE TABLE MovieCountryInfo
(
    Languages CHAR(20) NOT NULL,
    Country  CHAR(20) PRIMARY KEY
);

CREATE TABLE MovieCompany
(
    Names      CHAR(20) NOT NULL,
    CompanyID INT PRIMARY KEY,
    UNIQUE (Names)
);

CREATE TABLE MovieCompanyInfo
(
    Title     CHAR(20) NOT NULL,
    Years     INT      NOT NULL,
    CompanyID INT,
    PRIMARY KEY (Title, Years),
    FOREIGN KEY (CompanyID) REFERENCES MovieCompany (CompanyID)
        ON DELETE CASCADE
);

CREATE TABLE MovieBasicInfo
(
    Title             CHAR(20) NOT NULL,
    Years             INT      NOT NULL,
    MovieID           INT PRIMARY KEY,
    Lengths           INT      NOT NULL,
    Categories        CHAR(20) NOT NULL,
    Country           CHAR(20) NOT NULL,
    Rating            INT      NOT NULL,
    NumberOfUpvotes   INT DEFAULT 0,
    NumberOfWatchings INT DEFAULT 0,
    NumberOfComments  INT DEFAULT 0,
    FOREIGN KEY (Title, Years) REFERENCES MovieCompanyInfo (Title, Years),
    FOREIGN KEY (Country) REFERENCES MovieCountryInfo (Country)
        ON DELETE CASCADE
);

CREATE TABLE RMContain
(
    RankingID INT,
    MovieID   INT,
    PRIMARY KEY (RankingID, MovieID),
    FOREIGN KEY (RankingID) REFERENCES Ranking (RankingID),
    FOREIGN KEY (MovieID) REFERENCES MovieBasicInfo (MovieID)
        ON DELETE CASCADE
);

CREATE TABLE Users
(
    AccountNumber INT PRIMARY KEY,
    Names         CHAR(20),
    Passwords     CHAR(20),
    UNIQUE (Names)
);

CREATE TABLE Watch
(
    MovieID       INT,
    AccountNumber INT,
    Dates          DATE,
    PRIMARY KEY (MovieID, AccountNumber)
);

CREATE TABLE MovieList
(
    ListID        INT,
    AccountNumber INT,
    PRIMARY KEY (ListID, AccountNumber),
    FOREIGN KEY (AccountNumber) REFERENCES Users (AccountNumber)
);

CREATE TABLE HistoryList
(
    ListID INT,
    PRIMARY KEY (ListID)
);

CREATE TABLE FavouriteList
(
    ListID       INT,
    Descriptions CHAR(500),
    PRIMARY KEY (ListID)
);

CREATE TABLE MMContain
(
    MovieID       INT,
    ListID        INT,
    AccountNumber INT,
    PRIMARY KEY (MovieID, ListID, AccountNumber),
    FOREIGN KEY (AccountNumber) REFERENCES Users (AccountNumber),
    FOREIGN KEY (MovieID) REFERENCES MovieBasicInfo (MovieID),
    FOREIGN KEY (ListID, AccountNumber) REFERENCES MovieList (ListID, AccountNumber)
);

CREATE TABLE RReview
(
    NumberofLike    INT DEFAULT 0,
    NumberofDislike INT DEFAULT 0,
    Content         CHAR(500) NOT NULL,
    Dates           DATE,
    Rating          INT,
    ReviewID        INT PRIMARY KEY,
    MovieID         INT,
    AccountNumber   INT,
    FOREIGN KEY (AccountNumber) REFERENCES Users (AccountNumber),
    FOREIGN KEY (MovieID) REFERENCES MovieBasicInfo (MovieID)
);

CREATE TABLE CComment
(
    NumberofLike    INT DEFAULT 0,
    NumberofDislike INT DEFAULT 0,
    Content         CHAR(500) NOT NULL,
    Dates           DATE,
    CommentID       INT PRIMARY KEY,
    ReviewID        INT,
    AccountNumber   INT,
    FOREIGN KEY (AccountNumber) REFERENCES Users (AccountNumber),
    FOREIGN KEY (ReviewID) REFERENCES RReview (ReviewID)
);

CREATE TABLE DiscussionGroup
(
    GroupID   INT PRIMARY KEY,
    GroupName CHAR(20) NOT NULL
);

CREATE TABLE DiscussionContent
(
    ContentID     INT,
    GroupID       INT,
    AccountNumber INT,
    Content       CHAR(500) NOT NULL,
    Dates         DATE,
    PRIMARY KEY (ContentID, GroupID, AccountNumber),
    FOREIGN KEY (AccountNumber) REFERENCES Users (AccountNumber),
    FOREIGN KEY (GroupID) REFERENCES DiscussionGroup (GroupID)
);


-- Ranking (RankingName, RankingID)
INSERT INTO Ranking
VALUES ('Latest', 1);
INSERT INTO Ranking
VALUES ('Highest Rating', 2);
INSERT INTO Ranking
VALUES ('Most Watching', 3);
INSERT INTO Ranking
VALUES ('Most Upvotes', 4);
INSERT INTO Ranking
VALUES ('Most Comments', 5);


-- MovieCountryInfo (Language, Country)
INSERT INTO MovieCountryInfo
VALUES ('English', 'Canada');
INSERT INTO MovieCountryInfo
VALUES ('English', 'USA');
INSERT INTO MovieCountryInfo
VALUES ('Mandarin', 'China');


-- MovieCompany (Name, CompanyID)
INSERT INTO MovieCompany
VALUES ('Looking Glass', 10000);
INSERT INTO MovieCompany
VALUES ('Continuum Films', 20000);
INSERT INTO MovieCompany
VALUES ('Off the Spectrum', 30000);
INSERT INTO MovieCompany
VALUES ('Pendulum Film', 40000);
INSERT INTO MovieCompany
VALUES ('Sandstone Films', 50000);


-- MovieCompanyInfo  (Title, Year, CompanyID)
INSERT INTO MovieCompanyInfo
VALUES ('Monster Hunter', 2020, 10000);
INSERT INTO MovieCompanyInfo
VALUES ('Greenland', 2020, 20000);
INSERT INTO MovieCompanyInfo
VALUES ('The Little Things', 2021, 30000);
INSERT INTO MovieCompanyInfo
VALUES ('Endgame', 2021, 40000);
INSERT INTO MovieCompanyInfo
VALUES ('The Yinyang Master', 2021, 50000);


-- MovieBasicInfo  (Title, Year, MovieID, Length, Category, Country, Rating, #ofUpvotes, #Watching, #ofComments)
INSERT INTO MovieBasicInfo
VALUES ('Monster Hunter', 2020, 1, 104, 'Fantasy', 'Canada', 0, 0, 0, 0);
INSERT INTO MovieBasicInfo
VALUES ('Greenland', 2020, 2, 119, 'Disaster', 'USA', 0, 0, 0, 0);
INSERT INTO MovieBasicInfo
VALUES ('The Little Things', 2021, 3, 128, 'Crime', 'USA', 0, 0, 0, 0);
INSERT INTO MovieBasicInfo
VALUES ('Endgame', 2021, 4, 119, 'Crime', 'China', 0, 0, 0, 0);
INSERT INTO MovieBasicInfo
VALUES ('The Yinyang Master', 2021, 5, 120, 'Fantasy', 'China', 0, 0, 0, 0);


--  RMContain (RankingID, MovieID)
INSERT INTO RMContain
VALUES (1, 1);
INSERT INTO RMContain
VALUES (1, 3);
INSERT INTO RMContain
VALUES (1, 4);
INSERT INTO RMContain
VALUES (2, 2);
INSERT INTO RMContain
VALUES (2, 4);
INSERT INTO RMContain
VALUES (2, 5);
INSERT INTO RMContain
VALUES (3, 1);
INSERT INTO RMContain
VALUES (3, 2);
INSERT INTO RMContain
VALUES (3, 3);
INSERT INTO RMContain
VALUES (4, 3);
INSERT INTO RMContain
VALUES (4, 4);
INSERT INTO RMContain
VALUES (4, 5);
INSERT INTO RMContain
VALUES (5, 1);
INSERT INTO RMContain
VALUES (5, 3);
INSERT INTO RMContain
VALUES (5, 5);


-- User (AccountNumber, Name, Password)
INSERT INTO Users
VALUES (692630, 'ERburETE', 'Kc0I8iYM&2*xgw^s14Mn');
INSERT INTO Users
VALUES (469738, 'ndRIzErT', 'sb&YvvY*NvoeaBH70Ew1');
INSERT INTO Users
VALUES (279183, 'terThisk', 'QYXwgaT*NL&QYGmor@$@');
INSERT INTO Users
VALUES (628591, 'OMeragMa', 'gnEvZg^VBRrgupsfiF*L');
INSERT INTO Users
VALUES (904166, 'OMEISMIc', '*XD*7E@Hx6ZJ#B28dZEt');


-- Watch (MovieID int, AccountNumber int, Date date)
INSERT INTO Watch
VALUES (1, 904166, '14-4月-2021');
INSERT INTO Watch
VALUES (1, 469738, '16-4月-2021');
INSERT INTO Watch
VALUES (1, 628591, '28-4月-2021');
INSERT INTO Watch
VALUES (1, 279183, '11-5月-2021');
INSERT INTO Watch
VALUES (2, 692630, '10-6月-2021');
INSERT INTO Watch
VALUES (2, 279183, '20-6月-2021');
INSERT INTO Watch
VALUES (2, 469738, '19-7月-2021');
INSERT INTO Watch
VALUES (3, 628591, '21-7月-2021');
INSERT INTO Watch
VALUES (4, 628591, '22-7月-2021');
INSERT INTO Watch
VALUES (4, 692630, '1-10月-2021');
INSERT INTO Watch
VALUES (5, 692630, '3-10月-2021');
INSERT INTO Watch
VALUES (5, 469738, '27-10月-2021');
INSERT INTO Watch
VALUES (5, 279183, '30-10月-2021');
INSERT INTO Watch
VALUES (5, 628591, '8-11月-2021');


-- HistoryList (ListID)
INSERT INTO HistoryList
VALUES (11);
INSERT INTO HistoryList
VALUES (22);
INSERT INTO HistoryList
VALUES (33);
INSERT INTO HistoryList
VALUES (44);
INSERT INTO HistoryList
VALUES (55);


-- FavouriteList (ListID, AccountNumber)
INSERT INTO FavouriteList
VALUES (111, 692630);
INSERT INTO FavouriteList
VALUES (222, 469738);
INSERT INTO FavouriteList
VALUES (333, 279183);
INSERT INTO FavouriteList
VALUES (444, 628591);
INSERT INTO FavouriteList
VALUES (555, 904166);


-- RReview (#Like, #Dislike, Content, Date, Rating, ReviewID, MovieID, AccountNumber)
INSERT INTO RReview
VALUES (0, 0, 'Good 0 ...', '1-1月-2021', 8, 100, 1, 904166);
INSERT INTO RReview
VALUES (0, 0, 'Good 1 ...', '2-1月-2021', 9, 101, 1, 469738);
INSERT INTO RReview
VALUES (0, 0, 'Bad 00 ...', '1-1月-2021', 3, 102, 1, 628591);
INSERT INTO RReview
VALUES (0, 0, 'Good 2 ...', '4-1月-2021', 7, 103, 4, 279183);
INSERT INTO RReview
VALUES (0, 0, 'Bad 01 ...', '6-1月-2021', 4, 104, 3, 469738);


-- CComment (#Like, #DisLike, Content, Date, CommentID, ReviewID, AccountNumber)
INSERT INTO CComment
VALUES (0, 0, 'Agree 0', '1-2月-2021', 1000, 100, 469738);
INSERT INTO CComment
VALUES (0, 0, 'Agree 1', '2-2月-2021', 1001, 100, 904166);
INSERT INTO CComment
VALUES (0, 0, 'Noooooo', '1-2月-2021', 1002, 102, 692630);
INSERT INTO CComment
VALUES (0, 0, 'ABCDEFG', '9-1月-2021', 1003, 103, 628591);
INSERT INTO CComment
VALUES (0, 0, 'NOPEEEE', '8-1月-2021', 1004, 103, 279183);


-- DiscussionGroup (GroupID, GroupName)
INSERT INTO DiscussionGroup
VALUES (1, 'Super Group 0');
INSERT INTO DiscussionGroup
VALUES (2, 'Super Group 1');
INSERT INTO DiscussionGroup
VALUES (3, 'Super Group 2');
INSERT INTO DiscussionGroup
VALUES (4, 'Super Group 3');
INSERT INTO DiscussionGroup
VALUES (5, 'Super Group 4');


-- DiscussionContent (ContentId, GroupID, AccountNumber, Content, Date)
INSERT INTO DiscussionContent
VALUES (5000, 1, 279183, 'I like this movie', '2-2月-2021');
INSERT INTO DiscussionContent
VALUES (5001, 1, 904166, 'aaaaaaaa', '2-2月-2021');
INSERT INTO DiscussionContent
VALUES (5002, 3, 279183, 'hhhhhhhhhhhh', '30-1月-2021');
INSERT INTO DiscussionContent
VALUES (5003, 4, 692630, 'GOOD GOOD GOOD', '5-2月-2021');
INSERT INTO DiscussionContent
VALUES (5004, 4, 692630, 'tgf34gt34wftgwr3t', '8-2月-2021');


-- MovieList (ListID, AccountNumber)
INSERT INTO MovieList
VALUES (11, 904166);
INSERT INTO MovieList
VALUES (111, 904166);
INSERT INTO MovieList
VALUES (22, 469738);
INSERT INTO MovieList
VALUES (33, 279183);
INSERT INTO MovieList
VALUES (333, 279183);
INSERT INTO MovieList
VALUES (44, 628591);


-- MMcontain (MovieID, ListID, AccountNumber)
INSERT INTO MMcontain
VALUES (1, 11, 904166);
INSERT INTO MMcontain
VALUES (3, 11, 904166);
INSERT INTO MMcontain
VALUES (3, 111, 904166);
INSERT INTO MMcontain
VALUES (4, 111, 904166);
INSERT INTO MMcontain
VALUES (1, 22, 469738);
INSERT INTO MMcontain
VALUES (2, 22, 469738);
INSERT INTO MMcontain
VALUES (3, 22, 469738);
INSERT INTO MMcontain
VALUES (4, 22, 469738);
INSERT INTO MMcontain
VALUES (5, 22, 469738);
INSERT INTO MMcontain
VALUES (4, 33, 279183);
INSERT INTO MMcontain
VALUES (4, 333, 279183);
INSERT INTO MMcontain
VALUES (1, 44, 628591);
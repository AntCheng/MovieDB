-- ALTER SESSION SET NLS_LANGUAGE='AMERICAN';

CREATE TABLE Ranking
(
    RankingName CHAR(20),
    RankingID   INT PRIMARY KEY
);

CREATE TABLE MovieCountryInfo
(
    Languages CHAR(20) NOT NULL,
    Country   CHAR(20) PRIMARY KEY
);

CREATE TABLE MovieCompany
(
    Names     CHAR(20) NOT NULL,
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
    Rating            FLOAT    NOT NULL,
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
    Dates         DATE,
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
    NumberOfLike    INT DEFAULT 0,
    NumberOfDislike INT DEFAULT 0,
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
    NumberOfLike    INT DEFAULT 0,
    NumberOfDislike INT DEFAULT 0,
    Content         CHAR(500) NOT NULL,
    Dates           DATE,
    CommentID       INT PRIMARY KEY,
    ReviewID        INT,
    AccountNumber   INT,
    FOREIGN KEY (AccountNumber) REFERENCES Users (AccountNumber),
    FOREIGN KEY (ReviewID) REFERENCES RReview (ReviewID)
    ON DELETE CASCADE
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


-- MovieCountryInfo (Languages, Country)
INSERT INTO MovieCountryInfo
VALUES ('English', 'Canada');
INSERT INTO MovieCountryInfo
VALUES ('English', 'USA');
INSERT INTO MovieCountryInfo
VALUES ('Mandarin', 'China');


-- MovieCompany (Names, CompanyID)
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


-- MovieCompanyInfo  (Title, Years, CompanyID)
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


-- MovieBasicInfo  (Title, Years, MovieID, Lengths, Categories, Country, Rating, NumberOfUpvotes, NumberOfWatching, NumberOfComments)
INSERT INTO MovieBasicInfo
VALUES ('Monster Hunter', 2020, 1, 104, 'Fantasy', 'Canada', 9, 0, 0, 0);
INSERT INTO MovieBasicInfo
VALUES ('Greenland', 2020, 2, 119, 'Disaster', 'USA', 8, 0, 0, 0);
INSERT INTO MovieBasicInfo
VALUES ('The Little Things', 2021, 3, 128, 'Crime', 'USA', 10, 0, 0, 0);
INSERT INTO MovieBasicInfo
VALUES ('Endgame', 2021, 4, 119, 'Crime', 'China', 9, 0, 0, 0);
INSERT INTO MovieBasicInfo
VALUES ('The Yinyang Master', 2021, 5, 120, 'Fantasy', 'China', 9, 0, 0, 0);


-- RMContain (RankingID, MovieID)
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


-- User (AccountNumber, Names, Passwords)
INSERT INTO Users
VALUES (1, 'U1', 'P1');
INSERT INTO Users
VALUES (2, 'U2', 'P2');
INSERT INTO Users
VALUES (3, 'U3', 'P3');
INSERT INTO Users
VALUES (4, 'U4', 'P4');
INSERT INTO Users
VALUES (5, 'U5', 'P5');


-- Watch (MovieID, AccountNumber, Dates)
INSERT INTO Watch
VALUES (1, 5, '14-Apr-2021');
INSERT INTO Watch
VALUES (1, 2, '16-Apr-2021');
INSERT INTO Watch
VALUES (1, 4, '28-Apr-2021');
INSERT INTO Watch
VALUES (1, 3, '11-May-2021');
INSERT INTO Watch
VALUES (2, 1, '10-Jun-2021');
INSERT INTO Watch
VALUES (2, 3, '20-Jun-2021');
INSERT INTO Watch
VALUES (2, 2, '19-Jul-2021');
INSERT INTO Watch
VALUES (3, 4, '21-Jul-2021');
INSERT INTO Watch
VALUES (4, 4, '22-Jul-2021');
INSERT INTO Watch
VALUES (4, 1, '1-Oct-2021');
INSERT INTO Watch
VALUES (5, 1, '3-Oct-2021');
INSERT INTO Watch
VALUES (5, 2, '27-Oct-2021');
INSERT INTO Watch
VALUES (5, 3, '30-Oct-2021');
INSERT INTO Watch
VALUES (5, 4, '8-Nov-2021');


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


-- FavouriteList (ListID, Description)
INSERT INTO FavouriteList
VALUES (111, 'dfasfd');
INSERT INTO FavouriteList
VALUES (222, 'eragvaazvfr');
INSERT INTO FavouriteList
VALUES (333, 'frtgbs');
INSERT INTO FavouriteList
VALUES (444, 'xfdhbh');
INSERT INTO FavouriteList
VALUES (555, 'szfhgt');
INSERT INTO FavouriteList
VALUES (6666, 'aaaaaaaaaaa');


-- RReview (NumberOfLike, NumberOfDislike, Content, Dates, Rating, ReviewID, MovieID, AccountNumber)
INSERT INTO RReview
VALUES (0, 0, 'Good 0 ...', '1-Jan-2021', 8, 1, 1, 5);
INSERT INTO RReview
VALUES (0, 0, 'Good 1 ...', '2-Jan-2021', 9, 2, 1, 2);
INSERT INTO RReview
VALUES (0, 0, 'Bad 00 ...', '1-Jan-2021', 3, 3, 1, 4);
INSERT INTO RReview
VALUES (0, 0, 'Good 2 ...', '4-Jan-2021', 7, 4, 4, 3);
INSERT INTO RReview
VALUES (0, 0, 'Bad 01 ...', '6-Jan-2021', 4, 5, 3, 2);


-- CComment (NumberOfLike, NumberOfDisLike, Content, Dates, CommentID, ReviewID, AccountNumber)
INSERT INTO CComment
VALUES (0, 0, 'Agree 0', '1-Feb-2021', 1000, 1, 2);
INSERT INTO CComment
VALUES (0, 0, 'Agree 1', '2-Feb-2021', 1001, 1, 5);
INSERT INTO CComment
VALUES (0, 0, 'Noooooo', '1-Feb-2021', 1002, 1, 1);
INSERT INTO CComment
VALUES (0, 0, 'ABCDEFG', '9-Jan-2021', 1003, 1, 4);
INSERT INTO CComment
VALUES (0, 0, 'NOPEEEE', '8-Jan-2021', 1004, 1, 3);


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


-- DiscussionContent (ContentID, GroupID, AccountNumber, Content, Dates)
INSERT INTO DiscussionContent
VALUES (5000, 1, 3, 'I like this movie', '2-Feb-2021');
INSERT INTO DiscussionContent
VALUES (5001, 1, 5, 'aaaaaaaa', '2-Feb-2021');
INSERT INTO DiscussionContent
VALUES (5002, 3, 3, 'hhhhhhhhhhhh', '30-Jan-2021');
INSERT INTO DiscussionContent
VALUES (5003, 4, 1, 'GOOD GOOD GOOD', '5-Feb-2021');
INSERT INTO DiscussionContent
VALUES (5004, 4, 1, 'tgf34gt34wftgwr3t', '8-Feb-2021');


-- MovieList (ListID, AccountNumber)
INSERT INTO MovieList
VALUES (11, 5);
INSERT INTO MovieList
VALUES (111, 5);
INSERT INTO MovieList
VALUES (22, 2);
INSERT INTO MovieList
VALUES (33, 3);
INSERT INTO MovieList
VALUES (333, 3);
INSERT INTO MovieList
VALUES (44, 4);
INSERT INTO MovieList
VALUES (6666, 4);


-- MMcontain (MovieID, ListID, AccountNumber)
INSERT INTO MMcontain
VALUES (1, 11, 5);
INSERT INTO MMcontain
VALUES (3, 11, 5);
INSERT INTO MMcontain
VALUES (3, 111, 5);
INSERT INTO MMcontain
VALUES (4, 111, 5);
INSERT INTO MMcontain
VALUES (1, 22, 2);
INSERT INTO MMcontain
VALUES (2, 22, 2);
INSERT INTO MMcontain
VALUES (3, 22, 2);
INSERT INTO MMcontain
VALUES (4, 22, 2);
INSERT INTO MMcontain
VALUES (5, 22, 2);
INSERT INTO MMcontain
VALUES (4, 33, 3);
INSERT INTO MMcontain
VALUES (4, 333, 3);
INSERT INTO MMcontain
VALUES (1, 44, 4);
INSERT INTO MMcontain
VALUES (1, 6666, 4);
INSERT INTO MMcontain
VALUES (3, 6666, 4);
INSERT INTO MMcontain
VALUES (4, 6666, 4);
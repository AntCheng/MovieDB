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
    url               VARCHAR(1000),
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
    Descriptions CHAR(250),
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
    Content         CHAR(250) NOT NULL,
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
    Content         CHAR(250) NOT NULL,
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
    Content       CHAR(250) NOT NULL,
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
INSERT INTO MovieCompanyInfo
VALUES ('Justice League', 2017, 50000);
INSERT INTO MovieCompanyInfo
VALUES ('Nomadland', 2020, 40000);
INSERT INTO MovieCompanyInfo
VALUES ('The Grizzlies', 2018, 20000);
INSERT INTO MovieCompanyInfo
VALUES ('Glass', 2019, 20000);
INSERT INTO MovieCompanyInfo
VALUES ('The Last Emperor', 1987, 50000);

-- MovieBasicInfo  (Title, Years, MovieID, Lengths, Categories, Country, Rating, url)
INSERT INTO MovieBasicInfo
VALUES ('Monster Hunter', 2020, 1, 104, 'Fantasy', 'Canada', 9, 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcSaB4uEmVrC_YTHxGJI1TgrP1rQ3XDO6yd26lUKmJM_5f2NrpTY');
INSERT INTO MovieBasicInfo
VALUES ('Greenland', 2020, 2, 119, 'Disaster', 'USA', 8, 'https://i.pinimg.com/originals/68/1b/ff/681bfff6540dacf6dcf0d4822cb4cac9.jpg');
INSERT INTO MovieBasicInfo
VALUES ('The Little Things', 2021, 3, 128, 'Crime', 'USA', 10, 'https://images.radio.com/aiu-media/TheLittleThings011921-3ceaa9a4-27a3-4ff6-817a-dfa778e38a10.png');
INSERT INTO MovieBasicInfo
VALUES ('Endgame', 2021, 4, 119, 'Crime', 'China', 9, 'https://upload.wikimedia.org/wikipedia/en/2/24/Endgame.jpeg');
INSERT INTO MovieBasicInfo
VALUES ('The Yinyang Master', 2021, 5, 120, 'Fantasy', 'China', 9, 'https://pics.filmaffinity.com/the_yin_yang_master_aka_the_yinyang_master-199349142-mmed.jpg');
INSERT INTO MovieBasicInfo
VALUES ('Justice League', 2017, 6, 120, 'Action', 'USA', 9, 'https://static.rogerebert.com/uploads/movie/movie_poster/justice-league-2017/large_justice_league_ver20.jpg');
INSERT INTO MovieBasicInfo
VALUES ('Nomadland', 2020, 7, 107, 'Drama', 'USA', 9, 'https://pics.filmaffinity.com/Nomadland-118487105-mmed.jpg');
INSERT INTO MovieBasicInfo
VALUES ('The Grizzlies', 2018, 8, 120, 'Drama', 'Canada', 7, 'https://i.ytimg.com/vi/vv8PI_ryHwA/movieposter_en.jpg');
INSERT INTO MovieBasicInfo
VALUES ('Glass', 2019, 9, 129, 'Sci-Fi', 'USA', 6, 'https://thevisionmsms.org/wp-content/uploads/2019/02/glass.jpg');
INSERT INTO MovieBasicInfo
VALUES ('The Last Emperor', 1987, 10, 219, 'Drama', 'China', 7, 'https://img.discogs.com/U2b5tYGsaUhAhVRL0JT4fdtwc94=/fit-in/590x585/filters:strip_icc():format(jpeg):mode_rgb():quality(90)/discogs-images/R-2558807-1425740716-6146.jpeg.jpg');


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
VALUES (1, 'Anthony', 'P1');
INSERT INTO Users
VALUES (2, 'Josh', 'P2');
INSERT INTO Users
VALUES (3, 'Ray', 'P3');
INSERT INTO Users
VALUES (4, 'Mark', 'rrrml123@ii');
INSERT INTO Users
VALUES (5, 'Frank', 'plplai991919');
INSERT INTO Users
VALUES (6, 'Jim', 'pihfb$#12');
INSERT INTO Users
VALUES (7, 'Bob', 'etfp155');


-- Watch (MovieID, AccountNumber, Dates)
INSERT INTO Watch
VALUES (1, 1, '2021-04-14');
INSERT INTO Watch
VALUES (1, 6, '2021-04-16');
INSERT INTO Watch
VALUES (1, 3, '2021-04-28');
INSERT INTO Watch
VALUES (1, 4, '2021-05-28');
INSERT INTO Watch
VALUES (2, 5, '2021-06-14');
INSERT INTO Watch
VALUES (2, 6, '2021-06-19');
INSERT INTO Watch
VALUES (2, 2, '2021-06-20');
INSERT INTO Watch
VALUES (3, 4, '2021-06-21');
INSERT INTO Watch
VALUES (4, 5, '2021-07-11');
INSERT INTO Watch
VALUES (4, 6, '2021-10-14');
INSERT INTO Watch
VALUES (5, 7, '2021-09-29');
INSERT INTO Watch
VALUES (5, 5, '2021-10-17');
INSERT INTO Watch
VALUES (5, 1, '2021-10-30');
INSERT INTO Watch
VALUES (5, 2, '2021-11-14');


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
VALUES (111, 'My favourite action movies');
INSERT INTO FavouriteList
VALUES (222, 'My favourite dramas');
INSERT INTO FavouriteList
VALUES (333, 'Starring Jackie Chen');
INSERT INTO FavouriteList
VALUES (444, 'My favourite fiction movies');
INSERT INTO FavouriteList
VALUES (555, 'Oscars');
INSERT INTO FavouriteList
VALUES (6666, 'Oscars');


-- RReview (NumberOfLike, NumberOfDislike, Content, Dates, Rating, ReviewID, MovieID, AccountNumber)
INSERT INTO RReview
VALUES (0, 0, 'Good  movie!', '2021-01-14', 8, 1, 1, 1);
INSERT INTO RReview
VALUES (0, 0, 'Great!!!', '2021-04-14', 9, 2, 1, 5);
INSERT INTO RReview
VALUES (0, 0, 'Bad :( ', '2021-04-14', 3, 3, 1, 6);
INSERT INTO RReview
VALUES (0, 0, 'Just so so', '2021-04-14', 7, 4, 4, 2);
INSERT INTO RReview
VALUES (0, 0, 'Bad! ...', '2021-04-18', 4, 5, 3, 1);
INSERT INTO RReview
VALUES (0, 0, 'Terrible ...', '2021-04-18', 3, 6, 3, 4);
INSERT INTO RReview
VALUES (0, 0, 'Not a movie ...', '2021-04-18', 4, 7, 2, 6);
INSERT INTO RReview
VALUES (0, 0, 'Bad 01 ...', '2021-04-18', 4, 8, 2, 4);
INSERT INTO RReview
VALUES (0, 0, 'Bad 01 ...', '2021-04-18', 4, 9, 5, 2);
INSERT INTO RReview
VALUES (0, 0, 'Good overall', '2021-04-18', 7, 10, 3, 3);
INSERT INTO RReview
VALUES (0, 0, 'Good casting.', '2021-04-18', 8, 11, 6, 2);
INSERT INTO RReview
VALUES (0, 0, 'Not very good.', '2021-04-18', 5, 12, 7, 5);
INSERT INTO RReview
VALUES (0, 0, 'Excellent!', '2021-04-18', 9, 13, 8, 7);
INSERT INTO RReview
VALUES (0, 0, 'Very good!', '2021-04-18', 8, 14, 1, 2);
INSERT INTO RReview
VALUES (0, 0, 'Nice!', '2021-04-18', 8, 15, 1, 3);
INSERT INTO RReview
VALUES (0, 0, 'Wonderful!', '2021-04-18', 9, 16, 1, 4);
INSERT INTO RReview
VALUES (0, 0, 'Good  movie indeed!', '2021-04-18', 9, 17, 1, 7);


-- CComment (NumberOfLike, NumberOfDisLike, Content, Dates, CommentID, ReviewID, AccountNumber)
INSERT INTO CComment
VALUES (0, 0, 'Agree 0', '2021-10-01', 1000, 3, 1);
INSERT INTO CComment
VALUES (0, 0, 'Agree 1', '2021-10-01', 1001, 2, 2);
INSERT INTO CComment
VALUES (0, 0, 'Noooooo', '2021-10-01', 1002, 1, 2);
INSERT INTO CComment
VALUES (0, 0, 'Disagree', '2021-10-01', 1003, 3, 4);
INSERT INTO CComment
VALUES (0, 0, 'NOPEEEE', '2021-10-01', 1004, 4, 5);
INSERT INTO CComment
VALUES (0, 0, 'Interesting review', '2021-10-01', 1005, 6, 5);
INSERT INTO CComment
VALUES (0, 0, 'Interesting review!', '2021-10-01', 1006, 5, 7);
INSERT INTO CComment
VALUES (0, 0, 'Well said!', '2021-10-01', 1007, 8, 3);
INSERT INTO CComment
VALUES (0, 0, 'Well said!', '2021-10-01', 1008, 13, 4);
INSERT INTO CComment
VALUES (0, 0, 'Disagree', '2021-10-01', 1009, 12, 6);
INSERT INTO CComment
VALUES (0, 0, 'Disagree!!', '2021-10-01', 1010, 2, 4);


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
VALUES (5000, 1, 1, 'I like this movie', '2021-10-03');
INSERT INTO DiscussionContent
VALUES (5001, 1, 3, 'aaaaaaaa', '2021-10-03');
INSERT INTO DiscussionContent
VALUES (5002, 3, 5, 'hhhhhhhhhhhh', '2021-10-03');
INSERT INTO DiscussionContent
VALUES (5003, 4, 7, 'GOOD GOOD GOOD', '2021-10-03');
INSERT INTO DiscussionContent
VALUES (5004, 4, 2, 'tgf34gt34wftgwr3t', '2021-10-03');


-- MovieList (ListID, AccountNumber)
INSERT INTO MovieList
VALUES (11, 1);
INSERT INTO MovieList
VALUES (111, 4);
INSERT INTO MovieList
VALUES (22, 5);
INSERT INTO MovieList
VALUES (33, 2);
INSERT INTO MovieList
VALUES (333, 6);
INSERT INTO MovieList
VALUES (44, 3);
INSERT INTO MovieList
VALUES (6666, 7);


-- MMcontain (MovieID, ListID, AccountNumber)
INSERT INTO MMContain
VALUES (1, 11, 1);
INSERT INTO MMContain
VALUES (3, 11, 1);
INSERT INTO MMContain
VALUES (3, 111, 4);
INSERT INTO MMContain
VALUES (4, 111, 4);
INSERT INTO MMContain
VALUES (1, 22, 5);
INSERT INTO MMContain
VALUES (2, 22, 5);
INSERT INTO MMContain
VALUES (3, 22, 5);
INSERT INTO MMContain
VALUES (4, 22, 5);
INSERT INTO MMContain
VALUES (5, 22, 5);
INSERT INTO MMContain
VALUES (4, 33, 2);
INSERT INTO MMContain
VALUES (4, 333, 6);
INSERT INTO MMContain
VALUES (1, 44, 3);
INSERT INTO MMContain
VALUES (1, 6666, 7);
INSERT INTO MMContain
VALUES (3, 6666, 7);
INSERT INTO MMContain
VALUES (4, 6666, 7);
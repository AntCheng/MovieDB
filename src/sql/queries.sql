-- Deletion: Delete a comment of oneself from CComment
DELETE
FROM CComment
WHERE CommentID = '$CommentID'
  AND AccountNumber = '$AccountNumber';

--TODO
-- Deletion: Delete a review of oneself from RReview
DELETE
FROM RREVIEW
WHERE REVIEWID = '$ReviewID'
  AND AccountNumber = '$AccountNumber';

--TODO
-- Update: Update a user’s password
UPDATE Users
SET Passwords = '$newPasswords'
WHERE AccountNumber = '$AccountNumber';

--T
-- Selection/Projection: Select the MovieID of movies that are produced in a particular year
SELECT MovieID
FROM MovieBasicInfo
WHERE Years = '$Years';

--T
-- Selection/Projection: Select the MovieID of movies that are produced by a specific movie company
SELECT m.MovieID
FROM MovieBasicInfo m,
WHERE m.Country = '$Country';

--TODO
-- Join: MovieBasicInfo and RReview, used when user click moreinfo for the movie
-- Join: Users want to check if a specific movie is in their favourite lists
SELECT *
FROM MovieList ml,
     MMContain mc,
     FavouriteList fl
WHERE ml.ListID = mc.ListID
  AND ml.ListID = fl.ListID
  AND mc.MovieID = '$MovieID'
  AND ml.AccountNumber = '$AccountNumber';


-- T
-- can change: find all movies that have been reviewed by all users.
-- Division: Find all user’s favourite lists that have all movies rating larger than 9.5
SELECT m.movieID
FROM MovieBasicInfo m
WHERE NOT EXISTS((SELECT u.AccountNumber
                  FROM Users u)
                 MINUS
                 (SELECT r.AccountNumber
                  FROM RREVIEW r
                  WHERE r.MovieID = m.movieID));


-- T
-- Aggregation with Group By: find the best rating movieID in each category
WITH mm AS (SELECT m.Categories, max(rating) as rati
                        FROM MovieBasicInfo m 
                        GROUP BY m.Categories)
    SELECT m.MovieID
    FROM  mm, MovieBasicInfo m
    WHERE mm.Categories = m.Categories AND m.rating = mm.rati



--T
-- Aggregation with Having: Find the welcome category that has average rating higher than the average rating of all movies
SELECT Categories
FROM MovieBasicInfo
GROUP BY Categories
HAVING avg(rating) > (SELECT avg(rating) FROM MovieBasicInfo);



-- T
-- Nested Aggregation with Group By: Find the most reviewed movieID 
-- Used in most review checkbox
SELECT sd.MovieID
FROM (SELECT m.MOVIEID, Count(*) as nreview
      FROM MovieBasicInfo m, RREVIEW r
      WHERE m.MovieID = r.MovieID
      GROUP BY m.MOVIEID) sd
WHERE sd.nreview = (SELECT max(sd.nreview)
                   FROM sd);

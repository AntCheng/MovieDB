--INSERTION: User could insert a review for the movies
-- This is used in the addReview page, where user could go to that page by clicking start in the diplaymovie page. 
-- Displaymovie page could be go from the moreinfo button in main page
INSERT INTO RREVIEW
VALUES (0, 0, 'Good  movie!', '1-Jan-2021', 8, 1, 1, 1); --example


-- Deletion: Delete a review of oneself from RReview
-- This is used in the account page where you could go in the main page after login in. 
-- User could delete their review there and the casacade would cause the comments being deleted
DELETE
FROM RREVIEW
WHERE REVIEWID = '$ReviewID'
  AND AccountNumber = '$AccountNumber';


-- Update: Update a user’s password
-- This is used in the account page where user could update their password there
UPDATE Users
SET Passwords = '$newPasswords'
WHERE AccountNumber = '$AccountNumber';

-- Selection/Projection: Select the MovieID of movies that are produced in a particular year
-- This is used in the main page, where user could open the radio box and select a specific year to find movies for that year
SELECT MovieID
FROM MovieBasicInfo
WHERE Years = '$Years';


-- Selection/Projection: Select the MovieID of movies that are produced by a specific movie country
-- This is used in the main page, where user could open the radio box and select a specific country to find movies for that country
SELECT m.MovieID
FROM MovieBasicInfo m,
WHERE m.Country = '$Country';


-- Join: select all for the join MovieBasicInfo and RReview
-- This is used when user click moreinfo for the movie and the next pagee would use the result of thie query to show corresponding reviews
SELECT *
FROM MovieList ml,
     MMContain mc,
     FavouriteList fl
WHERE ml.ListID = mc.ListID
  AND ml.ListID = fl.ListID
  AND mc.MovieID = '$MovieID'
  AND ml.AccountNumber = '$AccountNumber';



-- Division: find all movies that have been reviewed by all users.
-- This is used in main page of the website, with the button below "Find the movies that have been reviewed by all users"
SELECT m.movieID
FROM MovieBasicInfo m
WHERE NOT EXISTS((SELECT u.AccountNumber
                  FROM Users u)
                 MINUS
                 (SELECT r.AccountNumber
                  FROM RREVIEW r
                  WHERE r.MovieID = m.movieID));



-- Aggregation with Group By: find the best rating movieID in each category
-- This is used in main page of the website, with the button below "Find the best rating movies in each category"
WITH mm AS (SELECT m.Categories, max(rating) as rati
                        FROM MovieBasicInfo m 
                        GROUP BY m.Categories)
    SELECT m.MovieID
    FROM  mm, MovieBasicInfo m
    WHERE mm.Categories = m.Categories AND m.rating = mm.rati




-- Aggregation with Having: Find the welcome category that has average rating higher than the average rating of all movies
-- This is used in main page of the website, with the button below "Find the welcome category"
SELECT Categories
FROM MovieBasicInfo
GROUP BY Categories
HAVING avg(rating) > (SELECT avg(rating) FROM MovieBasicInfo);



-- Nested Aggregation with Group By: Find the most reviewed movieID 
-- This is used in the main page, where there is a most review checkbox, if selected, would use this query
SELECT sd.MovieID
FROM (SELECT m.MOVIEID, Count(*) as nreview
      FROM MovieBasicInfo m, RREVIEW r
      WHERE m.MovieID = r.MovieID
      GROUP BY m.MOVIEID) sd
WHERE sd.nreview = (SELECT max(sd.nreview)
                   FROM sd);

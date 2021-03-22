
-- Deletion: Delete a comment of oneself from CComment
DELETE FROM CComment
WHERE CommentID = "$CommentID" AND AccountNumber = "$AccountNumber";

-- Deletion: Delete a review of oneself from RReview
DELETE FROM CComment
WHERE CommentID = "$CommentID" AND AccountNumber = "$AccountNumber";

-- Update: Update a user’s password
UPDATE Users
SET Passwords = "$newPasswords"
WHERE AccountNumber = "$AccountNumber";

-- Selection/Projection: Select the title of movies that are produced in a particular year
SELECT title
FROM MovieBasicInfo
WHERE Years = "$Years";

-- Selection/Projection: Select the title of movies that are produced by a specific movie company
SELECT mo.title
FROM MovieCompanyInfo mo, MovieCompany co
WHERE mo.CompanyID = co.CompanyID AND co.names = "$Names";


-- Join: Users want to check if a specific movie is in their favourite lists
SELECT (*)
FROM MovieList ml, MMContain mc, FavouriteList fl
WHERE ml.ListID = mc.ListID AND ml.ListID = fl.ListID
      AND mc.MovieID = "$MovieID" AND ml.AccountNumber = "$AccountNumber";


-- Division: Find all user’s favourite lists that have all movies rating larger than 9.5
SELECT (*)
FROM FavouriteList fl
WHERE NOT EXISTS ((SELECT m.MovieID
                   FROM Movie m
                   WHERE m.MovieID > 9.5)
                   EXCEPT
                   (SELECT fl.MovieID
                    FROM MMContain mc
                    WHERE mc.ListID = fl.ListID
                    AND mc.MovieID = m.MovieID));


-- Aggregation with Group By: Find the avg rating of the movies in each category
SELECT Category, avg(rating)
FROM MovieBasicInfo
GROUP BY Category;


-- Aggregation with Having: Find the avg number of up-votes for each category
-- for which the average rating is higher than the average rating of all movies
SELECT Category, avg(NumberOfUpvotes)
FROM MovieBasicInfo
GROUP BY Category
HAVING avg(rating) > (SELECT avg(rating) FROM MovieBasicInfo);


-- Nested Aggregation with Group By: Find the largest average views of all categories
SELECT Category, max(AvgWatchings)
FROM (SELECT avg(NumberOfWatchings) AS AvgWatchings
      FROM MovieBasicInfo
      GROUP BY Category);

<?php
include 'dbh.php';
include 'queryDisplay.php';
connectToDB();
displayHeader();
?>

<html>
<head>
    <title>Movie Info</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body class="main">

<div class="container-fluid">


    <div class="container-fluid">
        <p>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#movies" aria-expanded="true" aria-controls="movies">
                Display all movies
            </button>
        </p>

        <div class="collapse" id="movies">
            <div class="overflow-auto">
                <div class="card card-body">
                    <h4>
                        <?php
                        $result = executePlainSQL("SELECT Count(*) FROM MovieBasicInfo");
                        $row = mysqli_fetch_row($result);

                        if ($row != null)
                            echo "<br> All " . $row[0] . " movies in the database: </br>";
                        echo '<hr>';
                        $result = executePlainSQL("SELECT * FROM MovieBasicInfo");
                        $col = 7;
                        showTable($result, $col, true);
                        ?>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Background image -->
        <div
                class="bg-image"
                style="
            background-image: url('img/cpsc304_racket.jpg');
            height: 100vh;
            "
    </div>
    <!-- Background image -->

    <div class="row">
        <div class="col-2">
            <br>
            <h4><i> Custom search: </i></h4>
        </div>
        <div class="col-10">
            <br>
            <form class="form-horizontal" method="POST" action="#result">
                <input type="hidden" id="searchRequest" name="searchRequest">
                <div class="form_input">
                    <select id="filter_category" class="form-control" name="filter_category">
                        <option value="" disabled selected hidden>choose category</option>
                        <option value="">n/a</option>
                        <option value="Crime">Crime</option>
                        <option value="Disaster">Disaster</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Action">Action</option>
                        <option value="Drama">Drama</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="all">All Category</option>
                    </select>
                </div>
                <hr>
                <div class="form_input">
                    <select id="filter_country" class="form-control" name="filter_country">
                        <option value="" disabled selected hidden>choose country</option>
                        <option value="">n/a</option>
                        <option value="Canada">Canada</option>
                        <option value="China">China</option>
                        <option value="USA">USA</option>
                    </select>
                </div>
                <hr>
                <div class="form_input">
                    <select id="filter_year" class="form-control" name="filter_year">
                        <option value="" disabled selected hidden>choose year</option>
                        <option value="">n/a</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                        <option value="1987">1987</option>
                    </select>
                </div>
                <hr>
                <div class="form_input">
                    <select id="filter_rating" class="form-control" name="filter_rating">
                        <option value="" disabled selected hidden>choose Rating</option>
                        <option value="">n/a</option>
                        <option value=10>10</option>
                        <option value=9>9</option>
                        <option value=8>8</option>
                        <option value=7>7</option>
                        <option value=6>6</option>
                        <option value=5>5</option>
                        <option value=4>4</option>
                        <option value=3>3</option>
                        <option value=2>2</option>
                        <option value=1>1</option>
                    </select>
                </div>
                <hr>
                <div class="form_input">
                    Top User Rated: <input type="checkbox" id="filter_topRating" name="filter_topRating">
                </div>
                <hr>
                <div class="form_input">
                    Most reviewed: <input type="checkbox" id="filter_mostreviewd" name="filter_mostreviewd">
                </div>
                <hr>
                <input type="submit" name="search" value="search" class="btn btn-primary"/>
            </form>
        </div>

        <div class="container-fluid">
            <!--                <div class="row">-->
            <!--                    <div class="col-xs-12">-->
            <h4>Find the best rating movie in each category</h4>
            <form method="POST" action="#result"> <!--refresh page when submitted-->
                <input type="hidden" id="catRequest" name="catRequest">
                <input type="submit" value = "display" name="category_best" class="s"></p>
            </form>

            <h4>Find the movies that have been reviewed by all users</h4>
            <form method="POST" action="#result"> <!--refresh page when submitted-->
                <input type="hidden" id="allReviewRequest" name="allReviewRequest">
                <input type="submit" value = "display" name="allReview" class="s"></p>
            </form>

            <h4>Find popular categories</h4>
            <form method="POST" action="#result"> <!--refresh page when submitted-->
                <input type="hidden" id="wellCatRequest" name="wellCatRequest">
                <input type="submit" value = "display" name="wellCat" class="s"></p>
            </form>
            <!--                    </div>-->
            <!--                </div>-->
        </div>


    </div>



    <?php

    function handleSearchRequest(){
        generalQueryAndDisplay();
    }

    function handleCatRequest(){
        queryCatRequest();
    }

    function handleAllReviewRequest(){
        queryAllReview();
    }

    function handleWelcomeCatRequest(){
        queryWelcomeCat();
    }

    // HANDLE ALL POST ROUTES
    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('search', $_POST)) {
                handleSearchRequest();
            }else if(array_key_exists('category_best',$_POST)){
                handleCatRequest();
            }else if(array_key_exists('allReview',$_POST)){
                handleAllReviewRequest();
            }else if(array_key_exists('wellCat',$_POST)){
                handleWelcomeCatRequest();
            }
            disconnectFromDB();
        }
    }


    function displayHeader() {
        $uid = $_GET[uid];
        echo '<span><div class="container-fluid">'."Hello, "."<i>".$uid."</i><br>";


        $url_user = "account.php?uid=".urlencode($_GET[uid]);
        $userCenter = '<form method="POST" action='.$url_user.'>';
        $userCenter .= '<object height="1" hspace="350"></object>';
        $userCenter .= "Update your password or reviews".'&nbsp&nbsp';
        $userCenter .=  '<input type="submit" value="Go" name="go" class="s">';
        $userCenter .=  '</form>';
        echo $userCenter.'<hr /></div></span>';
    }


    if (isset($_POST['searchRequest']) || isset($_POST['catRequest']) || isset($_POST['allReviewRequest']) || isset($_POST['wellCatRequest'])){
        handlePOSTRequest();
    }
    ?>

</div>
</body>
</html>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Bucketlist</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <?php
            if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                echo "
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/bucketlist/home.php\">Home</a></li>
                ";
            } else {
                echo "
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/bucketlist/feed.php\">Feed</a></li>
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/bucketlist/account.php?userid=" . $_SESSION['id'] . " \">My Profile</a></li>
                    
                ";
            }

            ?>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="/search.php" method="post">
            <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search" required autocomplete="off">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <ul class="navbar-nav ml-auto">
            <?php
            if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                echo "
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/bucketlist/register.php\">Sign Up</a></li>
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/bucketlist/login.php\">Login</a></li>
                    
                ";
            } else {
                echo "
                    
                    <li class=\"nav-item dropdown\">
                        <a class=\"nav-link dropdown-toggle\" href=\"/bucketlist/account.php\" id=\"navbarDropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                          Settings
                        </a>
                        <div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdownMenuLink\">
                          <a class=\"dropdown-item\" href=\"/bucketlist/reset-password.php\">Reset Password</a>
                          <a class=\"dropdown-item\" href=\"/bucketlist/account_settings.php\">Upload Profile Picture</a>
                        </div>
                    </li>
                    
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/bucketlist/logout.php\">Logout</a></li>
                    
                    <button type=\"button\" class=\"btn btn-primary\">
                      Notifications <span class=\"badge badge-pill badge-light\">9</span>
                    </button>
                ";
            }

            ?>
        </ul>
    </div>
</nav>

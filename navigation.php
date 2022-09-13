<!-- navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
 
        <div class="navbar-header">
            <!-- Dropdown for mobile devices -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="<?php echo $home_url; ?>">A14 Project</a>
        </div>
 
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php echo $page_title=="Index" ? "class='active'" : ""; ?>>
                    <a href="<?php echo $home_url; ?>">
                        <span class="glyphicon glyphicon-home"></span> Home
                    </a>
                </li>
            </ul>
 
            <?php
            // check if user/student was logged in
            // if user was logged in, show "my Tests" and "Logout" options 
            if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true && $_SESSION['type']==0)
            {
                ?>
                <ul class="nav navbar-nav">
                    <li <?php echo $page_title=="My Tests" ? "class='active'" : ""; ?>>
                        <a href="<?php echo $home_url; ?>my_tests.php">
                            <span class="glyphicon glyphicon-tasks"></span> My Tests
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li <?php echo $page_title=="Profile" ? "class='active'" : ""; ?>>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <?php echo $_SESSION['name']; ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="<?php echo $home_url; ?>my_profile/details.php">
                                <span class="glyphicon glyphicon-briefcase"></span> My Profile
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $home_url; ?>logout.php?action=logout_confirmed" onclick="return confirm('Are you sure you want to logout?')">
                                <span class="glyphicon glyphicon-log-out"></span> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <?php
            }
                
            // if user was not logged in, show the "login" option
            else
            {
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li <?php echo $page_title=="Login" ? "class='active'" : ""; ?>>
                        <a href="<?php echo $home_url; ?>login">
                            <span class="glyphicon glyphicon-log-in"></span> Log In
                        </a>
                    </li>

                    <li <?php echo $page_title=="Register" ? "class='active'" : ""; ?>>
                        <a href="<?php echo $home_url; ?>register">
                            <span class="glyphicon glyphicon-check"></span> Register
                        </a>
                    </li>
                </ul>
                <?php
            }
            ?>
             
        </div>
        <!--/.nav-collapse -->
 
    </div>
</div>
<!-- /navbar -->
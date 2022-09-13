<!-- navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
 
        <div class="navbar-header">
            <!-- to enable navigation dropdown when viewed in mobile device -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
 
            <a class="navbar-brand" href="<?php echo $home_url; ?>admin/index.php">A14 Project</a>
        </div>
 
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
 
 
                <!-- highlight for order related pages -->
                <li <?php echo $page_title=="Home" || $page_title=="User Registration" ? "class='active'" : ""; ?>>
                    <a href="<?php echo $home_url; ?>admin/index.php">
                        <span class="glyphicon glyphicon-home"></span> Home
                    </a>
                </li>

                <!-- highlight for problem related pages -->
                <li <?php
                        echo $page_title=="Problems" ? "class='active'" : ""; ?> >
                    <a href="<?php echo $home_url; ?>admin/read_problems.php">
                        <span class="glyphicon glyphicon-list"></span> Problems
                    </a>
                </li>

                <!-- highlight for test related pages -->
                <li <?php
                        echo $page_title=="Tests" ? "class='active'" : ""; ?> >
                    <a href="<?php echo $home_url; ?>admin/read_tests.php">
                        <span class="glyphicon glyphicon-tasks"></span> Tests
                    </a>
                </li>

                <!-- highlight for user related pages -->
                <li <?php
                        echo $page_title=="Users" ? "class='active'" : ""; ?> >
                    <a href="<?php echo $home_url; ?>admin/read_users.php">
                        <span class="glyphicon glyphicon-user"></span> Users
                    </a>
                </li>
            </ul>
 
            <!-- options in the upper right corner of the page -->
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                          <?php echo $_SESSION['name']; ?>
                          <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <!-- log out user -->
                        <li>
                            <a href="<?php echo $home_url; ?>admin/my_profile/details.php">
                            <h5><span class="glyphicon glyphicon-briefcase"></span> My Profile</h5>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $home_url; ?>logout.php?action=logout_confirmed" onclick="return confirm('Are you sure you want to logout?')">
                            <h5><span class="glyphicon glyphicon-log-out"></span> Logout</h5>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
 
        </div><!--/.nav-collapse -->
 
    </div>
</div>
<!-- /navbar -->
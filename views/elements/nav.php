<nav class="navbar navbar-expand-lg navbar-light  fixed-top">
    <a class="logo" href="/"><img src="/images/gfv-logo.png" alt=""></a>

    <?php
    //  If user is logged in (session is NOT empty)
    if( !empty($_SESSION["user_logged_in"]) ) {
    ?>

    

    <button class="navbar-toggler border-0" data-toggle="collapse" data-target="#mainNavBar">
        <i class="fas fa-utensils"></i>
    </button>


    
    <div class="navbar-collapse collapse" id="mainNavBar">

        <ul class="navbar-nav ml-auto text-right">
            <li class="nav-item dropdown " ><a class="nav-link" href="/">Home</a></li>
            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle" id="accountDropdown" data-toggle="dropdown">Account</a>
                <div class="dropdown-menu dropdown-menu-right text-right">
                    <a class="dropdown-item" href="/users/">My Profile</a>
                    <a class="dropdown-item" href="/users/logout.php">Logout</a>
                </div>
            </li>
         </ul>
    </div>
    <?php
        }
    ?>
</nav>

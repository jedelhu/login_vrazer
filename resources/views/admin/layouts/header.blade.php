<header class="site-header">
    <div class="container-fluid">
        <a href="#" class="site-logo">
            {{--<img class="hidden-md-down" src="{{asset('images/logo-2.png') }}" alt="">--}}
            {{--<img class="hidden-lg-up" src="{{asset('images/logo-2-mob.png') }}" alt="">--}}
            <img class="hidden-md-down" src="{{asset('images/vrazer.png') }}" alt="">
            <img class="hidden-lg-up" src="{{asset('images/vrazer.png') }}" alt="">
        </a>
        <button class="hamburger hamburger--htla">
            <span>toggle menu</span>
        </button>
        <div class="site-header-content">
            <div class="site-header-content-in">
                <div class="site-header-shown">
                    <div class="dropdown dropdown-notification notif">
                        <a href="#"
                           class="header-alarm dropdown-toggle active"
                           id="dd-notification"
                           data-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false">
                            <i class="font-icon-alarm"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-notification">
                            <div class="dropdown-menu-notif-header">
                                Notifications
                                <span class="label label-pill label-danger">4</span>
                            </div>
                            <div class="dropdown-menu-notif-list">
                                <div class="dropdown-menu-notif-item">
                                    <div class="photo">
                                        <img src="{{asset('images/photo-64-1.jpg') }}" alt="">
                                    </div>
                                    <div class="dot"></div>
                                    <a href="#">Morgan</a> was bothering about something
                                    <div class="color-blue-grey-lighter">7 hours ago</div>
                                </div>
                                <div class="dropdown-menu-notif-item">
                                    <div class="photo">
                                        <img src="{{asset('images/photo-64-2.jpg') }}" alt="">
                                    </div>
                                    <div class="dot"></div>
                                    <a href="#">Lioneli</a> had commented on this <a href="#">Super Important Thing</a>
                                    <div class="color-blue-grey-lighter">7 hours ago</div>
                                </div>
                                <div class="dropdown-menu-notif-item">
                                    <div class="photo">
                                        <img src="{{asset('images/photo-64-3.jpg') }}" alt="">
                                    </div>
                                    <div class="dot"></div>
                                    <a href="#">Xavier</a> had commented on the <a href="#">Movie title</a>
                                    <div class="color-blue-grey-lighter">7 hours ago</div>
                                </div>
                                <div class="dropdown-menu-notif-item">
                                    <div class="photo">
                                        <img src="{{asset('images/photo-64-4.jpg') }}" alt="">
                                    </div>
                                    <a href="#">Lionely</a> wants to go to <a href="#">Cinema</a> with you to see <a href="#">This Movie</a>
                                    <div class="color-blue-grey-lighter">7 hours ago</div>
                                </div>
                            </div>
                            <div class="dropdown-menu-notif-more">
                                <a href="#">See more</a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown dropdown-lang">
                        <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="flag-icon flag-icon-us"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-menu-col">
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-ru"></span>Русский</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-de"></span>Deutsch</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-it"></span>Italiano</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-es"></span>Español</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-pl"></span>Polski</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-li"></span>Lietuviu</a>
                            </div>
                            <div class="dropdown-menu-col">
                                <a class="dropdown-item current" href="#"><span class="flag-icon flag-icon-us"></span>English</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-fr"></span>Français</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-by"></span>Беларускi</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-ua"></span>Українська</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-cz"></span>Česky</a>
                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-ch"></span>中國</a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown user-menu">
                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{asset('images/avatar-2-64.png')}}" alt="">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-user"></span>Profile</a>
                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-cog"></span>Settings</a>
                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-question-sign"></span>Help</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/logout') }}"><span class="font-icon glyphicon glyphicon-log-out"></span>Logout</a>
                        </div>
                    </div>

                    <button type="button" class="burger-right">
                        <i class="font-icon-menu-addl"></i>
                    </button>
                </div><!--.site-header-shown-->

                <div class="mobile-menu-right-overlay"></div>
                <div class="site-header-collapsed">
                    <div class="site-header-collapsed-in">
                        <div class="dropdown dropdown-typical">

                        <div class="dropdown">
                            <button class="btn btn-rounded dropdown-toggle" id="dd-header-add" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Add
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dd-header-add">
                                <a class="dropdown-item" href="#">Quant and Verbal</a>
                                <a class="dropdown-item" href="#">Real Gmat Test</a>
                                <a class="dropdown-item" href="#">Prep Official App</a>
                                <a class="dropdown-item" href="#">CATprer Test</a>
                                <a class="dropdown-item" href="#">Third Party Test</a>
                            </div>
                        </div>

                        <div class="site-header-search-container">
                            <form class="site-header-search closed">
                                <input type="text" placeholder="Search"/>
                                <button type="submit">
                                    <span class="font-icon-search"></span>
                                </button>
                                <div class="overlay"></div>
                            </form>
                        </div>
                    </div><!--.site-header-collapsed-in-->
                </div><!--.site-header-collapsed-->
            </div><!--site-header-content-in-->
        </div><!--.site-header-content-->
    </div><!--.container-fluid-->
</header><!--.site-header-->
<div class="mobile-menu-left-overlay"></div>
<ul class="main-nav nav nav-inline">
    <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Prperties</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="#">Documents</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Sales</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Projects</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
    </li>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Prperties</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Documents</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Sales</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Projects</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="http://loginportal.vrazer.net/admin/connection">Connection</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="http://loginportal.vrazer.net/admin/mysqlconnection">MYSQL Connection</a>
    </li>
</ul>

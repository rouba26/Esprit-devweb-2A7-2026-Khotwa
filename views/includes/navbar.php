<?php
// $activePage is set by each page before including this file.
// Values: 'home' | 'about' | 'courses' | 'team' | 'testimonial' | 'contact' | '404'
$activePage = $activePage ?? '';

// Pages dropdown is active when any of its children is active
$pagesActive = in_array($activePage, ['team', 'testimonial', '404']);
?>

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>eLEARNING</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php"       class="nav-item nav-link <?= $activePage === 'home'        ? 'active' : '' ?>">Home</a>
                <a href="about.php"       class="nav-item nav-link <?= $activePage === 'about'       ? 'active' : '' ?>">About</a>
                <a href="courses.php"     class="nav-item nav-link <?= $activePage === 'courses'     ? 'active' : '' ?>">Courses</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle <?= $pagesActive ? 'active' : '' ?>" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="team.php"        class="dropdown-item <?= $activePage === 'team'        ? 'active' : '' ?>">Our Team</a>
                        <a href="testimonial.php" class="dropdown-item <?= $activePage === 'testimonial' ? 'active' : '' ?>">Testimonial</a>
                        <a href="404.php"         class="dropdown-item <?= $activePage === '404'         ? 'active' : '' ?>">404 Page</a>
                    </div>
                </div>
                <a href="contact.php" class="nav-item nav-link <?= $activePage === 'contact' ? 'active' : '' ?>">Contact</a>
            </div>
            <a href="" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Join Now<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->

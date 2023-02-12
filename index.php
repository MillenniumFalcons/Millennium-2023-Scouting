<html lang="en" class="full-height">
<?php
include('header.php');
include('navBar.php');
?>
<style>
    /* TEMPLATE STYLES */
    .flex-center {
        color: #fff;
    }

    .intro-1 {
        background: url("3647.png")no-repeat center center;
        background-size: cover;
    }

    .navbar .btn-group .dropdown-menu a:hover {
        color: #000 !important;
    }

    .navbar .btn-group .dropdown-menu a:active {

        color: #fff !important;
    }
</style>

<body style="background-color:#800000">
    <header>
        <!--Intro Section-->
        <section class="view intro-1 hm-black-strong">
            <div style="background-color: rgba(10,0,0,.3);" class="full-bg-img flex-center">
                <div class="container">
                    <ul>
                        <li>
                            <h1 class="h1-responsive font-bold wow fadeInDown" data-wow-delay="0.2s">Welcome to Scouting!</h1>
                        </li>
                        <li>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </header>
    <?php
    include("footer.php");
    ?>
    <!-- Animations init-->
    <script>
        new WOW().init();
    </script>


</body>

</html>
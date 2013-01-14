<?php
    $fileName = $_SERVER['PHP_SELF'];
    //echo "$fileName";
    switch ($fileName) {
        case '/index.php':
            $nav1 = " class=\"active\"";
            $nav2 = "";
            $nav3 = "";
            break;
        case '/importTasks.php':
            $nav1 = "";
            $nav2 = " class=\"active\"";
            $nav3 = "";
            break;
        case '/importStories.php':
            $nav1 = "";
            $nav2 = "";
            $nav3 = " class=\"active\"";
            break;
        default:
            $nav1 = "";
            $nav2 = "";
            $nav3 = "";
            break;
    }
?>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" 
                data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">FusionForge Task Importer</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li<?php echo $nav1; ?>><a href="index.php">Home</a></li>
              <li<?php echo $nav2; ?>><a href="importTasks.php">Import Tasks</a></li>
              <li<?php echo $nav3; ?>><a href="importStories.php">Import Stories (Trackers)</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
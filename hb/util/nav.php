<!-- NAVBAR 
This works like a component to the application 
In order to avoid code repetion this component was created 
and is called always that is needed  
-->
<nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
  <div class="container">
    <a href="index.php" class="navbar-brand">hotBerry</a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item px-2">
          <!-- This shows the use of $BASE URL that is especified in the init.php-->
          <a href="<?php echo $BASE; ?>/hb/" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item px-2">
          <a href="<?php echo $BASE; ?>/hb/schedule/" class="nav-link">Schedule</a>
        </li>
        <li class="nav-item px-2">
          <a href="<?php echo $BASE; ?>/hb/historic/" class="nav-link">Historic</a>
        </li>
        <!-- This link only allows access to the administrator, level 1 in the
        relation_level  -->
        <?php if ($_SESSION["user_level"] == 1) { ?> 
        <li class="nav-item px-2">
          <a href="<?php echo $BASE; ?>/hb/user/" class="nav-link">Users</a>
        </li>
        <?php } ?> 
        <!-- This link is only shown if the user that is login has more than 
        one device registerd and is the adminisrtator, level 1 in the relation_level -->
        <?php if ($_SESSION["devices"] > 1 || $_SESSION["user_level"] == 1) { ?>
        <li class="nav-item px-2">
          <a href="<?php echo $BASE; ?>/hb/device/" class="nav-link">Devices</a>
        </li>
        <?php } ?> 
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown mr-3">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-user"></i> Welcome
            <?php
            // This shows first the user_name, but since this is not a required 
            // field in the regitration process, in the Users table, it shows the email
            // which is a required one
            if ($_SESSION["user_name"] == "") {
              echo $_SESSION["user_email"];
            } else {
              echo $_SESSION["user_name"];
            }
            ?>
          </a>
          <div class="dropdown-menu">
            <a href="<?php echo $BASE; ?>/hb/profile/profile.php" class="dropdown-item">
              <i class="fa fa-user-circle"></i> Profile
            </a>
            <a href="<?php echo $BASE; ?>/hb/profile/pass.php" class="dropdown-item">
              <i class="fa fa-lock"></i> Password
            </a>
          </div>
        </li>
        <li class="nav-item">
          <a href="<?php echo $BASE; ?>/hb/util/logout.php" class="nav-link">
            <i class="fa fa-user-times"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
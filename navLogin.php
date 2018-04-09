<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 10/11/2017
 * Time: 11:33
 */
echo '

  <nav class="green darken-2">
    <div class="nav-wrapper">
      <a href="index.php" class="brand-logo">Logo</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="climbers.php">All Climbers</a></li>
        <li><a href="climbs.php">Climbs</a></li>
        <li><a href="following.php">Following</a></li>
        <li><a href="followRequests.php">Follow Requests</a></li>
        <li><a href="post.php">Post News</a></li>
        <li><a href="messages.php">Messages</a></li>
        <li><a href="profile.php">'.$_SESSION["username"].'</a></li>
      </ul>
    </div>
  </nav>';
?>
<style>
    .dropdown-content li{
        cursor:default;
    }
    .checkBox{
        opacity: 100 !important;
    }
</style>
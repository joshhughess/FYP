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
        <li>
            <form method="post" action="searchResults.php">
                <div class="input-field">
                  <input id="search" name="search" autocomplete="off" type="search" class="autocomplete" required>
                  <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                </div>
            </form>           
        </li> 
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
<script>
    $(document).ready(function(){
        var theNames;
        $.ajax({url:"climbNames.php",success:function(result){
            theNames = result;
            var dataCountry = {};
            for (var i = 0; i < theNames.length; i++) {
                dataCountry[theNames[i][0]] = theNames[i][1];
            }
            $('input.autocomplete').autocomplete({
                data: dataCountry,
            });
        }});
    });
</script>
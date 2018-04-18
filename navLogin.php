<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 10/11/2017
 * Time: 11:33
 */
ob_start();
echo '

  <nav class="nav-extended green darken-2">
    <div class="nav-wrapper">
      <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
      <a href="index.php" class="brand-logo"><img style="max-height: 64px;" src="images/logo_burned.png"></a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">     
        <li><a href="climbers.php">All Climbers</a></li>
        <li><a href="climbs.php">Climbs</a></li>
        <li><a href="messages.php">Messages</a></li>
        <li><a href="profile.php">'.$_SESSION["username"].'</a></li>
        <li>
        <form class="right" style="height:60px" method="post" action="searchResults.php">
            <div class="input-field">
              <input id="search" name="search" autocomplete="off" type="search" class="autocomplete" required>
              <label class="label-icon" style="transform: translateY(4px);" for="search"><i class="material-icons">search</i></label>
            </div>
        </form>  
        </li>
      </ul>
    </div>
    </nav>
    
    <ul id="slide-out" class="side-nav">   
        <li>
            <div class="user-view">
            <div class="background grey lighten-3">
            </div>
                <a href="#user"><img class="circle" src="http://localhost/myClimb/images/userProfile.png"></a>
                <a href="#!name"><span class="name">'.$_SESSION["username"].'</span></a>
                <a href="#!email"><span class="email">'.$_SESSION["email"].'</span></a>
            </div>
        </li>
        <li><a href="climbers.php">All Climbers</a></li>
        <li><div class="divider"></div></li>
        <li><a href="climbs.php">Climbs</a></li>
        <li><div class="divider"></div></li>
        <li><a href="post.php">Post News</a></li>
        <li><div class="divider"></div></li>
        <li><a href="messages.php">Messages</a></li>    
        <li><div class="divider"></div></li>
        <li>
        <form style="height:60px" method="post" action="searchResults.php">
            <div class="input-field">
              <input id="search" name="search" autocomplete="off" type="search" class="autocomplete" required>
              <label class="label-icon" for="search"><i class="material-icons">search</i></label>
            </div>
        </form>  
        </li>
  </ul>
    ';
?>
<style>
    .dropdown-content li{
        cursor:default;
    }
    .checkBox{
        opacity: 100 !important;
    }
    .autocomplete-content{
        z-index:2000;
    }
</style>
<script>
    $(document).ready(function(){
        var theNames;
        $.ajax({url:"names.php",success:function(result){
            theNames = result;
            var data = {};
            for (var i = 0; i < theNames.length; i++) {
                data[theNames[i][0]] = theNames[i][1];
            }
            $('input.autocomplete').autocomplete({
                data: data
            });
        }});
        // Initialize collapse button
        $(".button-collapse").sideNav();

    });
</script>
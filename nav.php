<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 10/11/2017
 * Time: 11:33
 */
ob_start();
echo '  
  <ul id="dropdown1" class="dropdown-content">
  <li><form autocomplete="off" action="connect.php" method="post" id="signInForm">
    <div class="input-field">
        <input type="text" name="username" placeholder="username" autocomplete="off">
    </div>
    <div class="input-field">
        <input type="password" name="password" placeholder="password" autocomplete="off">
    </div>
    <button class="btn waves-effect waves-light" type="submit" name="submit" onclick="connect.php">Login
        <i class="material-icons right">send</i>
    </button>
</form>
    <a href="registerForm.php"><b>Click here to register</b></a>
    </li>
</ul>
  <nav class="green darken-2">
    <div class="nav-wrapper">
      <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
      <a href="index.php" class="brand-logo"><img style="max-height: 64px;" src="images/logo_burned.png"></a>
       <ul id="nav-mobile" class="right hide-on-med-and-down"> 
        <li><a href="climbers.php">All Climbers</a></li>
        <li><a href="climbs.php">Climbs</a></li>
        <li><a href = "registerForm.php">Register an account</a></li>
        <li><a class="dropdown-button" data-activates="dropdown1" data-beloworigin="true">Login<i class="material-icons right">arrow_drop_down</i></a></li>
        <li>
        <form class="right searchRes" style="height:64px" method="post" action="searchResults.php">
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
        <li><a href="climbers.php">All Climbers</a></li>
        <li><div class="divider"></div></li>
        <li><a href="climbs.php">Climbs</a></li>  
        <li><div class="divider"></div></li>
        <li><a href = "registerForm.php">Register an account</a></li>
        <li><div class="divider"></div></li>
        <li>
        <form style="height:60px" method="post" action="searchResults.php">
            <div class="input-field">
              <input id="searchMobile" name="search" autocomplete="off" type="search" class="autocomplete" required>
              <label class="label-icon" for="search"><i class="material-icons">search</i></label>
            </div>
        </form>  
        </li>
  </ul>
  ';
?>
<style>
    .dropdown-content li {
        cursor: default;
    }
</style>
<script>
    $(document).ready(function(){
//        $('.searchRes').css('float','right !important');
        var theNames;
        $.ajax({url:"names.php",success:function(result){
            theNames = result;
            var data = {};
            for (var i = 0; i < theNames.length; i++) {
                data[theNames[i][0]] = theNames[i][1];
            }
//            console.log(data);
            $('input.autocomplete').autocomplete({
                data: data,
                limit: 5,
                onAutocomplete: function(data) {
                    console.log(data);
                }
            });
        }});
        // Initialize collapse button
        $(".button-collapse").sideNav();
        $('.dropdown-content').on('click', function(event) {
            event.stopPropagation();
        });
    });
</script>
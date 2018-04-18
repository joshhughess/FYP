<?php
include('connect.php');
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
?>
<div class = "container">

    <div class = "row">

        <div class = "col-lg-9">


            <div class = "panel panel-default">

                <div class = "panel-body">

                    <div class ="page-header">
                        <?php
                            $username=$_SESSION['username'];
                                echo "<form id='post' method='post' action='enterPost.php'><input type='hidden' value='$username' name='username'><input type='text' name='input'><input type='submit' value='Post' name='post'></form>"
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
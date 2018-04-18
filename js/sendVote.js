    $(function(){
        $("a.vote_upPost").click(function(){
            //get the id
            the_id = $(this).attr('id');

            // show the spinner
            $(this).parent().html("<img src='images/spinner.gif'/>");

            //fadeout the vote-count
            $("span#votes_count"+the_id).fadeOut("fast");

            //the main ajax request
            $.ajax({
                type: "POST",
                data: "action=vote_up&id="+$(this).attr("id"),
                url: "votes.php?post",
                success: function(msg)
                {
                    $("span#votes_count"+the_id).html(msg);
                    //fadein the vote count
                    $("span#votes_count"+the_id).fadeIn();
                    //remove the spinner
                    $("span#vote_buttons"+the_id).remove();
                }
            });
        });

        $("a.vote_downPost").click(function(){
            //get the id

            the_id = $(this).attr('id');

            // show the spinner
            $(this).parent().html("<img src='images/spinner.gif'/>");

            //the main ajax request
            $.ajax({
                type: "POST",
                data: "action=vote_down&id="+$(this).attr("id"),
                url: "votes.php?post",
                success: function(msg)
                {
                    $("span#votes_count"+the_id).fadeOut();
                    $("span#votes_count"+the_id).html(msg);
                    $("span#votes_count"+the_id).fadeIn();
                    $("span#vote_buttons"+the_id).remove();
                }
            });
        });
        $("a.vote_upComment").click(function(){
            //get the id
            the_id = $(this).attr('id');

            // show the spinner
            $(this).parent().html("<img src='images/spinner.gif'/>");

            //fadeout the vote-count
            $("span#votes_count"+the_id).fadeOut("fast");

            //the main ajax request
            $.ajax({
                type: "POST",
                data: "action=vote_up&id="+$(this).attr("id"),
                url: "votes.php?comment",
                success: function(msg)
                {
                    $("span#votes_count"+the_id).html(msg);
                    //fadein the vote count
                    $("span#votes_count"+the_id).fadeIn();
                    //remove the spinner
                    $("span#vote_buttons"+the_id).remove();
                }
            });
        });

        $("a.vote_downComment").click(function(){
            //get the id

            the_id = $(this).attr('id');

            // show the spinner
            $(this).parent().html("<img src='images/spinner.gif'/>");

            //the main ajax request
            $.ajax({
                type: "POST",
                data: "action=vote_down&id="+$(this).attr("id"),
                url: "votes.php?comment",
                success: function(msg)
                {
                    $("span#votes_count"+the_id).fadeOut();
                    $("span#votes_count"+the_id).html(msg);
                    $("span#votes_count"+the_id).fadeIn();
                    $("span#vote_buttons"+the_id).remove();
                }
            });
        });
    });
<?php
include 'connect.php';

$connect = mysqli_connect($host,$userName,$password, $db);

function getAllVotes($id)
	{
	/**
	Returns an array whose first element is votes_up and the second one is votes_down
	**/
        global $connect;
        $votes = array();
	$q = "SELECT * FROM post WHERE postID = $id";
	$r = mysqli_query($connect, $q);
	if(mysqli_num_rows($r)==1)//id found in the table
	{
		$row = mysqli_fetch_assoc($r);
		$votes[0] = $row['votesUp'];
		$votes[1] = $row['votesDown'];
		return $votes;
	}
	else
	{
		echo "Something went wrong";
	}
	}
function getEffectiveVotes($id)
	{
	/**
	Returns an integer
	**/
	$votes = getAllVotes($id);
	$effectiveVote = $votes[0] - $votes[1];
	return $effectiveVote;
	}
	
$id = $_POST['id'];
$action = $_POST['action'];

//get the current votes
$cur_votes = getAllVotes($id);
$effectiveVote = getEffectiveVotes($id);

//ok, now update the votes

if($action=='vote_up') //voting up
{
	$votes_up = $cur_votes[0]+1;
	$effectiveVote = getEffectiveVotes($id) + 1;
	$q = "UPDATE post SET votesUp = $votes_up, numberOfVotes = $effectiveVote WHERE postID = $id";
}
elseif($action=='vote_down') //voting down
{
	$votes_down = $cur_votes[1]+1;
	$effectiveVote = getEffectiveVotes($id) - 1;
	if($effectiveVote<=-5)
	{
		$q="DELETE FROM post WHERE postID = $id";
		header('Refresh: 0;');
	}
	else
	{
		$q = "UPDATE post SET votesDown = $votes_down, numberOfVotes = $effectiveVote WHERE postID = $id";
	}
}

$r = mysqli_query($connect, $q);
if($r) //voting done
	{
	$effectiveVote = getEffectiveVotes($id);
	echo $effectiveVote." votes";
	}
	elseif(!$r) //voting failed
	{
	echo mysqli_error($connect);
	}
?>
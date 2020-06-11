<?php
    session_start();
    require_once ("/home1/gobinitc/public_html/bucketlist/config.php");
    require_once ("/home1/gobinitc/public_html/bucketlist/pageload.php");
    require_once ("/home1/gobinitc/public_html/bucketlist/models/followers.php");
    require_once ("/home1/gobinitc/public_html/bucketlist/actions/update_counts.php");
    $following_id = $_POST['userid'];
    $user_id = $_SESSION['id'];
    $unfollowed = 0;
    if($following_id != null && $user_id!= null){
        $isUnfollowed = Followers::isFollowing($following_id, TRUE);
        $isFollowed = Followers::isFollowing($following_id);
        $updateQuery = False;
        error_log("IS ALREADY FOLLOWED: " . $isFollowed . ". IS ALREADY UNFOLLOWED " . $isUnfollowed);
        if($isUnfollowed){
            $sql = "UPDATE followers SET unfollowed = FALSE WHERE user = ? AND following = ?";
            $updateQuery = True;
        } elseif ($isFollowed){
            $sql = "UPDATE followers SET unfollowed = TRUE WHERE user = ? AND following = ?";
            $updateQuery = True;
        } else {
            $sql = "INSERT INTO followers (user, following, unfollowed) VALUES (?, ?, ?)";

            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('iii', $param_user, $param_following, $param_unfollowed);
                $param_user = $user_id;
                $param_following = $following_id;
                $param_unfollowed = $unfollowed;

                if($stmt->execute()){

                    $updateFollowers = new UpdateCounts("Followers");
                    $updateFollowing = new UpdateCounts("Following");

                    if($updateFollowers->updateFollowers($following_id)){

                    } else {

                        throw new Exception("Failed to update count in /follow-user.php");
                    }
                    if($updateFollowing->updateFollowers($user_id)){

                    } else {
                        throw new Exception("Failed to update count in /follow-user.php");
                    }
                } else {
                    error_log("SQL error: " . $stmt->error);
                    throw new Exception();
                }
                $stmt->close();
            } else {
                error_log("SQL error in prepare: " . $mysqli->error);
                throw new Exception();
            }
        }

        if($updateQuery){
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('ii', $param_user, $param_following);
                $param_user = $user_id;
                $param_following = $following_id;

                if($stmt->execute()){
                    echo"Executing, now updating stuff";
                    $updateFollowers = new UpdateCounts("Followers");
                    $updateFollowing = new UpdateCounts("Following");

                    if($updateFollowers->updateFollowers($following_id)){

                    } else {

                        throw new Exception("Failed to update count in /follow-user.php");
                    }
                    if($updateFollowing->updateFollowers($user_id)){

                    } else {
                        throw new Exception("Failed to update count in /follow-user.php");
                    }
                } else {
                    error_log("SQL error: " . $stmt->error);
                    throw new Exception();
                }
            } else {
                error_log("SQL error in prepare: " . $mysqli->error);
                throw new Exception();
            }
        }

    }


?>
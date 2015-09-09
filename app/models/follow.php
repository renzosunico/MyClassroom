<?php
class Follow extends AppModel
{
    CONST TABLE = 'follow';
    public static function unsetFollow($thread_id, $user_id)
    {
        $db = DB::conn();
        $db->query("DELETE FROM follow where thread_id = ? AND user_id = ?", array($thread_id, $user_id));
    }

    public static function setFollow($thread_id, $user_id)
    {
        $db = DB::conn();
        $params = array(
            'thread_id'     =>  $thread_id,
            'user_id'       =>  $user_id
        );
        $db->insert(self::TABLE, $params);
    }

    public static function getFollowedThreadIds($user_id)
    {
        $db = DB::conn();
        return $db->rows("SELECT thread_id FROM follow WHERE user_id = ?", array($user_id));
    }

    public static function getFollowedThreadByUserId($user_id)
    {
        $db = DB::conn();
        return $db->row("SELECT * FROM follow where user_id = ?", array($user_id));
    }

    public static function isFollowed($thread_id)
    {
        $db = DB::conn();
        $params = array(
            $thread_id,
            $_SESSION['userid']
        );
        return $db->row("SELECT * FROM follow where thread_id=? AND user_id=?",$params);
    }

}
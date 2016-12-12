<?php
function get_location($ip)
{
    $content = file_get_contents("http://g.fastapi.net/location?__ip=$ip");
    
    $location = @json_decode($content, TRUE);

    return $location && $location['country'] ? $location : array();
}

function user_log($message, $level = 'info', $userId = NULL)
{

}

function avatar($img, $default = TRUE)
{
    if($default) {
        $avatar = '/img/avatar200x200.jpg';
    }else{
        $avatar = '';
    }

    if (!empty($img)) {
        $avatar = Config::get('Upload.Prefix') . $img;
    }

    return $avatar;
}

/*function avatarSmall($user, $type = 1)
{
    $avatar = '/img/avatar29x29.jpg';

    if (!empty($user['avatar_small'])) {
        $avatar = Config::get('Upload.Prefix') . $user['avatar_small'];
    }

    return $avatar;
}*/


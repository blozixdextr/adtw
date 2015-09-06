<?php

function twitcherLanguageToFlag($user) {
    if ($user->language_id > 0) {
        switch (strtolower($user->language->title)) {
            case 'english':
                $code = 'us';
                break;
            case 'russian':
                $code = 'ru';
                break;
            case 'chinese':
                $code = 'cn';
                break;
            case 'spanish':
                $code = 'es';
                break;
            default:
                $code = '';
                break;
        }
        if ($code != '') {
            return '<span class="flag-icon flag-icon-'.$code.'"></span> '.$user->language->title;
        }
        return $user->language->title;
    }
    return '';
}


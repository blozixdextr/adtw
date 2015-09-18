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

function anonymizeEmail($value) {
    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $emailParts = explode('@', $value);
        $name = $emailParts[0];
        $lastLetter = substr($name, -1);
        $firstLetter = substr($name, 0, 1);
        $length = strlen($name) - 2;
        if ($length < 2) {
            $length = 2;
        }
        $name = str_repeat('*', $length);
        $value = $firstLetter.$name.$lastLetter.'@'.$emailParts[1];
    }

    return $value;
}


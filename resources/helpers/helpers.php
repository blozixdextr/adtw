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

function getNotificationIcon($type) {
    switch ($type) {
        case 'important':
            $ico = 'check-square';
            break;

        case 'accept':
            $ico = 'check-square';
            break;

        case 'paypal':
            $ico = 'cc-paypal';
            break;

        case 'stripe':
            $ico = 'credit-card';
            break;

        case 'transfer':
        case 'balance':
            $ico = 'money';
            break;

        case 'banner':
            $ico = 'bookmark';
            break;

        case 'decline':
            $ico = 'minus-square';
            break;

        default:
            $ico = 'twitch';
            break;
    }

    return $ico;
}


-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 22 2015 г., 11:35
-- Версия сервера: 5.6.17
-- Версия PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `adtw`
--

-- --------------------------------------------------------

--
-- Структура таблицы `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `twitcher_id` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `status` enum('waiting','accepted','declined','finished') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting',
  `amount_limit` double(8,2) unsigned NOT NULL DEFAULT '0.00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `twitcher_id` (`twitcher_id`),
  KEY `type` (`type_id`),
  KEY `is_active` (`is_active`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `banner_stream`
--

DROP TABLE IF EXISTS `banner_stream`;
CREATE TABLE IF NOT EXISTS `banner_stream` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` int(10) unsigned NOT NULL,
  `stream_id` int(10) unsigned NOT NULL,
  `transfer_id` int(10) unsigned DEFAULT NULL,
  `viewers` int(10) unsigned NOT NULL DEFAULT '0',
  `minutes` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` double(12,6) unsigned NOT NULL DEFAULT '0.000000',
  `status` enum('waiting','accepted','declined','declining','complain') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting',
  `client_comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitcher_comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `banner_id` (`banner_id`),
  KEY `stream_id` (`stream_id`),
  KEY `transfer_id` (`transfer_id`),
  KEY `status` (`status`),
  KEY `minutes` (`minutes`),
  KEY `views` (`viewers`),
  KEY `amount` (`amount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `key_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `additional_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `review_date` datetime DEFAULT NULL,
  `var` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `key_value` (`key_value`),
  KEY `additional_value` (`additional_value`),
  KEY `review_date` (`review_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2015_08_30_190804_create_user_profiles_table', 1),
('2015_08_30_190805_create_notifications_table', 1),
('2015_08_30_190807_create_logs_table', 1),
('2015_09_02_110706_create_user_auth_token_table', 1),
('2015_09_02_205508_create_payments_table', 1),
('2015_09_02_210208_create_withdrawals_table', 1),
('2015_09_02_211006_create_user_transfers_table', 1),
('2015_09_02_214121_create_refs_table', 1),
('2015_09_02_215453_create_banners_table', 1),
('2015_09_03_124951_create_ref_user_table', 1),
('2015_09_03_63612_create_streams_table', 1),
('2015_09_03_64218_create_stream_banners_table', 1),
('2015_09_03_65455_create_stream_timelogs_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8_unicode_ci,
  `seen_at` datetime DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `seen_at` (`seen_at`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `merchant` enum('stripe','paypal') COLLATE utf8_unicode_ci NOT NULL,
  `transaction_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` double(8,2) unsigned NOT NULL,
  `currency` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `response` text COLLATE utf8_unicode_ci,
  `cart` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `merchant` (`merchant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `refs`
--

DROP TABLE IF EXISTS `refs`;
CREATE TABLE IF NOT EXISTS `refs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `type` (`type`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `refs`
--

INSERT INTO `refs` (`id`, `pid`, `type`, `title`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 0, 'banner_type', '728*90', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(2, 0, 'banner_type', '120*600', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(3, 0, 'banner_type', '300*250', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(4, 0, 'language', 'English', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(5, 0, 'language', 'Russian', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(6, 0, 'language', 'Spanish', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(7, 0, 'game', 'League of Legends', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(8, 0, 'game', 'Dota 2', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(9, 0, 'game', 'HeartStone', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(10, 0, 'game', 'CS:GO', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(11, 0, 'game', 'Starcraft 2', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(12, 0, 'game', 'WOW', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(13, 0, 'game', 'Destiny', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(14, 0, 'game', 'Diablo 3', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(15, 0, 'game', 'Super Mario Maker', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(16, 0, 'game', 'H1Z1', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(17, 0, 'game', 'FIFA 16', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(18, 0, 'game', 'Minecraft', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(19, 0, 'game', 'Runescape', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(20, 0, 'game', 'GTA 5', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(21, 0, 'game', 'WOT', NULL, '2015-09-22 07:34:46', '2015-09-22 07:34:46'),
(22, 0, 'game', 'Call of Duty', NULL, '2015-09-22 07:34:47', '2015-09-22 07:34:47'),
(23, 0, 'game', 'Poker', NULL, '2015-09-22 07:34:47', '2015-09-22 07:34:47'),
(24, 0, 'game', 'Arma 3', NULL, '2015-09-22 07:34:47', '2015-09-22 07:34:47');

-- --------------------------------------------------------

--
-- Структура таблицы `ref_user`
--

DROP TABLE IF EXISTS `ref_user`;
CREATE TABLE IF NOT EXISTS `ref_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ref_id` (`ref_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `streams`
--

DROP TABLE IF EXISTS `streams`;
CREATE TABLE IF NOT EXISTS `streams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_start` datetime NOT NULL,
  `time_end` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `stream_timelogs`
--

DROP TABLE IF EXISTS `stream_timelogs`;
CREATE TABLE IF NOT EXISTS `stream_timelogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stream_id` int(10) unsigned NOT NULL,
  `timeslot_start` datetime NOT NULL,
  `timeslot_end` datetime NOT NULL,
  `viewers` int(10) unsigned NOT NULL,
  `status` enum('live','died') COLLATE utf8_unicode_ci NOT NULL,
  `amount` double(12,6) NOT NULL DEFAULT '0.000000',
  `screenshot` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `response` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `stream_id` (`stream_id`),
  KEY `timeslot_start` (`timeslot_start`),
  KEY `timeslot_end` (`timeslot_end`),
  KEY `viewers` (`viewers`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `provider` enum('local','facebook','twitter','google','linkedin','github','bitbucket','twitch') COLLATE utf8_unicode_ci DEFAULT 'local',
  `oauth_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` enum('user','admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `type` enum('twitcher','client') COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitch_views` int(10) unsigned DEFAULT NULL,
  `twitch_followers` int(10) unsigned DEFAULT NULL,
  `twitch_videos` int(10) unsigned DEFAULT NULL,
  `twitch_profile` text COLLATE utf8_unicode_ci,
  `twitch_channel` text COLLATE utf8_unicode_ci,
  `last_activity` timestamp NULL DEFAULT NULL,
  `twitch_updated` timestamp NULL DEFAULT NULL,
  `balance` double(8,2) NOT NULL DEFAULT '0.00',
  `balance_blocked` double(8,2) unsigned NOT NULL DEFAULT '0.00',
  `currency` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'USD',
  `language_id` int(10) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  KEY `provider` (`provider`),
  KEY `oauth_id` (`oauth_id`),
  KEY `role` (`role`),
  KEY `type` (`type`),
  KEY `twitch_views` (`twitch_views`),
  KEY `twitch_followers` (`twitch_followers`),
  KEY `twitch_videos` (`twitch_videos`),
  KEY `last_activity` (`last_activity`),
  KEY `twitch_updated` (`twitch_updated`),
  KEY `balance` (`balance`),
  KEY `language` (`language_id`),
  KEY `balance_blocked` (`balance_blocked`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `is_active`, `name`, `email`, `password`, `provider`, `oauth_id`, `role`, `type`, `twitch_views`, `twitch_followers`, `twitch_videos`, `twitch_profile`, `twitch_channel`, `last_activity`, `twitch_updated`, `balance`, `balance_blocked`, `currency`, `language_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'admin', 'admin@adtw.ch', '$2y$10$B5wFoVEW6Scw4vTFBR9k3uacjVUNMuSxADtl/zjM7.lKK.TUzrIuq', 'local', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, '2015-09-22 07:34:49', NULL, 0.00, 0.00, 'USD', NULL, NULL, '2015-09-22 07:34:49', '2015-09-22 07:34:49', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_auth_token`
--

DROP TABLE IF EXISTS `user_auth_token`;
CREATE TABLE IF NOT EXISTS `user_auth_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sex` enum('male','female','other') COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `about` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmed_paypal` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_profiles_user_id_unique` (`user_id`),
  KEY `paypal` (`paypal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `first_name`, `last_name`, `sex`, `dob`, `about`, `avatar`, `mobile`, `paypal`, `confirmed_paypal`, `created_at`, `updated_at`) VALUES
(1, 1, 'Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2015-09-22 07:34:49', '2015-09-22 07:34:49');

-- --------------------------------------------------------

--
-- Структура таблицы `user_transfers`
--

DROP TABLE IF EXISTS `user_transfers`;
CREATE TABLE IF NOT EXISTS `user_transfers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` int(10) unsigned NOT NULL,
  `seller_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `currency` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `cart` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `seller_id` (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `withdrawals`
--

DROP TABLE IF EXISTS `withdrawals`;
CREATE TABLE IF NOT EXISTS `withdrawals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `merchant` enum('stripe','paypal') COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` double(8,2) unsigned NOT NULL,
  `currency` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('waiting','done','declined') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting',
  `transaction_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `response` text COLLATE utf8_unicode_ci,
  `admin_id` int(10) unsigned DEFAULT NULL,
  `admin_comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `merchant` (`merchant`),
  KEY `status` (`status`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

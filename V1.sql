-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 10 dec 2020 om 14:37
-- Serverversie: 10.4.14-MariaDB
-- PHP-versie: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spotlight`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `beoordeling`
--

CREATE TABLE `beoordeling` (
  `rating_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruikers`
--

CREATE TABLE `gebruikers` (
  `gebruiker_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `opleiding`
--

CREATE TABLE `opleiding` (
  `opleiding_id` int(11) NOT NULL,
  `jaar` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `opmerkingen`
--

CREATE TABLE `opmerkingen` (
  `opmerking_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `tekst` text DEFAULT NULL,
  `datum` datetime NOT NULL,
  `gebruiker_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `suggesties`
--

CREATE TABLE `suggesties` (
  `gebruiker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tags`
--

CREATE TABLE `tags` (
  `video_id` int(11) NOT NULL,
  `tag_naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `vakken`
--

CREATE TABLE `vakken` (
  `vak_id` int(11) NOT NULL,
  `opleiding_id` int(11) NOT NULL,
  `vak_naam` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `video`
--

CREATE TABLE `video` (
  `video_id` int(11) NOT NULL,
  `docent_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `youtube_id` varchar(15) DEFAULT NULL,
  `extensie` varchar(45) DEFAULT NULL,
  `datum` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `views`
--

CREATE TABLE `views` (
  `video_id` int(11) NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `beoordeling`
--
ALTER TABLE `beoordeling`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `fk_beoordeling_video1_idx` (`video_id`);

--
-- Indexen voor tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`gebruiker_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexen voor tabel `opleiding`
--
ALTER TABLE `opleiding`
  ADD PRIMARY KEY (`opleiding_id`);

--
-- Indexen voor tabel `opmerkingen`
--
ALTER TABLE `opmerkingen`
  ADD PRIMARY KEY (`opmerking_id`,`video_id`,`gebruiker_id`),
  ADD KEY `fk_opmerkingen_video1_idx` (`video_id`),
  ADD KEY `fk_opmerkingen_gebruikers1_idx` (`gebruiker_id`);

--
-- Indexen voor tabel `suggesties`
--
ALTER TABLE `suggesties`
  ADD KEY `fk_suggesties_gebruikers1_idx` (`gebruiker_id`);

--
-- Indexen voor tabel `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`video_id`),
  ADD UNIQUE KEY `video_id_UNIQUE` (`video_id`),
  ADD KEY `fk_tags_video1_idx` (`video_id`);

--
-- Indexen voor tabel `vakken`
--
ALTER TABLE `vakken`
  ADD PRIMARY KEY (`vak_id`),
  ADD KEY `fk_vakken_opleiding1_idx` (`opleiding_id`);

--
-- Indexen voor tabel `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`video_id`),
  ADD UNIQUE KEY `playback_id_UNIQUE` (`youtube_id`),
  ADD KEY `fk_video_gebruikers1_idx` (`docent_id`),
  ADD KEY `fk_video_categorie1_idx` (`categorie_id`);

--
-- Indexen voor tabel `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`video_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `beoordeling`
--
ALTER TABLE `beoordeling`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `gebruiker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `opleiding`
--
ALTER TABLE `opleiding`
  MODIFY `opleiding_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `opmerkingen`
--
ALTER TABLE `opmerkingen`
  MODIFY `opmerking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `video`
--
ALTER TABLE `video`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `beoordeling`
--
ALTER TABLE `beoordeling`
  ADD CONSTRAINT `fk_beoordeling_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `opmerkingen`
--
ALTER TABLE `opmerkingen`
  ADD CONSTRAINT `fk_opmerkingen_gebruikers1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruikers` (`gebruiker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_opmerkingen_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `suggesties`
--
ALTER TABLE `suggesties`
  ADD CONSTRAINT `fk_suggesties_gebruikers1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruikers` (`gebruiker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `fk_tags_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `vakken`
--
ALTER TABLE `vakken`
  ADD CONSTRAINT `fk_vakken_opleiding1` FOREIGN KEY (`opleiding_id`) REFERENCES `opleiding` (`opleiding_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `fk_video_categorie1` FOREIGN KEY (`categorie_id`) REFERENCES `vakken` (`vak_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_video_gebruikers1` FOREIGN KEY (`docent_id`) REFERENCES `gebruikers` (`gebruiker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `views`
--
ALTER TABLE `views`
  ADD CONSTRAINT `fk_views_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

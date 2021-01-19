-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 19 jan 2021 om 13:05
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
-- Database: `spot`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `beoordeling`
--

CREATE TABLE `beoordeling` (
  `beoordeling_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruiker`
--

CREATE TABLE `gebruiker` (
  `gebruiker_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `voornaam` varchar(100) NOT NULL,
  `achternaam` varchar(100) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `level` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `gebruiker`
--

INSERT INTO `gebruiker` (`gebruiker_id`, `email`, `voornaam`, `achternaam`, `wachtwoord`, `level`) VALUES
(1, 'admin@nhlstenden.com', 'Administrator', ' ', '$2y$10$ljB1iZorlylHJ8HJ5Ibas.A9rr10cOUGGXTc10Cj9Nxe7g1.wcSBq', 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `opleiding`
--

CREATE TABLE `opleiding` (
  `opleiding_id` int(11) NOT NULL,
  `jaar` int(11) NOT NULL,
  `periode` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `opmerking`
--

CREATE TABLE `opmerking` (
  `opmerking_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `tekst` text NOT NULL,
  `datum` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `suggestie`
--

CREATE TABLE `suggestie` (
  `suggestie_id` int(11) NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `datum` datetime NOT NULL DEFAULT current_timestamp(),
  `link` text NOT NULL,
  `tekst` text NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tag`
--

CREATE TABLE `tag` (
  `tag_id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tag_video`
--

CREATE TABLE `tag_video` (
  `tag_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `vak`
--

CREATE TABLE `vak` (
  `vak_id` int(11) NOT NULL,
  `opleiding_id` int(11) NOT NULL,
  `vak_naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `video`
--

CREATE TABLE `video` (
  `video_id` int(11) NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `titel` varchar(255) NOT NULL,
  `playback_id` varchar(255) DEFAULT NULL,
  `videoPath` varchar(255) DEFAULT NULL,
  `videoLengte` int(11) DEFAULT NULL,
  `uploadDatum` datetime NOT NULL DEFAULT current_timestamp(),
  `views` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `video_vak`
--

CREATE TABLE `video_vak` (
  `video_id` int(11) NOT NULL,
  `vak_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `voortgang`
--

CREATE TABLE `voortgang` (
  `gebruiker_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `datum` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `beoordeling`
--
ALTER TABLE `beoordeling`
  ADD PRIMARY KEY (`beoordeling_id`,`gebruiker_id`,`video_id`),
  ADD KEY `fk_beoordeling_video1_idx` (`video_id`),
  ADD KEY `fk_beoordeling_gebruiker1_idx` (`gebruiker_id`);

--
-- Indexen voor tabel `gebruiker`
--
ALTER TABLE `gebruiker`
  ADD PRIMARY KEY (`gebruiker_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexen voor tabel `opleiding`
--
ALTER TABLE `opleiding`
  ADD PRIMARY KEY (`opleiding_id`);

--
-- Indexen voor tabel `opmerking`
--
ALTER TABLE `opmerking`
  ADD PRIMARY KEY (`opmerking_id`,`video_id`,`gebruiker_id`),
  ADD KEY `fk_opmerkingen_video1_idx` (`video_id`),
  ADD KEY `fk_opmerkingen_gebruikers1_idx` (`gebruiker_id`);

--
-- Indexen voor tabel `suggestie`
--
ALTER TABLE `suggestie`
  ADD PRIMARY KEY (`suggestie_id`,`gebruiker_id`),
  ADD KEY `fk_suggestie_gebruiker1` (`gebruiker_id`);

--
-- Indexen voor tabel `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`tag_id`) USING BTREE;

--
-- Indexen voor tabel `tag_video`
--
ALTER TABLE `tag_video`
  ADD PRIMARY KEY (`tag_id`,`video_id`),
  ADD KEY `fk_video_id` (`video_id`);

--
-- Indexen voor tabel `vak`
--
ALTER TABLE `vak`
  ADD PRIMARY KEY (`vak_id`,`opleiding_id`),
  ADD KEY `fk_vakken_opleiding1_idx` (`opleiding_id`);

--
-- Indexen voor tabel `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`video_id`,`gebruiker_id`),
  ADD UNIQUE KEY `playback_id_UNIQUE` (`playback_id`),
  ADD KEY `fk_video_gebruikers1_idx` (`gebruiker_id`);

--
-- Indexen voor tabel `video_vak`
--
ALTER TABLE `video_vak`
  ADD PRIMARY KEY (`video_id`,`vak_id`),
  ADD KEY `fk_vakid2_idx` (`vak_id`);

--
-- Indexen voor tabel `voortgang`
--
ALTER TABLE `voortgang`
  ADD PRIMARY KEY (`gebruiker_id`,`video_id`) USING BTREE,
  ADD KEY `fk_voortgang_video1_idx` (`video_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `beoordeling`
--
ALTER TABLE `beoordeling`
  MODIFY `beoordeling_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT voor een tabel `gebruiker`
--
ALTER TABLE `gebruiker`
  MODIFY `gebruiker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT voor een tabel `opleiding`
--
ALTER TABLE `opleiding`
  MODIFY `opleiding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `opmerking`
--
ALTER TABLE `opmerking`
  MODIFY `opmerking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT voor een tabel `suggestie`
--
ALTER TABLE `suggestie`
  MODIFY `suggestie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `tag`
--
ALTER TABLE `tag`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT voor een tabel `vak`
--
ALTER TABLE `vak`
  MODIFY `vak_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `video`
--
ALTER TABLE `video`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `beoordeling`
--
ALTER TABLE `beoordeling`
  ADD CONSTRAINT `fk_beoordeling_gebruikerid` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_beoordeling_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `opmerking`
--
ALTER TABLE `opmerking`
  ADD CONSTRAINT `fk_opmerkingen_gebruikers1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_opmerkingen_video1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `suggestie`
--
ALTER TABLE `suggestie`
  ADD CONSTRAINT `fk_suggestie_gebruiker1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `tag_video`
--
ALTER TABLE `tag_video`
  ADD CONSTRAINT `fk_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_video_id` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `vak`
--
ALTER TABLE `vak`
  ADD CONSTRAINT `fk_vakken_opleiding1` FOREIGN KEY (`opleiding_id`) REFERENCES `opleiding` (`opleiding_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `fk_video_gebruikers1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Beperkingen voor tabel `video_vak`
--
ALTER TABLE `video_vak`
  ADD CONSTRAINT `fk_vakid2` FOREIGN KEY (`vak_id`) REFERENCES `vak` (`vak_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_video_id2` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `voortgang`
--
ALTER TABLE `voortgang`
  ADD CONSTRAINT `fk_video_id3` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_voortgang_gebruiker1` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`gebruiker_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

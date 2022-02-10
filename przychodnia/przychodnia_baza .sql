-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 10 Lut 2022, 15:06
-- Wersja serwera: 10.1.38-MariaDB
-- Wersja PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `przychodnia_baza`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `lekarze`
--

CREATE TABLE `lekarze` (
  `id_lekarza` int(11) NOT NULL,
  `imie` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `nazwisko` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `specjalnosc` char(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `lekarze`
--

INSERT INTO `lekarze` (`id_lekarza`, `imie`, `nazwisko`, `specjalnosc`) VALUES
(1, 'Krzysztof', 'Nowak', 'kardiolog'),
(2, 'Anna', 'Kot', 'dermatolog'),
(3, 'Jan', 'Kowalski', 'dentysta'),
(4, 'Zofia', 'Lis', 'pediatra'),
(5, 'Jakub', 'Kowalczyk', 'pediatra');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pacjenci`
--

CREATE TABLE `pacjenci` (
  `id_pacjenta` int(11) NOT NULL,
  `imie` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `nazwisko` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `pesel` char(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `haslo` char(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `pacjenci`
--

INSERT INTO `pacjenci` (`id_pacjenta`, `imie`, `nazwisko`, `pesel`, `login`, `haslo`) VALUES
(1, 'Anna', 'Nowak', '97110376941', 'pacjent_testowy', '$2y$10$DA859vLUPE48s7/WauVYdeIQbECo1HCY6yzc4cDb6UW3APvJGDuFm'),
(2, 'Patryk', 'Kowalski', '86092326165', 'user123', '$2y$10$etTPNBeAAwOkvza5Hw2iJu8ChoFwCP9wbkroxfnc9TYJNbKz/uDO6'),
(3, 'Iwona', 'Kowalczyk', '63063079329', 'razdwa', '$2y$10$ylzBX8De5ilezRvUcIhUr.HDRWk1ZkmIj86.rheXckbduineTd9Mi'),
(4, 'Wiktor', 'Mak', '01310436888', 'wiktor123', '$2y$10$UarOVtHw9lrzSfSmAr4jtuemmR5/4WczF7noXJFVP2M9.S.EUazM.'),
(5, 'Izabela', 'Rebisz', '00283076923', 'Izabela', '$2y$10$niLCXUcvSUeaNTFCGs8KLuZ6lQUZ9Rdz/H1riL170NKXeTSZF7IK.'),
(6, 'Natalia', 'Szalas', '00281032127', 'Natalia', '$2y$10$HfwKIpWr/gU/4B9e/4B9u.8cuglVDP0ww0yINWm/LIiv3A/Dvs1pq');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wizyty`
--

CREATE TABLE `wizyty` (
  `id` int(11) NOT NULL,
  `id_lekarza` int(11) NOT NULL,
  `id_pacjenta` int(11) NOT NULL,
  `data` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `godzina` char(5) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `wizyty`
--

INSERT INTO `wizyty` (`id`, `id_lekarza`, `id_pacjenta`, `data`, `godzina`) VALUES
(4, 2, 2, '03/01/2022', '12:00'),
(5, 3, 1, '03/03/2022', '13:00'),
(6, 2, 4, '02/25/2022', '9:30'),
(7, 3, 4, '03/05/2022', '9:30'),
(9, 3, 3, '02/16/2022', '11:30'),
(13, 4, 5, '03/03/2022', '10:00'),
(14, 3, 6, '02/16/2022', '13:00'),
(15, 4, 6, '03/03/2022', '9:30'),
(66, 1, 5, '02/10/2022', '12:30'),
(67, 5, 6, '02/17/2022', '13:30'),
(69, 2, 5, '02/28/2022', '10:30'),
(83, 5, 5, '02/10/2022', '12:00');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `lekarze`
--
ALTER TABLE `lekarze`
  ADD PRIMARY KEY (`id_lekarza`);

--
-- Indeksy dla tabeli `pacjenci`
--
ALTER TABLE `pacjenci`
  ADD PRIMARY KEY (`id_pacjenta`);

--
-- Indeksy dla tabeli `wizyty`
--
ALTER TABLE `wizyty`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `lekarze`
--
ALTER TABLE `lekarze`
  MODIFY `id_lekarza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `pacjenci`
--
ALTER TABLE `pacjenci`
  MODIFY `id_pacjenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `wizyty`
--
ALTER TABLE `wizyty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

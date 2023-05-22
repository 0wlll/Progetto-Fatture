-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 22, 2023 alle 21:46
-- Versione del server: 10.4.27-MariaDB
-- Versione PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_acl`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `fatture`
--

CREATE TABLE `fatture` (
  `id` int(255) NOT NULL,
  `data` date NOT NULL,
  `utente` int(50) NOT NULL,
  `indirizzata` varchar(255) NOT NULL,
  `motivazioni` varchar(255) NOT NULL,
  `somma` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `fatture`
--

INSERT INTO `fatture` (`id`, `data`, `utente`, `indirizzata`, `motivazioni`, `somma`) VALUES
(37, '2023-05-22', 46, 'fermi', 'gita scolastica', 450),
(38, '2023-05-22', 43, 'federico tommasini', 'disboscamento palude', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ruolo` varchar(15) NOT NULL DEFAULT 'Cliente',
  `area` varchar(20) DEFAULT NULL,
  `portafoglio_di` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `cognome`, `email`, `password`, `ruolo`, `area`, `portafoglio_di`) VALUES
(42, 'mattia', 'vidhi', 'mattia.vidhi@ptpvenezia.edu.it', '$2y$10$KDpjbNha.qBxV8f/8bQwRu3yt5drlTLtieh11a1.TecszVrbvmvcm', 'Admin', NULL, NULL),
(43, 'francesco', 'tommasini', 'francesco.tommasini@ptpvenezia.edu.it', '$2y$10$.F79l7dExqgPa/5aOdT5HOFKifZNqi3CJJ51tfnMHhlDfbW8pytGm', 'Capo_area', 'nord', NULL),
(45, 'federico', 'tommasini', 'federico.tommasini@ptpvenezia.edu.it', '$2y$10$WB0JHBbUP56QRKAIm..2WuwF1uyFS3ROtmnvt7CvtD2y1gNP.MOSC', 'Commerciale', 'nord', NULL),
(46, 'mattia', 'bonaldo', 'mattia.bonaldo@ptpvenezia.edu.it', '$2y$10$NEuilXiqUKtpY5TOsX39U.CM4JIp4ChzviX4iNx6x7NvKlG.b4i4W', 'Cliente', 'sud', '45');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `fatture`
--
ALTER TABLE `fatture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posseduta` (`utente`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `fatture`
--
ALTER TABLE `fatture`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `fatture`
--
ALTER TABLE `fatture`
  ADD CONSTRAINT `posseduta` FOREIGN KEY (`utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
--   ^...^
--  / o,o \
--  |):::(|
-- ===w=w===
--

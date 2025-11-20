-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 25, 2025 at 09:24 AM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sql_raspinha_pix`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banner_img` varchar(255) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `ordem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `banner_img`, `ativo`, `ordem`) VALUES
(1, '/assets/banners/banner_687cd3a026c04.png', 1, 2),
(2, '/assets/banners/banner_687cd3bbbc2cb.png', 1, 1),
(3, '/assets/banners/banner_687cd323dee39.png', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome_site` varchar(255) DEFAULT 'Raspadinha',
  `logo` varchar(255) DEFAULT NULL,
  `deposito_min` float NOT NULL DEFAULT 0,
  `saque_min` float NOT NULL DEFAULT 0,
  `cpa_padrao` float NOT NULL DEFAULT 0,
  `revshare_padrao` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `nome_site`, `logo`, `deposito_min`, `saque_min`, `cpa_padrao`, `revshare_padrao`) VALUES
(1, 'RaspaSorte', '/assets/upload/687c0e19a5cb8.png', 10, 10, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `depositos`
--

CREATE TABLE `depositos` (
  `id` int(11) NOT NULL,
  `transactionId` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `status` enum('PENDING','PAID') DEFAULT 'PENDING',
  `qrcode` text DEFAULT NULL,
  `idempotency_key` varchar(255) DEFAULT NULL,
  `gateway` enum('pixup','digitopay','gatewayproprio') NOT NULL,
  `webhook_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `depositos`
--

INSERT INTO `depositos` (`id`, `transactionId`, `user_id`, `nome`, `cpf`, `valor`, `status`, `qrcode`, `idempotency_key`, `gateway`, `webhook_data`, `created_at`, `updated_at`) VALUES
(8, 'e6bf7f6669aea688e946b70b69ffur5bs', 4, 'Yarkan Marley', '06664868598', 50.00, 'PAID', '00020101021226880014br.gov.bcb.pix2566qrcode.microcashif.com.br/pix/f24eaca7-d001-46af-8957-ca8045f747bb5204000053039865802BR5924WITEPAY SOLUCOES EM PAGA6015CORONEL FABRICI62070503***630474D5', '6882d63a86044-1753404986', 'gatewayproprio', NULL, '2025-07-25 00:56:28', '2025-07-25 00:56:59');

-- --------------------------------------------------------

--
-- Table structure for table `digitopay`
--

CREATE TABLE `digitopay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT 'https://api.digitopayoficial.com.br',
  `client_id` varchar(255) NOT NULL,
  `client_secret` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `digitopay`
--

INSERT INTO `digitopay` (`id`, `url`, `client_id`, `client_secret`, `created_at`, `updated_at`) VALUES
(1, 'https://api.digitopayoficial.com.br', '422e8de3-f566-4999-b47b-89d08c479903', '61fb272e-a8b0-4a20-89f5-711b86409ed5', '2025-07-19 12:56:52', '2025-07-22 16:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `gateway`
--

CREATE TABLE `gateway` (
  `id` int(10) UNSIGNED NOT NULL,
  `active` enum('pixup','digitopay','gatewayproprio') DEFAULT 'pixup',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gateway`
--

INSERT INTO `gateway` (`id`, `active`, `created_at`, `updated_at`) VALUES
(1, 'gatewayproprio', '2025-07-11 13:39:55', '2025-07-24 21:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `gatewayproprio`
--

CREATE TABLE `gatewayproprio` (
  `id` int(10) UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gatewayproprio`
--

INSERT INTO `gatewayproprio` (`id`, `url`, `api_key`, `created_at`, `updated_at`) VALUES
(1, 'https://hyperpagamentos.com', 'Yarkan_1232664919', '2025-07-24 21:26:43', '2025-07-24 21:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `historico_revshare`
--

CREATE TABLE `historico_revshare` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `valor_apostado` decimal(10,2) NOT NULL,
  `valor_revshare` decimal(10,2) NOT NULL,
  `percentual` float NOT NULL,
  `tipo` enum('perda_usuario','ganho_usuario') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `raspadinha_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `resultado` enum('loss','gain') DEFAULT NULL,
  `valor_ganho` decimal(10,2) DEFAULT 0.00,
  `premios_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`premios_json`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pixup`
--

CREATE TABLE `pixup` (
  `id` int(10) UNSIGNED NOT NULL,
  `ci` varchar(255) NOT NULL,
  `cs` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pixup`
--

INSERT INTO `pixup` (`id`, `ci`, `cs`, `url`, `created_at`, `updated_at`) VALUES
(1, 'andrevieiraa7x_8273634962755747', '177949d02e9a0d9a630c583f383e1ebb11f915fe919f586b56f08a5da9e0e1b8', 'https://api.bspay.co', '2025-07-11 13:41:14', '2025-07-21 16:02:34');

-- --------------------------------------------------------

--
-- Table structure for table `raspadinhas`
--

CREATE TABLE `raspadinhas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(120) NOT NULL,
  `descricao` text DEFAULT NULL,
  `banner` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raspadinhas`
--

INSERT INTO `raspadinhas` (`id`, `nome`, `descricao`, `banner`, `valor`, `created_at`) VALUES
(1, 'SONHO PREMIADO - R$ 2,00 - PRÊMIOS DE ATÉ R$5.000,00 ', 'Com só R$2, você raspa e pode levar prêmios exclusivos, gadgets, ou R$5000 na conta.', '/assets/img/banners/687ce7f33afe8.png', 2.00, '2025-07-11 21:55:04'),
(2, 'MEGA RASPADA BLACK 🖤💰 - R$10,00 - PRÊMIOS DE ATÉ R$20.000,00', 'Com R$10 na raspada você ativa a chance de faturar uma bolada até R$20.000. Prêmio bruto, imediato.', '/assets/img/banners/687ce824a04ed.png', 10.00, '2025-07-11 21:55:04'),
(3, '🔥 PIX TURBINADO - R$ 1,00 - PRÊMIOS DE ATÉ R$2.500,00', 'Raspa por apenas R$1 e pode explodir até R$2500 direto no PIX.', '/assets/img/banners/687ce7af59f64.png', 1.00, '2025-07-16 19:19:31'),
(4, 'OSTENTAÇÃO INSTANTÂNEA 💎 - R$5,00 - PRÊMIOS DE ATÉ R$10.000,00', 'R$5 pra raspar e a chance real de garantir eletrônicos top ou até R$10.000 em PIX.', '/assets/img/banners/687cea40caafd.png', 5.00, '2025-07-19 18:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `raspadinha_premios`
--

CREATE TABLE `raspadinha_premios` (
  `id` int(10) UNSIGNED NOT NULL,
  `raspadinha_id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(120) NOT NULL,
  `icone` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `probabilidade` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raspadinha_premios`
--

INSERT INTO `raspadinha_premios` (`id`, `raspadinha_id`, `nome`, `icone`, `valor`, `probabilidade`) VALUES
(29, 4, 'NADA 😬', '/assets/img/icons/687c106fb01ac.png', 0.00, 30.00),
(30, 4, 'R$1,00 NO PIX', '/assets/img/icons/687c09ddc2027.png', 1.00, 18.00),
(31, 4, 'R$5,00 NO PIX', '/assets/img/icons/687c09f749f8b.png', 5.00, 12.00),
(32, 4, 'R$10,00 NO PIX', '/assets/img/icons/687c0a1e0b378.png', 10.00, 8.00),
(33, 4, 'R$15,00 NO PIX', '/assets/img/icons/687c24d23eed0.png', 15.00, 6.00),
(34, 4, 'R$20,00 NO PIX', '/assets/img/icons/687c0b01a04a4.png', 20.00, 4.50),
(35, 4, 'R$50,00 NO PIX', '/assets/img/icons/687c0b433da67.png', 50.00, 4.00),
(36, 4, 'R$100,00 NO PIX', '/assets/img/icons/687c0dbbb87e4.png', 100.00, 3.00),
(37, 4, 'R$150,00 NO PIX', '/assets/img/icons/687c263842548.png', 150.00, 2.20),
(38, 4, 'R$200,00 NO PIX', '/assets/img/icons/687c0c3f09c6d.png', 200.00, 2.10),
(39, 4, 'Cafeteira Expresso Dolce Gusto', '/assets/img/icons/687c0c9a1f22a.png', 500.00, 2.00),
(40, 4, 'Lava e Seca Samsung', '/assets/img/icons/687c0cc6bb984.png', 3500.00, 0.40),
(41, 4, 'Notebook Gamer ', '/assets/img/icons/687cd625b0136.png', 4000.00, 1.50),
(42, 4, 'Smart TV Samsung 70\"', '/assets/img/icons/687c0d36c8044.png', 5000.00, 1.40),
(43, 4, 'R$1.000,00 NO PIX', '/assets/img/icons/687c0f4e1f147.png', 1000.00, 1.90),
(44, 4, 'R$3.000,00 NO PIX', '/assets/img/icons/687c0f6ac9a5e.png', 3000.00, 1.60),
(45, 4, 'iPhone 15 PRO MAX', '/assets/img/icons/687c0fe6b612a.png', 6000.00, 0.75),
(46, 4, 'R$10.000,00 NO PIX', '/assets/img/icons/687c1030df2ef.png', 10000.00, 0.30),
(47, 3, 'NADA 😬', '/assets/img/icons/687c0254729ef.png', 0.00, 25.00),
(48, 3, 'R$1,00 NO PIX', '/assets/img/icons/687be92f11610.png', 1.00, 15.00),
(49, 3, 'R$2,00 NO PIX', '/assets/img/icons/687bea587e903.png', 2.00, 11.00),
(50, 3, 'R$5,00 NO PIX', '/assets/img/icons/687bfdd13689e.png', 5.00, 9.00),
(51, 3, 'R$10,00 NO PIX', '/assets/img/icons/687beabea5f53.png', 10.00, 7.70),
(52, 3, 'R$20,00 NO PIX', '/assets/img/icons/687beaf761686.png', 20.00, 6.00),
(53, 3, 'R$15,00 NO PIX', '/assets/img/icons/687c248f70bc8.png', 15.00, 7.50),
(54, 3, 'R$50,00 NO PIX', '/assets/img/icons/687bfad6bca49.png', 50.00, 5.30),
(55, 3, 'TV 32 polegadas Smart', '/assets/img/icons/687be97e55304.png', 1000.00, 1.80),
(56, 3, 'JBL BOOMBOX 3', '/assets/img/icons/687bfb8a5b1c6.png', 2000.00, 0.20),
(57, 3, 'R$1.500,00 NO PIX', '/assets/img/icons/687be9cb1abad.png', 1500.00, 1.50),
(58, 3, 'R$2.500,00 NO PIX', '/assets/img/icons/687bfc8ee5723.png', 2500.00, 0.10),
(59, 1, 'NADA 😬', '/assets/img/icons/687c0272d42cc.png', 0.00, 30.00),
(60, 1, 'R$1,00 NO PIX', '/assets/img/icons/687c029628796.png', 1.00, 15.00),
(61, 1, 'R$5,00 NO PIX', '/assets/img/icons/687c036f22866.png', 5.00, 10.00),
(62, 1, 'R$10,00 NO PIX', '/assets/img/icons/687c072e05d74.png', 10.00, 8.00),
(63, 1, 'R$15,00 NO PIX', '/assets/img/icons/687c24eeda1dd.png', 15.00, 7.00),
(64, 1, 'R$20,00 NO PIX', '/assets/img/icons/687cfac0cda45.png', 20.00, 6.00),
(65, 1, 'R$50,00 NO PIX', '/assets/img/icons/687c032bd36c5.png', 50.00, 4.00),
(66, 1, 'Air Fryer Britânia', '/assets/img/icons/687c03ea8c3b5.png', 400.00, 2.00),
(67, 1, 'Microondas', '/assets/img/icons/687c041d18e2f.png', 500.00, 2.00),
(68, 1, 'R$500,00 NO PIX', '/assets/img/icons/687c07b350a5b.png', 500.00, 4.00),
(69, 1, 'Bicicleta Caloi', '/assets/img/icons/687c046b401b4.png', 800.00, 2.00),
(70, 1, 'Xbox Series S', '/assets/img/icons/687c04dea9970.png', 2000.00, 2.50),
(71, 1, 'R$1.200,00 NO PIX', '/assets/img/icons/687c050c8fc53.png', 1200.00, 2.00),
(72, 1, 'R$2.000,00 NO PIX', '/assets/img/icons/687c055b21ca9.png', 2000.00, 1.50),
(73, 1, 'Shineray PT2X', '/assets/img/icons/687c0598a13d0.png', 5000.00, 1.00),
(74, 2, 'NADA 😬', '/assets/img/icons/687c10c6b1667.png', 0.00, 30.00),
(77, 2, 'R$5,00 NO PIX', '/assets/img/icons/687c114fee310.png', 5.00, 13.00),
(78, 2, 'R$20,00 NO PIX', '/assets/img/icons/687c11ee2bc98.png', 20.00, 6.50),
(79, 2, 'R$15,00 NO PIX', '/assets/img/icons/687c251dd30ab.png', 15.00, 8.00),
(80, 2, 'R$50,00 NO PIX', '/assets/img/icons/687c124f3477d.png', 50.00, 6.00),
(81, 2, 'R$100,00 NO PIX', '/assets/img/icons/687c127d17125.png', 100.00, 3.50),
(82, 2, 'R$200,00 NO PIX', '/assets/img/icons/687c12c9570a1.png', 200.00, 2.50),
(83, 2, 'R$300,00 NO PIX', '/assets/img/icons/687c2d8e3beef.png', 300.00, 2.00),
(84, 2, 'R$500,00 NO PIX', '/assets/img/icons/687c14d2bfc79.png', 500.00, 2.00),
(85, 2, 'R$700,00 NO PIX', '/assets/img/icons/687c169784b00.png', 700.00, 1.80),
(86, 2, 'R$1.000,00 NO PIX', '/assets/img/icons/687c16bf8d4f9.png', 1000.00, 1.40),
(87, 2, 'R$3.000,00 NO PIX', '/assets/img/icons/687c1499d7b9f.png', 3000.00, 1.00),
(88, 2, 'R$5.000,00 NO PIX', '/assets/img/icons/687c17441f4e7.png', 10.00, 0.80),
(89, 2, 'Geladeira Smart LG', '/assets/img/icons/687c17c36902a.png', 9000.00, 0.60),
(90, 2, 'iPhone 16 Pro Max ', '/assets/img/icons/687c17f0a903b.png', 7500.00, 0.00),
(91, 2, 'Moto Honda Pop 110i zero km', '/assets/img/icons/687c1814b5ef1.png', 12500.00, 0.00),
(92, 2, 'MacBook Pro Apple 14\" M4', '/assets/img/icons/687c184b06fd6.png', 14000.00, 0.00),
(93, 2, 'Honda PCX 2025 ', '/assets/img/icons/687c18722f07a.png', 20000.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `saques`
--

CREATE TABLE `saques` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transactionId` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `transaction_id_digitopay` varchar(255) DEFAULT NULL,
  `idempotency_key` varchar(255) DEFAULT NULL,
  `digitopay_idempotency_key` varchar(255) DEFAULT NULL,
  `gateway` varchar(50) DEFAULT 'pixup',
  `webhook_data` text DEFAULT NULL,
  `status` enum('PENDING','PAID','CANCELLED','FAILED','PROCESSING','EM PROCESSAMENTO','ANALISE','REALIZADO') DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tipo` enum('DEPOSIT','WITHDRAW','REFUND') NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `saldo_anterior` decimal(10,2) NOT NULL,
  `saldo_posterior` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `gateway` varchar(50) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transacoes_afiliados`
--

CREATE TABLE `transacoes_afiliados` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `deposito_id` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `saldo` decimal(10,2) DEFAULT 0.00,
  `indicacao` varchar(100) DEFAULT NULL,
  `comissao_cpa` float DEFAULT 0,
  `comissao_revshare` float DEFAULT 0,
  `banido` tinyint(1) DEFAULT 0,
  `admin` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `influencer` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `telefone`, `email`, `senha`, `saldo`, `indicacao`, `comissao_cpa`, `comissao_revshare`, `banido`, `admin`, `created_at`, `updated_at`, `influencer`) VALUES
(4, 'Yarkan Marley', '(84) 99959-1257', 'yarkanpessoal@gmail.com', '$2y$10$jsPJcxFl8uWGtQhur3.NQOoWYZULDwl.rwHqXrVWl/x9uGnhnTGwG', 59.00, '', 0, 10, 0, 1, '2025-07-19 19:30:32', '2025-07-25 00:56:59', 1),
(470, 'Dunga almeida', '(41) 98394-0293', 'pilda2@gmail.com', '$2y$10$oxsSQl7B0bBgpBNTq.CkGugNxAdzt2njywBk.o3cuNf4lpK2OWZFS', 0.00, '', 0, 10, 0, 0, '2025-07-25 00:35:13', '2025-07-25 00:35:13', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_banners_ativo_ordem` (`ativo`,`ordem`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `depositos`
--
ALTER TABLE `depositos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idempotency_key` (`idempotency_key`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `digitopay`
--
ALTER TABLE `digitopay`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateway`
--
ALTER TABLE `gateway`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gatewayproprio`
--
ALTER TABLE `gatewayproprio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `historico_revshare`
--
ALTER TABLE `historico_revshare`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pixup`
--
ALTER TABLE `pixup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raspadinhas`
--
ALTER TABLE `raspadinhas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raspadinha_premios`
--
ALTER TABLE `raspadinha_premios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raspadinha_id` (`raspadinha_id`);

--
-- Indexes for table `saques`
--
ALTER TABLE `saques`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idx_saques_transaction_id` (`transaction_id`),
  ADD KEY `idx_saques_idempotency_key` (`idempotency_key`),
  ADD KEY `idx_saques_gateway` (`gateway`);

--
-- Indexes for table `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transacoes_afiliados`
--
ALTER TABLE `transacoes_afiliados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `afiliado_id` (`afiliado_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `deposito_id` (`deposito_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `depositos`
--
ALTER TABLE `depositos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `digitopay`
--
ALTER TABLE `digitopay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `historico_revshare`
--
ALTER TABLE `historico_revshare`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9520;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19312;

--
-- AUTO_INCREMENT for table `pixup`
--
ALTER TABLE `pixup`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `raspadinhas`
--
ALTER TABLE `raspadinhas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `raspadinha_premios`
--
ALTER TABLE `raspadinha_premios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `saques`
--
ALTER TABLE `saques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `transacoes_afiliados`
--
ALTER TABLE `transacoes_afiliados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=471;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `raspadinha_premios`
--
ALTER TABLE `raspadinha_premios`
  ADD CONSTRAINT `raspadinha_premios_ibfk_1` FOREIGN KEY (`raspadinha_id`) REFERENCES `raspadinhas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transacoes_afiliados`
--
ALTER TABLE `transacoes_afiliados`
  ADD CONSTRAINT `transacoes_afiliados_ibfk_1` FOREIGN KEY (`afiliado_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `transacoes_afiliados_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `transacoes_afiliados_ibfk_3` FOREIGN KEY (`deposito_id`) REFERENCES `depositos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

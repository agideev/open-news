-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 05, 2026 at 07:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `open_news_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_usage`
--

CREATE TABLE `ai_usage` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `usage_date` date NOT NULL,
  `credits_used` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `daily_limit` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `audio` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `summary`, `content`, `image`, `audio`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Moçambique anuncia novos investimentos em tecnologia', 'mocambique-anuncia-novos-investimentos-em-tecnologia', 'Novos investimentos prometem impulsionar o setor tecnológico e criar oportunidades para jovens profissionais.', 'O setor tecnológico de Moçambique poderá receber novos investimentos nos próximos meses. A iniciativa pretende apoiar startups, empresas de tecnologia e jovens profissionais que trabalham com desenvolvimento de software e inovação digital.', '/uploads/news/mocambique-anuncia-novos-investimentos-em-tecnologia_08d5f9e8.jpg', '/uploads/news/audios/mocambique-anuncia-novos-investimentos-em-tecnologia_audio_86b493b1.mp3', 'published', '2026-10-04 06:20:47', '2026-10-04 11:20:47', '2026-10-04 11:40:22'),
(2, 'Jovens moçambicanos ganham destaque no desenvolvimento de software', 'jovens-mocambicanos-ganham-destaque-no-desenvolvimento-de-software', 'Programadores moçambicanos estão criando novas soluções digitais para empresas e comunidades.', 'Uma nova geração de programadores moçambicanos está ganhando espaço no mercado de tecnologia. Projetos desenvolvidos localmente começam a atender necessidades de empresas, instituições e comunidades em diferentes regiões do país.', '/uploads/news/jovens-mocambicanos-ganham-destaque-no-desenvolvimento-de-software_dc869791.jpg', '/uploads/news/audios/jovens-mocambicanos-ganham-destaque-no-desenvolvimento-de-software_audio_61c79cd6.mp3', 'published', '2026-10-04 06:20:47', '2026-10-04 11:20:47', '2026-10-04 11:41:35'),
(3, 'Internet móvel continua a crescer em Moçambique', 'internet-movel-continua-a-crescer-em-mocambique', 'O acesso à internet através de dispositivos móveis continua aumentando entre os utilizadores moçambicanos.', 'O uso da internet através de smartphones continua crescendo em Moçambique. O aumento do acesso a dispositivos móveis e a expansão das redes de comunicação estão contribuindo para uma maior presença digital da população.', '/uploads/news/internet-movel-continua-a-crescer-em-mocambique_4f47bbb5.webp', '/uploads/news/audios/internet-movel-continua-a-crescer-em-mocambique_audio_2ef9ea46.mp3', 'published', '2026-10-04 06:20:47', '2026-10-04 11:20:47', '2026-10-04 11:42:39'),
(4, 'Empreendedores apostam em negócios digitais', 'empreendedores-apostam-em-negocios-digitais', 'Pequenos empreendedores estão utilizando plataformas digitais para alcançar novos clientes.', 'O comércio digital está se tornando uma alternativa cada vez mais utilizada por pequenos empreendedores. Redes sociais, lojas online e plataformas de comunicação permitem que negócios locais alcancem clientes sem depender exclusivamente de estabelecimentos físicos.', '/uploads/news/empreendedores-apostam-em-negocios-digitais_a00c71f7.jpg', '/uploads/news/audios/empreendedores-apostam-em-negocios-digitais_audio_eb4aa387.mp3', 'published', '2026-10-04 06:20:47', '2026-10-04 11:20:47', '2026-10-04 11:43:42'),
(5, 'Tecnologia pode transformar a educação nos próximos anos', 'tecnologia-pode-transformar-a-educacao-nos-proximos-anos', 'Ferramentas digitais estão sendo utilizadas para melhorar o acesso ao conhecimento e apoiar estudantes.', 'O avanço das tecnologias digitais pode transformar a forma como estudantes aprendem. Plataformas online, aplicações educativas e ferramentas de inteligência artificial estão criando novas possibilidades para professores e alunos.', '/uploads/news/tecnologia-pode-transformar-a-educacao-nos-proximos-anos_771b6ba1.jpg', '/uploads/news/audios/tecnologia-pode-transformar-a-educacao-nos-proximos-anos_audio_4d685169.mp3', 'published', '2026-10-04 06:20:47', '2026-10-04 11:20:47', '2026-10-04 11:44:53'),
(6, 'F-22 Raptor realiza demonstração inédita durante exercício militar', 'f-22-raptor-realiza-demonstrac-ao-in-edita-durante-exerc-icio-militar', 'Os Estados Unidos realizaram um novo exercício militar com o caça F-22 Raptor para testar comunicação, sensores e capacidades de combate em diferentes cenários de defesa aérea.', 'Os Estados Unidos realizaram um novo exercício militar envolvendo o caça F-22 Raptor. A operação teve como objetivo testar sistemas de comunicação, sensores e capacidades de combate da aeronave. Segundo as autoridades, o exercício permitiu avaliar o desempenho do caça em diferentes cenários de defesa aérea. Especialistas destacam que a integração entre aeronaves e sistemas de vigilância continua sendo uma prioridade para a aviação militar americana. As autoridades não divulgaram detalhes sobre os equipamentos utilizados durante os testes.\r\n', '/uploads/news/f-22-raptor-realiza-demonstrac-ao-in-edita-durante-exerc-icio-militar_64f8c717.webp', '/uploads/news/audios/f-22-raptor-realiza-demonstrac-ao-in-edita-durante-exerc-icio-militar_audio_962d43ea.mp3', 'published', '2026-10-05 06:29:00', '2026-10-04 17:45:14', '2026-10-05 14:51:02'),
(7, 'GTA 6 ganha destaque com novidades sobre seu aguardado lançamento', 'gta-6-ganha-destaque-com-novidades-sobre-seu-aguardado-lancamento', 'GTA 6 continua entre os jogos mais aguardados, gerando grande expectativa entre os fãs da franquia.', 'O aguardado GTA 6 continua despertando enorme interesse entre jogadores de todo o mundo. Desenvolvido pela Rockstar Games, o título promete apresentar um mapa amplo, novos personagens e uma experiência mais detalhada em comparação com os jogos anteriores da franquia.\r\n\r\nA expectativa dos fãs aumentou após a divulgação de informações e materiais oficiais relacionados ao projeto. O jogo será ambientado em Vice City, trazendo uma nova história e uma dupla de protagonistas.\r\n\r\nA Rockstar ainda mantém muitos detalhes em segredo, mas a comunidade espera novas informações sobre jogabilidade, missões e recursos do mundo aberto. O lançamento é considerado um dos eventos mais importantes da indústria dos games.', '/uploads/news/gta-6-ganha-destaque-com-novidades-sobre-seu-aguardado-lancamento_68d03b73.webp', '/uploads/news/audios/gta-6-ganha-destaque-com-novidades-sobre-seu-aguardado-lancamento_audio_de74875f.mp3', 'published', '2026-10-05 09:52:00', '2026-10-05 14:54:07', '2026-10-05 14:54:07'),
(8, 'Inteligência Artificial avança e reacende debate sobre o futuro dos programadores', 'intelig-encia-artificial-avanca-e-reacende-debate-sobre-o-futuro-dos-programadores', 'O avanço da IA na programação aumenta a produtividade, mas também gera preocupações sobre possíveis mudanças no mercado de trabalho.', 'O avanço das ferramentas de inteligência artificial capazes de escrever, corrigir e analisar código está transformando o mercado de programação. Sistemas de IA já conseguem desenvolver funções, encontrar erros e auxiliar na criação de aplicações em poucos minutos.\r\n\r\nEsse progresso reacende o debate sobre se a tecnologia poderá substituir programadores no futuro. Especialistas, porém, destacam que a IA ainda depende de profissionais para definir requisitos, revisar código, tomar decisões técnicas e compreender as necessidades de cada projeto.\r\n\r\nEm vez de eliminar completamente a profissão, a tendência pode transformar o trabalho dos programadores, tornando habilidades como arquitetura de software, resolução de problemas e capacidade de trabalhar com inteligência artificial cada vez mais importantes.', '/uploads/news/intelig-encia-artificial-avanca-e-reacende-debate-sobre-o-futuro-dos-programadores_038fad7a.jpg', '/uploads/news/audios/intelig-encia-artificial-avanca-e-reacende-debate-sobre-o-futuro-dos-programadores_audio_be9ec59a.mp3', 'published', '2026-10-05 09:56:00', '2026-10-05 14:57:28', '2026-10-05 14:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `news_favorites`
--

CREATE TABLE `news_favorites` (
  `id` int(10) UNSIGNED NOT NULL,
  `news_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_favorites`
--

INSERT INTO `news_favorites` (`id`, `news_id`, `user_id`, `created_at`) VALUES
(3, 8, 1, '2026-10-05 16:49:11'),
(4, 6, 1, '2026-10-05 16:49:16');

-- --------------------------------------------------------

--
-- Table structure for table `news_images`
--

CREATE TABLE `news_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `news_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(60) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `whatsapp` varchar(30) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `avatar`, `bio`, `whatsapp`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin', 'admin@gmail.com', '$2y$10$aWHiKNWmLnS7IKi4JEYbJOmiKp9tNzOJ260tjFJuqXCVaAF9EZHiq', NULL, NULL, NULL, 'admin', '2026-10-04 06:18:11', '2026-10-04 06:18:11'),
(2, 'agiabudo', 'agiabudo', 'nova@gmail.com', '$2y$10$LgxiL.w4.8Xn8C1eTJwbSOQcEFBD2TOmZXTK7wZtq5vsHIXZq9Oj.', NULL, NULL, NULL, 'user', '2026-10-05 10:01:41', '2026-10-05 10:01:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_usage`
--
ALTER TABLE `ai_usage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_date` (`user_id`,`usage_date`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_news_slug` (`slug`);

--
-- Indexes for table `news_favorites`
--
ALTER TABLE `news_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_news_user` (`news_id`,`user_id`),
  ADD KEY `fk_news_favorites_user` (`user_id`);

--
-- Indexes for table `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_images_news` (`news_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_username` (`username`),
  ADD UNIQUE KEY `uniq_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_usage`
--
ALTER TABLE `ai_usage`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `news_favorites`
--
ALTER TABLE `news_favorites`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_usage`
--
ALTER TABLE `ai_usage`
  ADD CONSTRAINT `fk_ai_usage_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_favorites`
--
ALTER TABLE `news_favorites`
  ADD CONSTRAINT `fk_news_favorites_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_news_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_images`
--
ALTER TABLE `news_images`
  ADD CONSTRAINT `fk_news_images_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

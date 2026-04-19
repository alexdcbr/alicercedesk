-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para alicercedesk
DROP DATABASE IF EXISTS `alicercedesk`;
CREATE DATABASE IF NOT EXISTS `alicercedesk` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `alicercedesk`;

-- Copiando estrutura para tabela alicercedesk.anexos
DROP TABLE IF EXISTS `anexos`;
CREATE TABLE IF NOT EXISTS `anexos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chamado_id` int DEFAULT NULL,
  `nome_arquivo` varchar(255) DEFAULT NULL,
  `caminho` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mensagem_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chamado_id` (`chamado_id`),
  KEY `mensagem_id` (`mensagem_id`),
  CONSTRAINT `anexos_ibfk_1` FOREIGN KEY (`chamado_id`) REFERENCES `chamados` (`id`),
  CONSTRAINT `anexos_ibfk_2` FOREIGN KEY (`mensagem_id`) REFERENCES `mensagens_chamado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela alicercedesk.anexos: ~2 rows (aproximadamente)
DELETE FROM `anexos`;
INSERT INTO `anexos` (`id`, `chamado_id`, `nome_arquivo`, `caminho`, `criado_em`, `mensagem_id`) VALUES
	(1, 4, 'Escova_Cabelo.png', 'uploads/1776470169_Escova_Cabelo.png', '2026-04-17 23:56:09', NULL),
	(2, 4, 'kit_teclado_mouse.png', 'uploads/1776470642_kit_teclado_mouse.png', '2026-04-18 00:04:02', 3);

-- Copiando estrutura para tabela alicercedesk.chamados
DROP TABLE IF EXISTS `chamados`;
CREATE TABLE IF NOT EXISTS `chamados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `assunto` varchar(255) DEFAULT NULL,
  `descricao` text,
  `status` enum('aberto','pendente','resolvido') DEFAULT 'aberto',
  `solicitante_id` int DEFAULT NULL,
  `atendente_id` int DEFAULT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `resolvido_em` datetime DEFAULT NULL,
  `primeira_resposta_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `solicitante_id` (`solicitante_id`),
  KEY `atendente_id` (`atendente_id`),
  CONSTRAINT `chamados_ibfk_1` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `chamados_ibfk_2` FOREIGN KEY (`atendente_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela alicercedesk.chamados: ~5 rows (aproximadamente)
DELETE FROM `chamados`;
INSERT INTO `chamados` (`id`, `assunto`, `descricao`, `status`, `solicitante_id`, `atendente_id`, `criado_em`, `resolvido_em`, `primeira_resposta_em`) VALUES
	(1, 'Instalar o sistema na máquina', 'Preciso instalar o sistema CCL na minha máquina, pois o computador foi formatado.', 'resolvido', 1, 1, '2026-04-13 23:45:28', NULL, NULL),
	(2, 'teste de abertura de chamado', 'testando a abertura de um chamado.', 'pendente', 2, 3, '2026-04-14 00:00:44', NULL, NULL),
	(3, 'erro com calculo de devolução', 'Estou fazendo a devolução de um contrato mas ao realizar o cálculos os valores não estão batendo pode me auxiliar com a devolução?', 'aberto', 1, NULL, '2026-04-17 23:47:38', NULL, NULL),
	(4, 'Não estou conseguindo gerar boletos', 'Bom Dia,\r\n\r\nAo gerar um boleto para uma determinada fatura de um contrato está dando o erro da imagem em anexo.\r\n\r\nPoderiam me auxiliar o cliente está esperando o envio do boleto.\r\n\r\nObrigado.', 'aberto', 1, NULL, '2026-04-17 23:56:09', NULL, NULL),
	(5, 'dificuldades em faturar um contrato', 'Boa Tarde, \r\n\r\nEstou com dificuldades em faturar um contrato, ao entrar na tela de faturamento o contrato em questão não aparece para faturar.\r\n\r\nPoderiam me auxiliar a realizar este faturamento.\r\n\r\nObrigado.', 'aberto', 1, NULL, '2026-04-18 00:25:11', NULL, NULL);

-- Copiando estrutura para tabela alicercedesk.mensagens_chamado
DROP TABLE IF EXISTS `mensagens_chamado`;
CREATE TABLE IF NOT EXISTS `mensagens_chamado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chamado_id` int DEFAULT NULL,
  `remetente_id` int DEFAULT NULL,
  `mensagem` text,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `chamado_id` (`chamado_id`),
  KEY `remetente_id` (`remetente_id`),
  CONSTRAINT `mensagens_chamado_ibfk_1` FOREIGN KEY (`chamado_id`) REFERENCES `chamados` (`id`),
  CONSTRAINT `mensagens_chamado_ibfk_2` FOREIGN KEY (`remetente_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela alicercedesk.mensagens_chamado: ~4 rows (aproximadamente)
DELETE FROM `mensagens_chamado`;
INSERT INTO `mensagens_chamado` (`id`, `chamado_id`, `remetente_id`, `mensagem`, `criado_em`) VALUES
	(1, 1, 1, 'Por gentileza passe o ID do seu temviwer para que seja possível instalar e mantenha o teamviewer aberto.', '2026-04-13 23:46:43'),
	(2, 3, 1, 'poderia por gentileza enviar um print da tela com as informações relativas a devolução?', '2026-04-17 23:48:36'),
	(3, 4, 1, 'Foi realizado um ajuste na sua configuração de conta corrente por gentileza proceda com os testes segue um exemplo de geração com a correção.', '2026-04-18 00:04:02'),
	(4, 5, 1, 'poderia por gentileza dar maiores detalhes sobre ete faturamento?', '2026-04-18 00:26:17');

-- Copiando estrutura para tabela alicercedesk.usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `perfil` enum('admin','agente','cliente') DEFAULT 'cliente',
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela alicercedesk.usuarios: ~3 rows (aproximadamente)
DELETE FROM `usuarios`;
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `criado_em`) VALUES
	(1, 'Admin', 'admin@teste.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin', '2026-04-13 23:40:56'),
	(2, 'Cliente', 'cliente@teste.com', 'e10adc3949ba59abbe56e057f20f883e', 'cliente', '2026-04-13 23:59:12'),
	(3, 'Agente', 'agente@teste.com', 'e10adc3949ba59abbe56e057f20f883e', 'agente', '2026-04-13 23:59:12');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Tempo de geração: 31/07/2018 às 08:55
-- Versão do servidor: 5.7.21-0ubuntu0.16.04.1
-- Versão do PHP: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cmda`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria_produto`
--

CREATE TABLE `categoria_produto` (
  `id_categoria` int(11) NOT NULL,
  `nome_categoria` varchar(80) NOT NULL,
  `pizza` int(11) NOT NULL DEFAULT '0',
  `fg_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `categoria_produto`
--

INSERT INTO `categoria_produto` (`id_categoria`, `nome_categoria`, `pizza`, `fg_ativo`) VALUES
(1, 'Adicionais', 0, 1),
(2, 'Pizza', 1, 1),
(3, 'Bebidas', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cidade`
--

CREATE TABLE `cidade` (
  `id_cidade` int(11) NOT NULL,
  `nome_cidade` varchar(120) NOT NULL,
  `estado` varchar(60) NOT NULL,
  `sigla_estado` varchar(2) NOT NULL,
  `fg_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nome_cliente` int(11) NOT NULL,
  `email` int(11) NOT NULL,
  `telefone` int(11) NOT NULL,
  `endereco` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `cep` int(11) NOT NULL,
  `complemento` int(11) NOT NULL,
  `id_cidade` int(11) NOT NULL,
  `fg_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comanda`
--

CREATE TABLE `comanda` (
  `id_comanda` int(11) NOT NULL,
  `ref_comanda` varchar(200) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `data_comanda` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observacao` text,
  `mesa` int(11) DEFAULT NULL,
  `tipo_comanda` int(11) NOT NULL DEFAULT '1',
  `id_cliente_viagem` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `comanda`
--

INSERT INTO `comanda` (`id_comanda`, `ref_comanda`, `status`, `data_comanda`, `observacao`, `mesa`, `tipo_comanda`, `id_cliente_viagem`) VALUES
(1, 'CMD01', 1, '2018-07-03 17:00:00', '', 12, 1, NULL),
(2, 'CMD02', 1, '2018-07-14 17:38:08', 'Testes', 5, 1, NULL),
(3, 'CMD03', 1, '2018-07-14 17:44:19', 'Mesa de teste 2', 11, 1, NULL),
(4, 'CMD04', 1, '2018-07-14 17:49:51', 'Elias Portela', 8, 1, NULL),
(5, 'CMD05', 1, '2018-07-15 15:30:07', 'Cliente Lucas', 3, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `comanda_produto`
--

CREATE TABLE `comanda_produto` (
  `id_comanda_produto` int(11) NOT NULL,
  `id_comanda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `data_pedido` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_pedido` int(11) NOT NULL DEFAULT '1',
  `quantidade` decimal(10,1) NOT NULL,
  `valor_produto` decimal(10,2) NOT NULL,
  `id_tabela_produto` int(11) NOT NULL,
  `observacao` text,
  `fg_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `comanda_produto`
--

INSERT INTO `comanda_produto` (`id_comanda_produto`, `id_comanda`, `id_produto`, `data_pedido`, `status_pedido`, `quantidade`, `valor_produto`, `id_tabela_produto`, `observacao`, `fg_ativo`) VALUES
(62, 4, 4, '2018-07-18 00:04:13', 1, '1.0', '0.00', 8, NULL, 1),
(63, 4, 15, '2018-07-18 00:04:13', 1, '1.0', '0.00', 12, 'Adicionais: Milho||Remoções: S/ Tomate', 1),
(64, 4, 16, '2018-07-18 00:04:47', 1, '1.0', '0.00', 13, 'Remoções: S/ Cebola', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_produto`
--

CREATE TABLE `item_produto` (
  `id_produto` int(11) NOT NULL,
  `id_produto_item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `item_produto`
--

INSERT INTO `item_produto` (`id_produto`, `id_produto_item`) VALUES
(12, 1),
(12, 2),
(13, 1),
(13, 3),
(14, 1),
(14, 4),
(14, 3),
(12, 8),
(15, 1),
(15, 5),
(15, 2),
(16, 1),
(16, 5),
(16, 2),
(16, 6),
(16, 3),
(16, 7),
(18, 1),
(18, 9),
(18, 6),
(32, 1),
(32, 5),
(32, 11),
(32, 3),
(32, 6),
(19, 9),
(19, 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(200) NOT NULL,
  `ref_produto` varchar(200) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `gerar_pedido` int(1) NOT NULL DEFAULT '1',
  `fg_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome_produto`, `ref_produto`, `id_categoria`, `gerar_pedido`, `fg_ativo`) VALUES
(1, 'Mozzarella', '1001', 1, 1, 1),
(2, 'Tomate', '1002', 1, 1, 1),
(3, 'Catupiry', '1003', 1, 1, 1),
(4, 'Milho', '1004', 1, 1, 1),
(5, 'Presunto', '1005', 1, 1, 1),
(6, 'Cebola', '1006', 1, 1, 1),
(7, 'Azeitonas Verdes', '1007', 1, 1, 1),
(8, 'Chedder', '1008', 1, 1, 1),
(9, 'Calabresa', '1009', 1, 1, 1),
(10, 'Pimenta Calabresa', '1010', 1, 1, 1),
(11, 'Parmesão', '1011', 1, 1, 1),
(12, 'Mozzarella', '01', 2, 1, 1),
(13, 'Mozzarella c/ Catupiry', '02', 2, 1, 1),
(14, 'Mozzarella c/ Milho e Catupiry', '03', 2, 1, 1),
(15, 'Bauru', '04', 2, 1, 1),
(16, 'Bauru c/ Catupiry', '05', 2, 1, 1),
(17, 'Bauru c/ Chedder', '06', 2, 1, 1),
(18, 'Calabresa Mineira', '07', 2, 1, 1),
(19, 'Calabresa Baiana', '08', 2, 1, 1),
(20, 'Calabresa Paulista', '09', 2, 1, 1),
(21, 'Calacatu', '10', 2, 1, 1),
(22, 'Ervilha', 'r1', 1, 1, 1),
(24, 'Ovos', 'r3', 1, 1, 1),
(25, 'Palmito', 'r6', 1, 1, 1),
(27, 'Lombo Canadense', 'r10', 1, 1, 1),
(29, 'Bacon', 'r937372', 1, 1, 1),
(30, 'Peito de Frango Desfiado', 'R171934', 1, 1, 1),
(31, 'Provolone', 'R393223', 1, 1, 1),
(32, 'Napolitana', '11', 2, 1, 1),
(33, 'Portuguesa', 'R033', 2, 1, 1),
(35, 'Lombinho', 'R035', 2, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tabela_preco`
--

CREATE TABLE `tabela_preco` (
  `id_tabela` int(11) NOT NULL,
  `nome_tabela` varchar(60) NOT NULL,
  `fg_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `tabela_preco`
--

INSERT INTO `tabela_preco` (`id_tabela`, `nome_tabela`, `fg_ativo`) VALUES
(1, 'Esfiha', 1),
(2, 'Broto', 1),
(3, 'Grande', 1),
(4, 'Unico', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tabela_produto`
--

CREATE TABLE `tabela_produto` (
  `id_tabela_produto` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_tabela` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `tabela_produto`
--

INSERT INTO `tabela_produto` (`id_tabela_produto`, `id_produto`, `id_tabela`, `valor`) VALUES
(1, 12, 2, '18.00'),
(2, 12, 3, '20.00'),
(3, 13, 2, '24.40'),
(4, 13, 3, '30.00'),
(5, 1, 4, '4.00'),
(6, 2, 4, '4.00'),
(7, 3, 4, '4.00'),
(8, 4, 4, '4.00'),
(9, 5, 4, '4.00'),
(10, 12, 1, '14.20'),
(11, 15, 3, '28.00'),
(12, 15, 2, '20.00'),
(13, 16, 3, '30.00'),
(14, 16, 2, '20.00'),
(15, 18, 3, '30.00'),
(16, 18, 2, '20.00'),
(17, 32, 3, '30.00'),
(18, 32, 2, '20.00'),
(19, 19, 2, '20.00'),
(20, 19, 3, '30.00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `administrativo` int(11) NOT NULL DEFAULT '0',
  `fg_ativo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `administrativo`, `fg_ativo`) VALUES
(1, 'Administrador', 'admin', '$2y$10$XC2SLplSTIHdfWttkxQ4i.5xfh34rKNBDmMwD17Dj4EQJ0U6MQQH2', 3, 1);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `categoria_produto`
--
ALTER TABLE `categoria_produto`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `cidade`
--
ALTER TABLE `cidade`
  ADD PRIMARY KEY (`id_cidade`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_cidade` (`id_cidade`);

--
-- Índices de tabela `comanda`
--
ALTER TABLE `comanda`
  ADD PRIMARY KEY (`id_comanda`),
  ADD UNIQUE KEY `ref_comanda` (`ref_comanda`),
  ADD KEY `id_cliente_viagem` (`id_cliente_viagem`);

--
-- Índices de tabela `comanda_produto`
--
ALTER TABLE `comanda_produto`
  ADD PRIMARY KEY (`id_comanda_produto`),
  ADD KEY `id_comanda` (`id_comanda`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_tabela_produto` (`id_tabela_produto`);

--
-- Índices de tabela `item_produto`
--
ALTER TABLE `item_produto`
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_produto_item` (`id_produto_item`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`),
  ADD UNIQUE KEY `ref_produto` (`ref_produto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices de tabela `tabela_preco`
--
ALTER TABLE `tabela_preco`
  ADD PRIMARY KEY (`id_tabela`);

--
-- Índices de tabela `tabela_produto`
--
ALTER TABLE `tabela_produto`
  ADD PRIMARY KEY (`id_tabela_produto`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_tabela` (`id_tabela`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `categoria_produto`
--
ALTER TABLE `categoria_produto`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `cidade`
--
ALTER TABLE `cidade`
  MODIFY `id_cidade` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `comanda`
--
ALTER TABLE `comanda`
  MODIFY `id_comanda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de tabela `comanda_produto`
--
ALTER TABLE `comanda_produto`
  MODIFY `id_comanda_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT de tabela `tabela_preco`
--
ALTER TABLE `tabela_preco`
  MODIFY `id_tabela` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de tabela `tabela_produto`
--
ALTER TABLE `tabela_produto`
  MODIFY `id_tabela_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id_cidade`);

--
-- Restrições para tabelas `comanda`
--
ALTER TABLE `comanda`
  ADD CONSTRAINT `comanda_ibfk_2` FOREIGN KEY (`id_cliente_viagem`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `comanda_produto`
--
ALTER TABLE `comanda_produto`
  ADD CONSTRAINT `comanda_produto_ibfk_1` FOREIGN KEY (`id_comanda`) REFERENCES `comanda` (`id_comanda`),
  ADD CONSTRAINT `comanda_produto_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `comanda_produto_ibfk_3` FOREIGN KEY (`id_tabela_produto`) REFERENCES `tabela_produto` (`id_tabela_produto`);

--
-- Restrições para tabelas `item_produto`
--
ALTER TABLE `item_produto`
  ADD CONSTRAINT `item_produto_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `item_produto_ibfk_2` FOREIGN KEY (`id_produto_item`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria_produto` (`id_categoria`);

--
-- Restrições para tabelas `tabela_produto`
--
ALTER TABLE `tabela_produto`
  ADD CONSTRAINT `tabela_produto_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `tabela_produto_ibfk_2` FOREIGN KEY (`id_tabela`) REFERENCES `tabela_preco` (`id_tabela`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

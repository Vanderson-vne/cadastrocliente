-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Set-2021 às 18:57
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dbsysab8`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `banco`
--

CREATE TABLE `banco` (
  `idbanco` bigint(20) UNSIGNED NOT NULL,
  `idempresa` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agencia` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `digito_agencia` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conta` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `digito_conta` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carteira` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `multa` decimal(4,2) DEFAULT NULL,
  `juros` decimal(4,2) DEFAULT NULL,
  `jurosapos` decimal(4,2) DEFAULT NULL,
  `prazo_protesto` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc_demonstrativo1` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc_demonstrativo2` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc_demonstrativo3` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instrucao1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instrucao2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instrucao3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_cliente` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_inscr_empresa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_inscr_empresa` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_convenio_banco` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequencia` int(11) DEFAULT NULL,
  `versao` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_arquivo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idremessa` int(11) DEFAULT NULL,
  `idretorno` int(11) DEFAULT NULL,
  `nosso_numero` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ambiente` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_cedente` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path_remessa` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path_retorno` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefixo_cooperativa` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `digito_prefixo` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `especie_titulo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `byte_idt` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `situacao` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ultima_remessa` bigint(20) DEFAULT NULL,
  `contador_remessa` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `banco`
--

INSERT INTO `banco` (`idbanco`, `idempresa`, `codigo`, `nome`, `agencia`, `digito_agencia`, `conta`, `digito_conta`, `carteira`, `multa`, `juros`, `jurosapos`, `prazo_protesto`, `logo`, `desc_demonstrativo1`, `desc_demonstrativo2`, `desc_demonstrativo3`, `instrucao1`, `instrucao2`, `instrucao3`, `codigo_cliente`, `tipo_inscr_empresa`, `nro_inscr_empresa`, `cod_convenio_banco`, `sequencia`, `versao`, `tipo_arquivo`, `idremessa`, `idretorno`, `nosso_numero`, `ambiente`, `cod_cedente`, `path_remessa`, `path_retorno`, `prefixo_cooperativa`, `digito_prefixo`, `especie_titulo`, `saldo`, `byte_idt`, `posto`, `created_at`, `updated_at`, `situacao`, `ultima_remessa`, `contador_remessa`) VALUES
(1, 1, '001', 'Caixa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '108278.54', NULL, NULL, NULL, NULL, 'Caixa', NULL, NULL),
(2, 1, '748', 'Sicred', '123', '0', '1010', '0', '1', '0.00', '0.00', '0.00', '5', NULL, NULL, NULL, NULL, 'Sr. Caixa, cobrar multa de 2% após o vencimento', 'Receber até 10 dias após o vencimento', 'Em caso de dúvidas entre em contato conosco: suporte@powercorp.com.br', '111', '01', '01', '123', NULL, '1', NULL, 0, 0, NULL, NULL, '123456', NULL, 'p', '748', '11', NULL, '0.00', '1', '11', NULL, NULL, 'Banco', 4, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `detalhe_locacao`
--

CREATE TABLE `detalhe_locacao` (
  `iddetalhe_locacao` bigint(20) UNSIGNED NOT NULL,
  `idlocacao` bigint(20) UNSIGNED NOT NULL,
  `idevento` bigint(20) UNSIGNED NOT NULL,
  `complemento` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qtde` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `mes_ano_det` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qtde_limite` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `detalhe_locacao`
--

INSERT INTO `detalhe_locacao` (`iddetalhe_locacao`, `idlocacao`, `idevento`, `complemento`, `qtde`, `valor`, `mes_ano_det`, `qtde_limite`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, '1000.00', NULL, NULL, NULL, NULL),
(2, 1, 2, NULL, NULL, '100.00', NULL, NULL, NULL, NULL),
(3, 2, 1, NULL, NULL, '5000.00', '01/2021', NULL, NULL, NULL),
(4, 2, 2, NULL, NULL, '200.00', NULL, NULL, NULL, NULL),
(5, 3, 1, NULL, NULL, '1980.00', NULL, NULL, NULL, NULL),
(6, 3, 2, NULL, NULL, '180.00', NULL, NULL, NULL, NULL),
(8, 1, 3, NULL, '0', '50.00', NULL, NULL, NULL, NULL),
(10, 4, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(11, 4, 2, NULL, NULL, '200.00', NULL, NULL, NULL, NULL),
(12, 5, 1, NULL, NULL, '5500.00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `detalhe_recibo`
--

CREATE TABLE `detalhe_recibo` (
  `iddetalhe_recibo` bigint(20) UNSIGNED NOT NULL,
  `idrecibo` bigint(20) UNSIGNED NOT NULL,
  `idevento` bigint(20) UNSIGNED NOT NULL,
  `complemento` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qtde` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `mes_ano_det` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qtde_limite` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `detalhe_recibo`
--

INSERT INTO `detalhe_recibo` (`iddetalhe_recibo`, `idrecibo`, `idevento`, `complemento`, `qtde`, `valor`, `mes_ano_det`, `qtde_limite`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, '1000.00', '02/2021', NULL, NULL, NULL),
(2, 1, 2, NULL, NULL, '100.00', '02/2021', NULL, NULL, NULL),
(3, 1, 3, NULL, NULL, '200.00', '02/2021', NULL, NULL, NULL),
(6, 3, 1, NULL, NULL, '5000.00', '02/2021', NULL, NULL, NULL),
(7, 3, 2, NULL, NULL, '200.00', '02/2021', NULL, NULL, NULL),
(8, 3, 4, NULL, NULL, '869.36', NULL, NULL, NULL, NULL),
(9, 4, 1, NULL, NULL, '5000.00', '01/2021', NULL, NULL, NULL),
(10, 4, 2, NULL, NULL, '200.00', NULL, NULL, NULL, NULL),
(11, 4, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(12, 5, 1, NULL, NULL, '1980.00', '09/2021', NULL, NULL, NULL),
(13, 5, 2, NULL, NULL, '180.00', '09/2021', NULL, NULL, NULL),
(14, 6, 1, NULL, NULL, '1980.00', NULL, NULL, NULL, NULL),
(15, 6, 2, NULL, NULL, '180.00', NULL, NULL, NULL, NULL),
(16, 6, 4, NULL, NULL, '5.70', NULL, NULL, NULL, NULL),
(18, 6, 3, NULL, NULL, '50.00', '10/2021', NULL, NULL, NULL),
(19, 7, 1, NULL, NULL, '1980.00', NULL, NULL, NULL, NULL),
(20, 7, 2, NULL, NULL, '180.00', NULL, NULL, NULL, NULL),
(22, 8, 1, NULL, NULL, '5000.00', '02/2021', NULL, NULL, NULL),
(23, 8, 2, NULL, NULL, '200.00', '02/2021', NULL, NULL, NULL),
(24, 8, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(25, 9, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(26, 9, 2, NULL, NULL, '200.00', NULL, NULL, NULL, NULL),
(27, 9, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(29, 10, 1, NULL, NULL, '5000.00', '02/2021', NULL, NULL, NULL),
(30, 10, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(31, 11, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(33, 12, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(34, 12, 2, NULL, NULL, '200.00', NULL, NULL, NULL, NULL),
(35, 12, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(36, 13, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(37, 13, 2, NULL, NULL, '200.00', NULL, NULL, NULL, NULL),
(38, 13, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(44, 16, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(45, 16, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(46, 17, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(47, 17, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(48, 18, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(49, 18, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(50, 19, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(51, 19, 4, NULL, NULL, '505.64', NULL, NULL, NULL, NULL),
(52, 20, 1, NULL, NULL, '1980.00', NULL, NULL, NULL, NULL),
(53, 20, 2, NULL, NULL, '180.00', NULL, NULL, NULL, NULL),
(55, 21, 1, NULL, NULL, '1980.00', NULL, NULL, NULL, NULL),
(56, 21, 2, NULL, NULL, '180.00', NULL, NULL, NULL, NULL),
(57, 22, 1, NULL, NULL, '1980.00', NULL, NULL, NULL, NULL),
(58, 22, 2, NULL, NULL, '180.00', NULL, NULL, NULL, NULL),
(62, 24, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(63, 25, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(64, 26, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(65, 27, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(66, 28, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(67, 29, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(68, 30, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(69, 31, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(70, 32, 1, NULL, NULL, '5000.00', NULL, NULL, NULL, NULL),
(71, 33, 1, NULL, NULL, '5500.00', NULL, NULL, NULL, NULL),
(72, 34, 1, NULL, NULL, '5000.00', '01/2021', NULL, NULL, NULL),
(73, 34, 2, NULL, NULL, '200.00', NULL, NULL, NULL, NULL),
(74, 35, 1, NULL, NULL, '5000.00', '01/2021', NULL, NULL, NULL),
(75, 35, 2, NULL, NULL, '200.00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa`
--

CREATE TABLE `empresa` (
  `idempresa` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fantasia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsavel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creci` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banco_padrao_boleto` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gera_todos_boletos` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conta_caixa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transacao_caixa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `empresa`
--

INSERT INTO `empresa` (`idempresa`, `nome`, `fantasia`, `endereco`, `bairro`, `cidade`, `estado`, `cep`, `cnpj`, `responsavel`, `cpf`, `creci`, `email`, `banco_padrao_boleto`, `telefone`, `created_at`, `updated_at`, `gera_todos_boletos`, `conta_caixa`, `transacao_caixa`) VALUES
(1, 'SYSAB-Web', 'SYSAB-Web', 'Rua Tenenete Jose Luis Soares,74', 'Jardim Alvinopolis', NULL, 'SP', '12943-430', '01.862.735/0001-97', 'Power Software Informatica', '712.136.356-91', '123456', 'power@powercorp.com.br', '748', '(11) 98604-4826', NULL, NULL, 'Nao', '1', '1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `evento`
--

CREATE TABLE `evento` (
  `idevento` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comissao` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pede_qtde` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unidade` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indice_cc` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `irrf` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imp_recibo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cont_c_deb_cred` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comissao_iptu` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `libera_cc` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agrupamento` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boleto` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `evento`
--

INSERT INTO `evento` (`idevento`, `nome`, `comissao`, `pede_qtde`, `unidade`, `tipo`, `indice_cc`, `irrf`, `imp_recibo`, `cont_c_deb_cred`, `comissao_iptu`, `libera_cc`, `agrupamento`, `boleto`, `created_at`, `updated_at`) VALUES
(1, 'Aluguel', 'Sim', 'Nao', '', 'Credito', 'Sim', 'Sim', 'Sim', NULL, 'Sim', 'Sim', '1', 'Sim', NULL, NULL),
(2, 'IPTU', 'Sim', 'Nao', '', 'Credito', 'Nao', 'Sim', 'Sim', NULL, 'Sim', 'Sim', '1', 'Sim', NULL, NULL),
(3, 'Abono', 'Nao', 'Nao', '', 'Debito', 'Nao', 'Sim', 'Sim', NULL, 'Sim', 'Sim', '1', 'Sim', NULL, NULL),
(4, 'I.R.F.', 'Nao', 'Nao', '', 'Debito', 'Nao', 'Sim', 'Nao', NULL, 'Nao', 'Nao', '1', 'Sim', NULL, NULL),
(5, 'Correção de Aluguel', 'Sim', 'Sim', NULL, 'Credito', 'Sim', 'Sim', 'Sim', NULL, 'Sim', NULL, NULL, 'Sim', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fiador`
--

CREATE TABLE `fiador` (
  `idfiador` bigint(20) UNSIGNED NOT NULL,
  `idinquilino` bigint(20) UNSIGNED NOT NULL,
  `idmunicipio` bigint(20) UNSIGNED NOT NULL,
  `tipo_pessoa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fantasia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fisica_juridica` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf_cnpj` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referencia` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obs` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg_ie` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicao` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conjuge` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aos_cuidados` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_corr` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_corr` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `compl_corr` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro_corr` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade_corr` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf_corr` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep_corr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf_conj` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg_conj` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `fiador`
--

INSERT INTO `fiador` (`idfiador`, `idinquilino`, `idmunicipio`, `tipo_pessoa`, `nome`, `fantasia`, `fisica_juridica`, `cpf_cnpj`, `endereco`, `telefone`, `email`, `complemento`, `bairro`, `cidade`, `uf`, `cep`, `referencia`, `obs`, `rg_ie`, `condicao`, `conjuge`, `aos_cuidados`, `end_corr`, `num_corr`, `compl_corr`, `bairro_corr`, `cidade_corr`, `uf_corr`, `cep_corr`, `cpf_conj`, `rg_conj`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Fiador', 'Jose Alves', 'Alves', 'Fisica', '71213635691', 'x', NULL, NULL, NULL, NULL, 'x', NULL, '2', NULL, NULL, '23', 'Ativo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_padraos`
--

CREATE TABLE `historico_padraos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idempresa` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `historico` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agrupamento` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `historico_padraos`
--

INSERT INTO `historico_padraos` (`id`, `idempresa`, `codigo`, `historico`, `agrupamento`, `created_at`, `updated_at`) VALUES
(1, 1, '1', 'Historico Padrao', '1', NULL, NULL),
(2, 1, '2', 'Pagto de Aluguel', 'Pagto', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `imovel`
--

CREATE TABLE `imovel` (
  `idimovel` bigint(20) UNSIGNED NOT NULL,
  `idproprietario` bigint(20) UNSIGNED NOT NULL,
  `idmunicipio` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referencia` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obs` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicao` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `situacao` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idinquilino` int(11) DEFAULT NULL,
  `idlocacao` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `imovel`
--

INSERT INTO `imovel` (`idimovel`, `idproprietario`, `idmunicipio`, `nome`, `endereco`, `complemento`, `bairro`, `cidade`, `uf`, `cep`, `referencia`, `obs`, `condicao`, `codigo`, `situacao`, `idinquilino`, `idlocacao`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Imovel Padrao', 'Endereço', '', '', '', '', '', 'Padrao', '', '', '', 'Vago', NULL, NULL, NULL, NULL),
(2, 1, 1, 'Residencial', 'Rua Tenente Jose Luis Soares,74', 'Jardim Alvinopolis', NULL, 'Atibaia', 'SP', NULL, NULL, NULL, 'Ativo', 'C001', 'Alugado', 1, 2, NULL, NULL),
(3, 1, 1, 'Residencial', 'Av Paulista, 50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ativo', 'R002', 'Vago', 0, 0, NULL, NULL),
(4, 1, 1, 'Comercial', 'Ruas das Flores', NULL, 'Centro', 'Atibaia', 'SP', '12943000', NULL, NULL, 'Ativo', 'R003', 'Vago', 0, 0, NULL, NULL),
(5, 1, 1, 'Residencial', 'Av. Dona Gertrudes,100', NULL, 'Centro', 'Atibaia', 'SP', '1294000', NULL, NULL, 'Ativo', 'R005', 'Vago', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `indice`
--

CREATE TABLE `indice` (
  `idindice` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `indice`
--

INSERT INTO `indice` (`idindice`, `nome`, `created_at`, `updated_at`) VALUES
(1, 'IGPM', NULL, NULL),
(2, 'III', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `inquilino`
--

CREATE TABLE `inquilino` (
  `idinquilino` bigint(20) UNSIGNED NOT NULL,
  `idproprietario` bigint(20) UNSIGNED NOT NULL,
  `idimovel` bigint(20) UNSIGNED NOT NULL,
  `idmunicipio` bigint(20) UNSIGNED NOT NULL,
  `tipo_pessoa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fantasia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fisica_juridica` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf_cnpj` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referencia` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obs` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg_ie` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicao` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conjuge` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aos_cuidados` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_corr` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_corr` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `compl_corr` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro_corr` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade_corr` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf_corr` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep_corr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favorecido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf_fav` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banco_fav` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ag_fav` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conta_fav` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ult_extrato` datetime DEFAULT NULL,
  `data_ult_extrato` datetime DEFAULT NULL,
  `irrf` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locacao_encerada` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dt_enc_locacao` datetime DEFAULT NULL,
  `ult_recibo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idlocacao` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `inquilino`
--

INSERT INTO `inquilino` (`idinquilino`, `idproprietario`, `idimovel`, `idmunicipio`, `tipo_pessoa`, `nome`, `fantasia`, `fisica_juridica`, `cpf_cnpj`, `endereco`, `telefone`, `email`, `complemento`, `bairro`, `cidade`, `uf`, `cep`, `referencia`, `obs`, `rg_ie`, `condicao`, `conjuge`, `aos_cuidados`, `end_corr`, `num_corr`, `compl_corr`, `bairro_corr`, `cidade_corr`, `uf_corr`, `cep_corr`, `favorecido`, `cpf_fav`, `banco_fav`, `ag_fav`, `conta_fav`, `ult_extrato`, `data_ult_extrato`, `irrf`, `locacao_encerada`, `dt_enc_locacao`, `ult_recibo`, `idlocacao`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 2, 2, 1, 'Inquilino', 'Maria Lanzoni', 'Maria', NULL, '71213635691', 'Rua das Flores,74', '11986044826', 'maria@gmail.com', NULL, NULL, 'Atibaia', 'SP', '12940000', NULL, NULL, NULL, 'Ativo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 4),
(2, 1, 1, 1, 'Inquilino', 'Gabriel Lanzoni', NULL, 'Juridica', '71213635691', 'Av Paulista, 50', '+551144181345', 'gabi@gmail.com', NULL, NULL, 'Atibaia', 'SP', '12940000', NULL, NULL, NULL, 'Inativo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 5),
(3, 1, 1, 1, 'Inquilino', 'Pedro Paulo', 'Pedro', 'Fisica', '71213635691', 'Rua Tenente Jose Luis Soares,512', '11986044826', 'pedro@gmail.com', NULL, NULL, 'Atibaia', 'SP', '12943430', NULL, NULL, '23', 'Inativo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 6),
(4, 1, 1, 1, 'Inquilino', 'Gustavo', 'Gu', 'Fisica', '71213635691', 'AV Santana, 100', NULL, 'gustavo@gmail.com', NULL, 'centro', 'Atibaia', 'SP', 'R003', NULL, NULL, '23', 'Inativo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `lacto_indice`
--

CREATE TABLE `lacto_indice` (
  `idlacto_indice` bigint(20) UNSIGNED NOT NULL,
  `idindice` bigint(20) UNSIGNED NOT NULL,
  `mes_ano` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(10,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `lacto_indice`
--

INSERT INTO `lacto_indice` (`idlacto_indice`, `idindice`, `mes_ano`, `valor`, `created_at`, `updated_at`) VALUES
(1, 1, '09/2021', '1.0000', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `locacao`
--

CREATE TABLE `locacao` (
  `idlocacao` bigint(20) UNSIGNED NOT NULL,
  `idinquilino` bigint(20) UNSIGNED NOT NULL,
  `idproprietario` bigint(20) UNSIGNED NOT NULL,
  `idimovel` bigint(20) UNSIGNED NOT NULL,
  `idindice` bigint(20) UNSIGNED NOT NULL,
  `dt_inicial` date NOT NULL,
  `dt_final` date NOT NULL,
  `reajuste` int(11) NOT NULL,
  `contador_aluguel` int(11) NOT NULL,
  `reajuste_sobre` decimal(10,2) NOT NULL,
  `vencimento` date NOT NULL,
  `taxa_adm` decimal(10,2) NOT NULL,
  `desocupacao` date DEFAULT NULL,
  `estado` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mes_ano` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dt_ini_contrato` date NOT NULL,
  `dt_fin_contrato` date NOT NULL,
  `codigo` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `locacao`
--

INSERT INTO `locacao` (`idlocacao`, `idinquilino`, `idproprietario`, `idimovel`, `idindice`, `dt_inicial`, `dt_final`, `reajuste`, `contador_aluguel`, `reajuste_sobre`, `vencimento`, `taxa_adm`, `desocupacao`, `estado`, `mes_ano`, `dt_ini_contrato`, `dt_fin_contrato`, `codigo`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2, 1, '2021-02-01', '2021-02-28', 12, 2, '1000.00', '2021-03-05', '10.00', NULL, 'Inativo', '03/2021', '2021-01-01', '2022-12-31', '0', NULL, NULL),
(2, 1, 2, 2, 1, '2021-04-01', '2021-04-30', 12, 4, '5000.00', '2021-05-05', '10.00', NULL, 'Ativo', '05/2021', '2021-01-01', '2022-12-31', '0', NULL, NULL),
(3, 2, 1, 3, 1, '2022-02-01', '2022-02-28', 12, 7, '1980.00', '2022-03-15', '10.00', '2021-09-25', 'Inativo', '03/2022', '2021-08-01', '2022-08-30', '0', NULL, NULL),
(4, 3, 2, 4, 1, '2021-04-01', '2021-04-30', 12, 4, '5000.00', '2021-05-05', '10.00', '2021-09-25', 'Inativo', '05/2021', '2021-01-01', '2022-12-31', '0', NULL, NULL),
(5, 4, 2, 5, 1, '2022-01-01', '2022-01-31', 12, 1, '5500.00', '2022-02-01', '10.00', '2021-09-25', 'Inativo', '02/2022', '2021-01-01', '2022-12-31', '0', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_08_04_183114_create_empresa_table', 1),
(5, '2020_08_04_183333_create_evento_table', 1),
(6, '2020_08_04_183747_create_indice_table', 1),
(7, '2020_08_04_191029_create_municipio_table', 1),
(8, '2020_08_04_191311_create_banco_table', 1),
(9, '2020_08_04_193144_create_proprietario_table', 1),
(10, '2020_08_04_193431_create_reajuste_table', 1),
(11, '2020_08_04_193702_create_lacto_indice_table', 1),
(12, '2020_08_04_201021_create_imovel_table', 1),
(13, '2020_08_04_201251_create_inquilino_table', 1),
(14, '2020_08_04_201328_create_locacao_table', 1),
(15, '2020_08_04_201559_create_fiador_table', 1),
(16, '2020_08_04_201738_create_detalhe_locacao_table', 1),
(17, '2020_08_04_201853_create_recibo_table', 1),
(18, '2020_08_04_201926_create_detalhe_recibo_table', 1),
(19, '2020_08_06_175044_add_nomeremessa_to_recibo', 1),
(20, '2020_08_19_150733_add_situacao_to_banco', 1),
(21, '2020_08_25_114130_add_gera_todos_boletos_to_empresa', 1),
(22, '2020_08_25_142238_create_transacao_table', 1),
(23, '2020_09_17_093404_create_mov_contas_table', 1),
(24, '2020_11_26_113200_create_remessas_table', 1),
(25, '2020_11_26_113756_add_ultima_remessa_to_banco', 1),
(26, '2020_11_26_115452_remove_ideremessa_to_recibo', 1),
(27, '2020_11_26_115523_add_forain_key_to_recibo', 1),
(28, '2021_02_10_173805_create_plano_contas_table', 1),
(29, '2021_02_16_101050_create_movimentacaos_table', 1),
(30, '2021_02_16_101247_add_conta_caixa_to_empresa', 1),
(31, '2021_02_18_132834_create_historico_padraos_table', 1),
(32, '2021_02_18_150823_add_historico_to_mov_contas', 1),
(33, '2021_02_18_151527_add_historico_to_movimentacaos', 1),
(34, '2021_02_27_104533_add_idrecibo_to_mov_contas', 1),
(35, '2021_09_09_104057_create_permission_tables', 1),
(36, '2021_09_09_161724_add_userid_to_proprietario', 1),
(37, '2021_09_09_161801_add_userid_to_inquilino', 1),
(38, '2021_09_16_131112_create_tabela_irs_table', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 1),
(1, 'App\\User', 2),
(3, 'App\\User', 3),
(4, 'App\\User', 4),
(4, 'App\\User', 5),
(4, 'App\\User', 6),
(4, 'App\\User', 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimentacaos`
--

CREATE TABLE `movimentacaos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idempresa` bigint(20) UNSIGNED NOT NULL,
  `idinquilino` bigint(20) UNSIGNED DEFAULT NULL,
  `idproprietario` bigint(20) UNSIGNED DEFAULT NULL,
  `idbanco` bigint(20) UNSIGNED DEFAULT NULL,
  `idmov_contas` bigint(20) UNSIGNED DEFAULT NULL,
  `idtransacao` bigint(20) UNSIGNED DEFAULT NULL,
  `idevento` bigint(20) UNSIGNED DEFAULT NULL,
  `idlocacao` bigint(20) UNSIGNED DEFAULT NULL,
  `idrecibo` bigint(20) UNSIGNED DEFAULT NULL,
  `idplano_conta` bigint(20) UNSIGNED DEFAULT NULL,
  `conta` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `mes_ano` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `historico` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documento` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `complemento` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comissao` decimal(10,2) DEFAULT NULL,
  `incide_caixa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caixa_rec_pag` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incide_conta_cor` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Tipo_d_c` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_lacto` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nominal` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `predatado` datetime DEFAULT NULL,
  `compensado` datetime DEFAULT NULL,
  `ult_extrato` int(11) DEFAULT NULL,
  `parcial` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idhistorico` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `movimentacaos`
--

INSERT INTO `movimentacaos` (`id`, `idempresa`, `idinquilino`, `idproprietario`, `idbanco`, `idmov_contas`, `idtransacao`, `idevento`, `idlocacao`, `idrecibo`, `idplano_conta`, `conta`, `data`, `mes_ano`, `historico`, `documento`, `valor`, `complemento`, `comissao`, `incide_caixa`, `caixa_rec_pag`, `incide_conta_cor`, `Tipo_d_c`, `tipo_lacto`, `nominal`, `predatado`, `compensado`, `ult_extrato`, `parcial`, `created_at`, `updated_at`, `idhistorico`) VALUES
(1, 1, 1, 2, 1, NULL, NULL, 1, 1, 1, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Maria Lanzoni Dinheiro  ', '1', '1000.00', NULL, '100.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 1, 2, 1, NULL, NULL, 2, 1, 1, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Maria Lanzoni Dinheiro  ', '1', '100.00', NULL, '10.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 1, 2, 1, NULL, NULL, 3, 1, 1, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Maria Lanzoni Dinheiro  ', '1', '200.00', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 1, 2, 1, NULL, NULL, 1, 2, 3, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Maria Lanzoni Dinheiro  ', '3', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 1, 2, 1, NULL, NULL, 2, 2, 3, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Maria Lanzoni Dinheiro  ', '3', '200.00', NULL, '20.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 1, 1, 2, 1, NULL, NULL, 4, 2, 3, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Maria Lanzoni Dinheiro  ', '3', '869.36', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, 2, 1, 1, NULL, NULL, 1, 3, 5, NULL, NULL, '2021-09-15 00:00:00', '09/2021', 'Gabriel Lanzoni Dinheiro  ', '5', '1980.00', NULL, '198.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, '1980.00', NULL, NULL, NULL),
(8, 1, 2, 1, 1, NULL, NULL, 2, 3, 5, NULL, NULL, '2021-09-15 00:00:00', '09/2021', 'Gabriel Lanzoni Dinheiro  ', '5', '180.00', NULL, '18.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 2, 1, 1, NULL, NULL, 1, 3, 6, NULL, NULL, '2021-10-15 00:00:00', '10/2021', 'Gabriel Lanzoni Dinheiro  ', '6', '1980.00', NULL, '198.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, '3960.00', NULL, NULL, NULL),
(10, 1, 2, 1, 1, NULL, NULL, 2, 3, 6, NULL, NULL, '2021-10-15 00:00:00', '10/2021', 'Gabriel Lanzoni Dinheiro  ', '6', '180.00', NULL, '18.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 2, 1, 1, NULL, NULL, 4, 3, 6, NULL, NULL, '2021-10-15 00:00:00', '10/2021', 'Gabriel Lanzoni Dinheiro  ', '6', '5.70', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 1, 2, 1, 1, NULL, NULL, 3, 3, 6, NULL, NULL, '2021-10-15 00:00:00', '10/2021', 'Gabriel Lanzoni Dinheiro  ', '6', '50.00', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 1, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-09-24 00:00:00', NULL, 'Padaria', NULL, '100.00', NULL, '0.00', 'Sim', NULL, 'Sim', 'Debito', 'Pagto', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(14, 1, 3, 2, 1, NULL, NULL, 1, 4, 8, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Pedro Paulo Dinheiro  ', '8', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 1, 3, 2, 1, NULL, NULL, 2, 4, 8, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Pedro Paulo Dinheiro  ', '8', '200.00', NULL, '20.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 1, 3, 2, 1, NULL, NULL, 4, 4, 8, NULL, NULL, '2021-02-05 00:00:00', '02/2021', 'Pedro Paulo Dinheiro  ', '8', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 1, 4, 2, 1, NULL, NULL, 1, 5, 10, NULL, NULL, '2021-02-01 00:00:00', '02/2021', 'Gustavo Dinheiro  ', '10', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 1, 4, 2, 1, NULL, NULL, 4, 5, 10, NULL, NULL, '2021-02-01 00:00:00', '02/2021', 'Gustavo Dinheiro  ', '10', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 1, 3, 2, 1, NULL, NULL, 1, 4, 9, NULL, NULL, '2021-03-05 00:00:00', '03/2021', 'Pedro Paulo Dinheiro  ', '9', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 1, 3, 2, 1, NULL, NULL, 2, 4, 9, NULL, NULL, '2021-03-05 00:00:00', '03/2021', 'Pedro Paulo Dinheiro  ', '9', '200.00', NULL, '20.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 1, 3, 2, 1, NULL, NULL, 4, 4, 9, NULL, NULL, '2021-03-05 00:00:00', '03/2021', 'Pedro Paulo Dinheiro  ', '9', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 1, 3, 2, 1, NULL, NULL, 1, 4, 12, NULL, NULL, '2021-04-05 00:00:00', '04/2021', 'Pedro Paulo Dinheiro  ', '12', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 1, 3, 2, 1, NULL, NULL, 2, 4, 12, NULL, NULL, '2021-04-05 00:00:00', '04/2021', 'Pedro Paulo Dinheiro  ', '12', '200.00', NULL, '20.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 1, 3, 2, 1, NULL, NULL, 4, 4, 12, NULL, NULL, '2021-04-05 00:00:00', '04/2021', 'Pedro Paulo Dinheiro  ', '12', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 1, 3, 2, 1, NULL, NULL, 1, 4, 13, NULL, NULL, '2021-05-05 00:00:00', '05/2021', 'Pedro Paulo Dinheiro  ', '13', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 1, 3, 2, 1, NULL, NULL, 2, 4, 13, NULL, NULL, '2021-05-05 00:00:00', '05/2021', 'Pedro Paulo Dinheiro  ', '13', '200.00', NULL, '20.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 1, 3, 2, 1, NULL, NULL, 4, 4, 13, NULL, NULL, '2021-05-05 00:00:00', '05/2021', 'Pedro Paulo Dinheiro  ', '13', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 1, 4, 2, 1, NULL, NULL, 1, 5, 11, NULL, NULL, '2021-03-01 00:00:00', '03/2021', 'Gustavo Dinheiro  ', '11', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 1, 4, 2, 1, NULL, NULL, 4, 5, 11, NULL, NULL, '2021-03-01 00:00:00', '03/2021', 'Gustavo Dinheiro  ', '11', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 1, 4, 2, 1, NULL, NULL, 1, 5, 11, NULL, NULL, '2021-03-01 00:00:00', '03/2021', 'Gustavo Dinheiro  ', '11', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 1, 4, 2, 1, NULL, NULL, 4, 5, 11, NULL, NULL, '2021-03-01 00:00:00', '03/2021', 'Gustavo Dinheiro  ', '11', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 1, 4, 2, 1, NULL, NULL, 1, 5, 11, NULL, NULL, '2021-03-01 00:00:00', '03/2021', 'Gustavo Dinheiro  ', '11', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 1, 4, 2, 1, NULL, NULL, 4, 5, 11, NULL, NULL, '2021-03-01 00:00:00', '03/2021', 'Gustavo Dinheiro  ', '11', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 1, 4, 2, 1, NULL, NULL, 1, 5, 11, NULL, NULL, '2021-03-01 00:00:00', '03/2021', 'Gustavo Dinheiro  ', '11', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 1, 4, 2, 1, NULL, NULL, 1, 5, 11, NULL, NULL, '2021-03-01 00:00:00', '03/2021', 'Gustavo Dinheiro  ', '11', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 1, 2, 1, 1, NULL, NULL, 1, 3, 7, NULL, NULL, '2021-11-15 00:00:00', '11/2021', 'Gabriel Lanzoni Dinheiro  ', '7', '1980.00', NULL, '198.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, '5940.00', NULL, NULL, NULL),
(37, 1, 2, 1, 1, NULL, NULL, 2, 3, 7, NULL, NULL, '2021-11-15 00:00:00', '11/2021', 'Gabriel Lanzoni Dinheiro  ', '7', '180.00', NULL, '18.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 1, 2, 1, 1, NULL, NULL, 1, 3, 20, NULL, NULL, '2021-12-15 00:00:00', '12/2021', 'Gabriel Lanzoni Dinheiro  ', '20', '1980.00', NULL, '198.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, '7920.00', NULL, NULL, NULL),
(39, 1, 2, 1, 1, NULL, NULL, 2, 3, 20, NULL, NULL, '2021-12-15 00:00:00', '12/2021', 'Gabriel Lanzoni Dinheiro  ', '20', '180.00', NULL, '18.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 1, 2, 1, 1, NULL, NULL, 1, 3, 21, NULL, NULL, '2022-01-15 00:00:00', '01/2022', 'Gabriel Lanzoni Dinheiro  ', '21', '1980.00', NULL, '198.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, '9900.00', NULL, NULL, NULL),
(41, 1, 2, 1, 1, NULL, NULL, 2, 3, 21, NULL, NULL, '2022-01-15 00:00:00', '01/2022', 'Gabriel Lanzoni Dinheiro  ', '21', '180.00', NULL, '18.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 1, 2, 1, 1, NULL, NULL, 1, 3, 22, NULL, NULL, '2022-02-15 00:00:00', '02/2022', 'Gabriel Lanzoni Dinheiro  ', '22', '1980.00', NULL, '198.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, '11880.00', NULL, NULL, NULL),
(43, 1, 2, 1, 1, NULL, NULL, 2, 3, 22, NULL, NULL, '2022-02-15 00:00:00', '02/2022', 'Gabriel Lanzoni Dinheiro  ', '22', '180.00', NULL, '18.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 1, 4, 2, 1, NULL, NULL, 1, 5, 16, NULL, NULL, '2021-04-01 00:00:00', '04/2021', 'Gustavo Dinheiro  ', '16', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 1, 4, 2, 1, NULL, NULL, 4, 5, 16, NULL, NULL, '2021-04-01 00:00:00', '04/2021', 'Gustavo Dinheiro  ', '16', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 1, NULL, NULL, 1, NULL, 2, NULL, NULL, NULL, NULL, NULL, '2021-09-25 00:00:00', NULL, 'Pagto Power', '12', '10000.00', NULL, NULL, 'Nao', 'Pagto', 'Sim', 'Debito', 'Cheque', 'Power', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(47, 1, NULL, 2, 1, NULL, 2, NULL, NULL, NULL, NULL, NULL, '2021-09-25 00:00:00', NULL, 'Pagto Aluguel', '222', '5000.00', NULL, NULL, 'Nao', 'Pagto', 'Sim', 'Debito', 'Cheque', 'Vanderson', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(48, 1, 4, 2, 1, NULL, NULL, 1, 5, 24, NULL, NULL, '2021-05-01 00:00:00', '05/2021', 'Gustavo Dinheiro  ', '24', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 1, 4, 2, 1, NULL, NULL, 1, 5, 25, NULL, NULL, '2021-06-01 00:00:00', '06/2021', 'Gustavo Dinheiro  ', '25', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 1, 4, 2, 1, NULL, NULL, 1, 5, 26, NULL, NULL, '2021-07-01 00:00:00', '07/2021', 'Gustavo Dinheiro  ', '26', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 1, 4, 2, 1, NULL, NULL, 1, 5, 27, NULL, NULL, '2021-08-01 00:00:00', '08/2021', 'Gustavo Dinheiro  ', '27', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 1, 4, 2, 1, NULL, NULL, 1, 5, 28, NULL, NULL, '2021-09-01 00:00:00', '09/2021', 'Gustavo Dinheiro  ', '28', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 1, 4, 2, 1, NULL, NULL, 1, 5, 29, NULL, NULL, '2021-10-01 00:00:00', '10/2021', 'Gustavo Dinheiro  ', '29', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 1, 4, 2, 1, NULL, NULL, 1, 5, 30, NULL, NULL, '2021-11-01 00:00:00', '11/2021', 'Gustavo Dinheiro  ', '30', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 1, 4, 2, 1, NULL, NULL, 1, 5, 31, NULL, NULL, '2021-12-01 00:00:00', '12/2021', 'Gustavo Dinheiro  ', '31', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 1, 4, 2, 1, NULL, NULL, 1, 5, 32, NULL, NULL, '2022-01-01 00:00:00', '01/2022', 'Gustavo Dinheiro  ', '32', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 1, 1, 2, 1, NULL, NULL, 1, 2, 4, NULL, NULL, '2021-03-05 00:00:00', '03/2021', 'Maria Lanzoni Dinheiro  ', '4', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 1, 1, 2, 1, NULL, NULL, 2, 2, 4, NULL, NULL, '2021-03-05 00:00:00', '03/2021', 'Maria Lanzoni Dinheiro  ', '4', '200.00', NULL, '20.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 1, 1, 2, 1, NULL, NULL, 4, 2, 4, NULL, NULL, '2021-03-05 00:00:00', '03/2021', 'Maria Lanzoni Dinheiro  ', '4', '505.64', NULL, NULL, 'Sim', 'Receita', 'Nao', 'Debito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 1, 1, 2, 1, NULL, NULL, 1, 2, 34, NULL, NULL, '2021-04-05 00:00:00', '04/2021', 'Maria Lanzoni Dinheiro  ', '34', '5000.00', NULL, '500.00', 'Sim', 'Receita', 'Sim', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 1, 1, 2, 1, NULL, NULL, 2, 2, 34, NULL, NULL, '2021-04-05 00:00:00', '04/2021', 'Maria Lanzoni Dinheiro  ', '34', '200.00', NULL, '20.00', 'Sim', 'Receita', 'Nao', 'Credito', 'Recibo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mov_contas`
--

CREATE TABLE `mov_contas` (
  `idmov_contas` bigint(20) UNSIGNED NOT NULL,
  `idempresa` bigint(20) UNSIGNED NOT NULL,
  `idbanco` bigint(20) UNSIGNED NOT NULL,
  `idtransacao` bigint(20) UNSIGNED NOT NULL,
  `data` datetime DEFAULT NULL,
  `documento` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `historico` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `compensado` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parcial` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idhistorico` bigint(20) UNSIGNED DEFAULT NULL,
  `idrecibo` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `mov_contas`
--

INSERT INTO `mov_contas` (`idmov_contas`, `idempresa`, `idbanco`, `idtransacao`, `data`, `documento`, `valor`, `historico`, `compensado`, `parcial`, `created_at`, `updated_at`, `idhistorico`, `idrecibo`) VALUES
(1, 1, 1, 1, '2021-02-05 00:00:00', '1', '900.00', 'Maria Lanzoni Dinheiro  ', 'Nao', '14419.36', NULL, NULL, 1, 1),
(2, 1, 1, 1, '2021-02-05 00:00:00', '3', '4330.64', 'Maria Lanzoni Dinheiro  ', 'Nao', '13519.36', NULL, NULL, 1, 3),
(3, 1, 1, 1, '2021-09-15 00:00:00', '5', '2160.00', 'Gabriel Lanzoni Dinheiro  ', 'Nao', '58639.88', NULL, NULL, 1, 5),
(4, 1, 1, 1, '2021-10-15 00:00:00', '6', '2104.30', 'Gabriel Lanzoni Dinheiro  ', 'Nao', '45744.18', NULL, NULL, 1, 6),
(5, 1, 1, 1, '2021-02-05 00:00:00', '8', '4694.36', 'Pedro Paulo Dinheiro  ', 'Nao', '9188.72', NULL, NULL, 1, 8),
(6, 1, 1, 1, '2021-02-01 00:00:00', '10', '4494.36', 'Gustavo Dinheiro  ', 'Nao', '4494.36', NULL, NULL, 1, 10),
(7, 1, 1, 1, '2021-03-05 00:00:00', '9', '4694.36', 'Pedro Paulo Dinheiro  ', 'Nao', '42596.80', NULL, NULL, 1, 9),
(8, 1, 1, 1, '2021-04-05 00:00:00', '12', '4694.36', 'Pedro Paulo Dinheiro  ', 'Nao', '51785.52', NULL, NULL, 1, 12),
(9, 1, 1, 1, '2021-05-05 00:00:00', '13', '4694.36', 'Pedro Paulo Dinheiro  ', 'Nao', '56479.88', NULL, NULL, 1, 13),
(10, 1, 1, 1, '2021-03-01 00:00:00', '11', '4494.36', 'Gustavo Dinheiro  ', 'Nao', '37902.44', NULL, NULL, 1, 11),
(11, 1, 1, 1, '2021-03-01 00:00:00', '11', '4494.36', 'Gustavo Dinheiro  ', 'Nao', '33408.08', NULL, NULL, 1, 11),
(12, 1, 1, 1, '2021-03-01 00:00:00', '11', '4494.36', 'Gustavo Dinheiro  ', 'Nao', '28913.72', NULL, NULL, 1, 11),
(13, 1, 1, 1, '2021-03-01 00:00:00', '11', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '24419.36', NULL, NULL, 1, 11),
(14, 1, 1, 1, '2021-03-01 00:00:00', '11', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '19419.36', NULL, NULL, 1, 11),
(15, 1, 1, 1, '2021-11-15 00:00:00', '7', '2160.00', 'Gabriel Lanzoni Dinheiro  ', 'Nao', '47904.18', NULL, NULL, 1, 7),
(16, 1, 1, 1, '2021-12-15 00:00:00', '20', '2160.00', 'Gabriel Lanzoni Dinheiro  ', 'Nao', '50064.18', NULL, NULL, 1, 20),
(17, 1, 1, 1, '2022-01-15 00:00:00', '21', '2160.00', 'Gabriel Lanzoni Dinheiro  ', 'Nao', '52224.18', NULL, NULL, 1, 21),
(18, 1, 1, 1, '2022-02-15 00:00:00', '22', '2160.00', 'Gabriel Lanzoni Dinheiro  ', 'Nao', '54384.18', NULL, NULL, 1, 22),
(19, 1, 1, 1, '2021-04-01 00:00:00', '16', '4494.36', 'Gustavo Dinheiro  ', 'Nao', '47091.16', NULL, NULL, 1, 16),
(20, 1, 1, 2, '2021-09-25 00:00:00', '12', '10000.00', 'Pagto Power', 'Nao', '43639.88', NULL, NULL, 1, NULL),
(21, 1, 1, 2, '2021-09-25 00:00:00', '222', '5000.00', 'Pagto Aluguel', 'Nao', '53639.88', NULL, NULL, 1, NULL),
(22, 1, 1, 2, '2021-09-27 00:00:00', 'Acerto', '1000.00', 'Acerto', 'Nao', '53384.18', NULL, NULL, 1, NULL),
(23, 1, 1, 1, '2021-05-01 00:00:00', '24', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '58384.18', NULL, NULL, 1, 24),
(24, 1, 1, 1, '2021-06-01 00:00:00', '25', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '63384.18', NULL, NULL, 1, 25),
(25, 1, 1, 1, '2021-07-01 00:00:00', '26', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '68384.18', NULL, NULL, 1, 26),
(26, 1, 1, 1, '2021-08-01 00:00:00', '27', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '73384.18', NULL, NULL, 1, 27),
(27, 1, 1, 1, '2021-09-01 00:00:00', '28', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '78384.18', NULL, NULL, 1, 28),
(28, 1, 1, 1, '2021-10-01 00:00:00', '29', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '83384.18', NULL, NULL, 1, 29),
(29, 1, 1, 1, '2021-11-01 00:00:00', '30', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '88384.18', NULL, NULL, 1, 30),
(30, 1, 1, 1, '2021-12-01 00:00:00', '31', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '93384.18', NULL, NULL, 1, 31),
(31, 1, 1, 1, '2022-01-01 00:00:00', '32', '5000.00', 'Gustavo Dinheiro  ', 'Nao', '98384.18', NULL, NULL, 1, 32),
(32, 1, 1, 1, '2021-03-05 00:00:00', '4', '4694.36', 'Maria Lanzoni Dinheiro  ', 'Nao', '103078.54', NULL, NULL, 1, 4),
(33, 1, 1, 1, '2021-04-05 00:00:00', '34', '5200.00', 'Maria Lanzoni Dinheiro  ', 'Nao', '108278.54', NULL, NULL, 1, 34);

-- --------------------------------------------------------

--
-- Estrutura da tabela `municipio`
--

CREATE TABLE `municipio` (
  `idmunicipio` bigint(20) UNSIGNED NOT NULL,
  `cep` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UF` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cod_pais` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `municipio`
--

INSERT INTO `municipio` (`idmunicipio`, `cep`, `nome`, `bairro`, `localidade`, `UF`, `cod_pais`, `created_at`, `updated_at`) VALUES
(1, '12949098', 'Atibaia', 'Atibaia', 'Atibaia', 'SP', '55', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'menu.admin', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(2, 'menu.gerente', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(3, 'menu.proprietario', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(4, 'menu.inquilino', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(5, 'menu.caixa', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `plano_contas`
--

CREATE TABLE `plano_contas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idempresa` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conta` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agrupamento` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `plano_contas`
--

INSERT INTO `plano_contas` (`id`, `idempresa`, `codigo`, `conta`, `agrupamento`, `created_at`, `updated_at`) VALUES
(1, 1, '001', 'Entrada', '1', NULL, NULL),
(2, 1, '002', 'Saida', '2', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `proprietario`
--

CREATE TABLE `proprietario` (
  `idproprietario` bigint(20) UNSIGNED NOT NULL,
  `idmunicipio` bigint(20) UNSIGNED NOT NULL,
  `tipo_pessoa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fantasia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fisica_juridica` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf_cnpj` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endereco` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento_end` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referencia` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obs_prop` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg_ie` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conjuge` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aos_cuidados` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_corr` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_corr` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `compl_corr` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro_corr` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade_corr` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf_corr` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep_corr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favorecido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf_fav` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banco_fav` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ag_fav` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conta_fav` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_civil` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `proprietario`
--

INSERT INTO `proprietario` (`idproprietario`, `idmunicipio`, `tipo_pessoa`, `nome`, `fantasia`, `fisica_juridica`, `cpf_cnpj`, `endereco`, `telefone`, `email`, `complemento_end`, `bairro`, `cidade`, `uf`, `cep`, `referencia`, `obs_prop`, `rg_ie`, `condicao`, `conjuge`, `aos_cuidados`, `end_corr`, `num_corr`, `compl_corr`, `bairro_corr`, `cidade_corr`, `uf_corr`, `cep_corr`, `favorecido`, `cpf_fav`, `banco_fav`, `ag_fav`, `conta_fav`, `estado_civil`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 1, 'Proprietario', 'Proprietário SYSAB', 'Proprietario', 'Fisica', '00.000.000/0001-00', NULL, NULL, 'proprietario@gmail.com', NULL, 'Centro', 'Atibaia', 'SP', '12940-000', NULL, NULL, NULL, 'Ativo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'Proprietario', 'Vanderson Nogueira Expedito', 'Vander', 'Fisica', '71213635691', 'Rua Tenente Jose Luis Soares,74, Jardim Alvinopolis', '11986044826', 'vander@gmail.com', 'Jardim Alvinopolis', NULL, 'Atibaia', 'SP', '12943430', NULL, NULL, '23', 'Ativo', 'Vanderson', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Casado', NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `reajuste`
--

CREATE TABLE `reajuste` (
  `idreajuste` bigint(20) UNSIGNED NOT NULL,
  `idindice` bigint(20) UNSIGNED NOT NULL,
  `mes_ano` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensal` decimal(10,4) DEFAULT NULL,
  `bimestral` decimal(10,4) DEFAULT NULL,
  `trimestral` decimal(10,4) DEFAULT NULL,
  `quadrimestral` decimal(10,4) DEFAULT NULL,
  `quintimestral` decimal(10,4) DEFAULT NULL,
  `semestral` decimal(10,4) DEFAULT NULL,
  `anual` decimal(10,4) DEFAULT NULL,
  `bianual` decimal(10,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `reajuste`
--

INSERT INTO `reajuste` (`idreajuste`, `idindice`, `mes_ano`, `mensal`, `bimestral`, `trimestral`, `quadrimestral`, `quintimestral`, `semestral`, `anual`, `bianual`, `created_at`, `updated_at`) VALUES
(1, 1, '02/2022', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '10.0000', '0.0000', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `recibo`
--

CREATE TABLE `recibo` (
  `idrecibo` bigint(20) UNSIGNED NOT NULL,
  `idlocacao` bigint(20) UNSIGNED NOT NULL,
  `mes_ano` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dt_inicial` date NOT NULL,
  `dt_final` date NOT NULL,
  `contador_aluguel` int(11) NOT NULL,
  `reajuste` int(11) NOT NULL,
  `dt_vencimento` date DEFAULT NULL,
  `dt_pagamento` date DEFAULT NULL,
  `nosso_numero` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forma_pgto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banco` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `praca` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dt_emissao` date DEFAULT NULL,
  `dt_apresentacao` date DEFAULT NULL,
  `emitente` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor_pgto` decimal(10,2) DEFAULT NULL,
  `troco` decimal(10,2) DEFAULT NULL,
  `telefone` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obs` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_aluguel` decimal(10,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxa_adm` decimal(10,2) NOT NULL,
  `liquido` decimal(10,2) NOT NULL,
  `idretorno` int(11) DEFAULT NULL,
  `idinquilino` int(11) DEFAULT NULL,
  `idproprietario` int(11) DEFAULT NULL,
  `idimovel` int(11) DEFAULT NULL,
  `idindice` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nomeremessa` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idremessa` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `recibo`
--

INSERT INTO `recibo` (`idrecibo`, `idlocacao`, `mes_ano`, `dt_inicial`, `dt_final`, `contador_aluguel`, `reajuste`, `dt_vencimento`, `dt_pagamento`, `nosso_numero`, `forma_pgto`, `cheque`, `banco`, `praca`, `dt_emissao`, `dt_apresentacao`, `emitente`, `valor_pgto`, `troco`, `telefone`, `obs`, `total_aluguel`, `estado`, `codigo`, `taxa_adm`, `liquido`, `idretorno`, `idinquilino`, `idproprietario`, `idimovel`, `idindice`, `created_at`, `updated_at`, `nomeremessa`, `idremessa`) VALUES
(1, 1, '02/2021', '2021-01-01', '2021-01-30', 1, 12, '2021-02-05', '2021-02-05', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '900.00', NULL, NULL, NULL, '1000.00', 'Ativo', '0', '100.00', '900.00', NULL, 1, 2, 2, 1, NULL, NULL, NULL, NULL),
(3, 2, '02/2021', '2021-01-01', '2021-01-30', 1, 12, '2021-02-05', '2021-02-05', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '4330.64', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 1, 2, 2, 1, NULL, NULL, NULL, NULL),
(4, 2, '03/2021', '2021-02-01', '2021-02-28', 2, 12, '2021-03-05', '2021-03-05', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '4694.36', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 1, 2, 2, 1, NULL, NULL, 'remessa_Maria Lanzoni_4_2.txt', 2),
(5, 3, '09/2021', '2021-08-01', '2021-08-30', 1, 12, '2021-09-15', '2021-09-15', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '2160.00', NULL, NULL, NULL, '1980.00', 'Ativo', '0', '198.00', '1782.00', NULL, 2, 1, 3, 1, NULL, NULL, NULL, NULL),
(6, 3, '10/2021', '2021-09-01', '2021-09-30', 2, 12, '2021-10-15', '2021-10-15', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '2104.30', NULL, NULL, NULL, '1980.00', 'Ativo', '0', '198.00', '1782.00', NULL, 2, 1, 3, 1, NULL, NULL, 'remessa_Gabriel Lanzoni_6_3.txt', 3),
(7, 3, '11/2021', '2021-10-01', '2021-10-30', 3, 12, '2021-11-15', '2021-11-15', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '2160.00', NULL, NULL, NULL, '1980.00', 'Ativo', '0', '198.00', '1782.00', NULL, 2, 1, 3, 1, NULL, NULL, NULL, NULL),
(8, 4, '02/2021', '2021-01-01', '2021-01-30', 1, 12, '2021-02-05', '2021-02-05', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '4694.36', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 3, 2, 4, 1, NULL, NULL, NULL, NULL),
(9, 4, '03/2021', '2021-02-01', '2021-02-28', 2, 12, '2021-03-05', '2021-03-05', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '4694.36', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 3, 2, 4, 1, NULL, NULL, NULL, NULL),
(10, 5, '02/2021', '2021-01-01', '2021-01-30', 1, 12, '2021-02-01', '2021-02-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '4494.36', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(11, 5, '03/2021', '2021-02-01', '2021-02-28', 2, 12, '2021-03-01', '2021-03-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(12, 4, '04/2021', '2021-03-01', '2021-03-31', 3, 12, '2021-04-05', '2021-04-05', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '4694.36', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 3, 2, 4, 1, NULL, NULL, NULL, NULL),
(13, 4, '05/2021', '2021-04-01', '2021-04-30', 4, 12, '2021-05-05', '2021-05-05', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '4694.36', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 3, 2, 4, 1, NULL, NULL, NULL, NULL),
(16, 5, '04/2021', '2021-03-01', '2021-03-31', 3, 12, '2021-04-01', '2021-04-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '4494.36', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(17, 5, '04/2021', '2021-03-01', '2021-03-31', 3, 12, '2021-04-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, 'remessa_Gustavo_17_4.txt', 4),
(18, 5, '04/2021', '2021-03-01', '2021-03-31', 3, 12, '2021-04-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(19, 5, '04/2021', '2021-03-01', '2021-03-31', 3, 12, '2021-04-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(20, 3, '12/2021', '2021-11-01', '2021-11-30', 4, 12, '2021-12-15', '2021-12-15', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '2160.00', NULL, NULL, NULL, '1980.00', 'Ativo', '0', '198.00', '1782.00', NULL, 2, 1, 3, 1, NULL, NULL, NULL, NULL),
(21, 3, '01/2022', '2021-12-01', '2021-12-30', 5, 12, '2022-01-15', '2022-01-15', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '2160.00', NULL, NULL, NULL, '1980.00', 'Ativo', '0', '198.00', '1782.00', NULL, 2, 1, 3, 1, NULL, NULL, NULL, NULL),
(22, 3, '02/2022', '2022-01-01', '2022-01-30', 6, 12, '2022-02-15', '2022-02-15', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '2160.00', NULL, NULL, NULL, '1980.00', 'Ativo', '0', '198.00', '1782.00', NULL, 2, 1, 3, 1, NULL, NULL, NULL, NULL),
(24, 5, '05/2021', '2021-04-01', '2021-04-30', 4, 12, '2021-05-01', '2021-05-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(25, 5, '06/2021', '2021-05-01', '2021-05-31', 5, 12, '2021-06-01', '2021-06-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(26, 5, '07/2021', '2021-06-01', '2021-06-30', 6, 12, '2021-07-01', '2021-07-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(27, 5, '08/2021', '2021-07-01', '2021-07-31', 7, 12, '2021-08-01', '2021-08-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(28, 5, '09/2021', '2021-08-01', '2021-08-31', 8, 12, '2021-09-01', '2021-09-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(29, 5, '10/2021', '2021-09-01', '2021-09-30', 9, 12, '2021-10-01', '2021-10-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(30, 5, '11/2021', '2021-10-01', '2021-10-31', 10, 12, '2021-11-01', '2021-11-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(31, 5, '12/2021', '2021-11-01', '2021-11-30', 11, 12, '2021-12-01', '2021-12-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(32, 5, '01/2022', '2021-12-01', '2021-12-31', 12, 12, '2022-01-01', '2022-01-01', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(33, 5, '02/2022', '2022-01-01', '2022-01-31', 1, 12, '2022-02-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5500.00', 'Ativo', '0', '500.00', '4500.00', NULL, 4, 2, 5, 1, NULL, NULL, NULL, NULL),
(34, 2, '04/2021', '2021-03-01', '2021-03-31', 3, 12, '2021-04-05', '2021-04-05', NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL, '5200.00', NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 1, 2, 2, 1, NULL, NULL, NULL, NULL),
(35, 2, '05/2021', '2021-04-01', '2021-04-30', 4, 12, '2021-05-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5000.00', 'Ativo', '0', '500.00', '4500.00', NULL, 1, 2, 2, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `remessas`
--

CREATE TABLE `remessas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `path_remessa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `remessas`
--

INSERT INTO `remessas` (`id`, `path_remessa`, `created_at`, `updated_at`) VALUES
(1, 'remessa_Maria Lanzoni_2_1.txt', '2021-09-17 14:21:36', '2021-09-17 14:21:36'),
(2, 'remessa_Maria Lanzoni_4_2.txt', '2021-09-21 20:03:40', '2021-09-21 20:03:40'),
(3, 'remessa_Gabriel Lanzoni_6_3.txt', '2021-09-21 20:11:25', '2021-09-21 20:11:25'),
(4, 'remessa_Gustavo_17_4.txt', '2021-09-27 20:43:35', '2021-09-27 20:43:35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(2, 'Gerente', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(3, 'Proprietario', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(4, 'Inquilino', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(5, 'Caixa', 'web', '2021-09-16 20:03:08', '2021-09-16 20:03:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tabela_irs`
--

CREATE TABLE `tabela_irs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idempresa` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `faixa1` decimal(10,2) DEFAULT NULL,
  `aliquota1` decimal(10,2) DEFAULT NULL,
  `deduzir1` decimal(10,2) DEFAULT NULL,
  `faixa2` decimal(10,2) DEFAULT NULL,
  `aliquota2` decimal(10,2) DEFAULT NULL,
  `deduzir2` decimal(10,2) DEFAULT NULL,
  `faixa3` decimal(10,2) DEFAULT NULL,
  `aliquota3` decimal(10,2) DEFAULT NULL,
  `deduzir3` decimal(10,2) DEFAULT NULL,
  `faixa4` decimal(10,2) DEFAULT NULL,
  `aliquota4` decimal(10,2) DEFAULT NULL,
  `deduzir4` decimal(10,2) DEFAULT NULL,
  `faixa5` decimal(10,2) DEFAULT NULL,
  `aliquota5` decimal(10,2) DEFAULT NULL,
  `deduzir5` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tabela_irs`
--

INSERT INTO `tabela_irs` (`id`, `idempresa`, `codigo`, `faixa1`, `aliquota1`, `deduzir1`, `faixa2`, `aliquota2`, `deduzir2`, `faixa3`, `aliquota3`, `deduzir3`, `faixa4`, `aliquota4`, `deduzir4`, `faixa5`, `aliquota5`, `deduzir5`, `created_at`, `updated_at`) VALUES
(1, 1, '2021', '1903.98', '0.00', '0.00', '2826.65', '7.50', '142.80', '3751.05', '15.00', '354.80', '4664.68', '22.50', '636.13', '4664.68', '27.50', '869.36', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacao`
--

CREATE TABLE `transacao` (
  `idtransacao` bigint(20) UNSIGNED NOT NULL,
  `idempresa` bigint(20) UNSIGNED NOT NULL,
  `transacao` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `situacao` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filial` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conta` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transacao_filial` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `transacao`
--

INSERT INTO `transacao` (`idtransacao`, `idempresa`, `transacao`, `tipo`, `situacao`, `filial`, `conta`, `transacao_filial`, `created_at`, `updated_at`) VALUES
(1, 1, 'Entrada de Caixa', 'Credito', 'Caixa', '', '', '', NULL, NULL),
(2, 1, 'Saida de Caixa', 'Debito', 'Caixa', '', '', '', NULL, NULL),
(3, 1, 'Entrada de Banco', 'Credito', 'Banco', '', '', '', NULL, NULL),
(4, 1, 'Saida de Banco', 'Debito', 'Banco', '', '', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$10$k3Mv5sieFFfnLh34yZD5rOT0zr2hWfkURbami7VMJUa88p0JMAd4C', '1', NULL, '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(2, 'Power', 'power@admin.com', NULL, '$2y$10$39VXB6/P5pAQeLcJJ6WepOuARtUtrdBJa9tUFaOCK6.5SJAirmJVO', '1', NULL, '2021-09-16 20:03:08', '2021-09-16 20:03:08'),
(3, 'Vanderson Nogueira Expedito', 'vander@gmail.com', NULL, '$2y$10$ols9iK6w83rti6nN29VKDOS5co08qEbf736Qg.UWj8lbgqzF5udbS', '1', NULL, '2021-09-16 20:14:52', '2021-09-16 20:14:52'),
(4, 'Maria Lanzoni', 'maria@gmail.com', NULL, '$2y$10$9fLHm6IP1S4V4J87VGFGBuLqxcy1SthuyxzbdP93oGMbnZGKWPALC', '1', NULL, '2021-09-16 20:16:31', '2021-09-16 20:16:31'),
(5, 'Gabriel Lanzoni', 'gabi@gmail.com', NULL, '$2y$10$uBsTe.7r38HCc.v4hlVUi.Z8r8R4rqMaIyVvmXCIZiDRWColvFrba', '1', NULL, '2021-09-21 20:06:34', '2021-09-21 20:06:34'),
(6, 'Pedro Paulo', 'pedro@gmail.com', NULL, '$2y$10$AMKv/qwrcyvvDzdPaDb.DelzrAULPG4W31i4rr.udwBugUGhiyigq', '1', NULL, '2021-09-24 13:13:55', '2021-09-24 13:13:55'),
(7, 'Gustavo', 'gustavo@gmail.com', NULL, '$2y$10$520xUU/35d/HKB1HKbfgNerPO0YJV28qwRLE4bkNi5XJojvVPSShu', '1', NULL, '2021-09-24 14:11:29', '2021-09-24 14:11:29');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`idbanco`),
  ADD KEY `banco_idempresa_foreign` (`idempresa`);

--
-- Índices para tabela `detalhe_locacao`
--
ALTER TABLE `detalhe_locacao`
  ADD PRIMARY KEY (`iddetalhe_locacao`),
  ADD KEY `detalhe_locacao_idlocacao_foreign` (`idlocacao`),
  ADD KEY `detalhe_locacao_idevento_foreign` (`idevento`);

--
-- Índices para tabela `detalhe_recibo`
--
ALTER TABLE `detalhe_recibo`
  ADD PRIMARY KEY (`iddetalhe_recibo`),
  ADD KEY `detalhe_recibo_idrecibo_foreign` (`idrecibo`),
  ADD KEY `detalhe_recibo_idevento_foreign` (`idevento`);

--
-- Índices para tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`),
  ADD UNIQUE KEY `empresa_nome_unique` (`nome`);

--
-- Índices para tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`idevento`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `fiador`
--
ALTER TABLE `fiador`
  ADD PRIMARY KEY (`idfiador`),
  ADD KEY `fiador_idinquilino_foreign` (`idinquilino`),
  ADD KEY `fiador_idmunicipio_foreign` (`idmunicipio`);

--
-- Índices para tabela `historico_padraos`
--
ALTER TABLE `historico_padraos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historico_padraos_idempresa_foreign` (`idempresa`);

--
-- Índices para tabela `imovel`
--
ALTER TABLE `imovel`
  ADD PRIMARY KEY (`idimovel`),
  ADD KEY `imovel_idproprietario_foreign` (`idproprietario`),
  ADD KEY `imovel_idmunicipio_foreign` (`idmunicipio`);

--
-- Índices para tabela `indice`
--
ALTER TABLE `indice`
  ADD PRIMARY KEY (`idindice`);

--
-- Índices para tabela `inquilino`
--
ALTER TABLE `inquilino`
  ADD PRIMARY KEY (`idinquilino`),
  ADD KEY `inquilino_idproprietario_foreign` (`idproprietario`),
  ADD KEY `inquilino_idimovel_foreign` (`idimovel`),
  ADD KEY `inquilino_idmunicipio_foreign` (`idmunicipio`),
  ADD KEY `inquilino_user_id_foreign` (`user_id`);

--
-- Índices para tabela `lacto_indice`
--
ALTER TABLE `lacto_indice`
  ADD PRIMARY KEY (`idlacto_indice`),
  ADD KEY `lacto_indice_idindice_foreign` (`idindice`);

--
-- Índices para tabela `locacao`
--
ALTER TABLE `locacao`
  ADD PRIMARY KEY (`idlocacao`),
  ADD KEY `locacao_idinquilino_foreign` (`idinquilino`),
  ADD KEY `locacao_idproprietario_foreign` (`idproprietario`),
  ADD KEY `locacao_idimovel_foreign` (`idimovel`),
  ADD KEY `locacao_idindice_foreign` (`idindice`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Índices para tabela `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Índices para tabela `movimentacaos`
--
ALTER TABLE `movimentacaos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movimentacaos_idempresa_foreign` (`idempresa`),
  ADD KEY `movimentacaos_idinquilino_foreign` (`idinquilino`),
  ADD KEY `movimentacaos_idproprietario_foreign` (`idproprietario`),
  ADD KEY `movimentacaos_idbanco_foreign` (`idbanco`),
  ADD KEY `movimentacaos_idmov_contas_foreign` (`idmov_contas`),
  ADD KEY `movimentacaos_idtransacao_foreign` (`idtransacao`),
  ADD KEY `movimentacaos_idevento_foreign` (`idevento`),
  ADD KEY `movimentacaos_idlocacao_foreign` (`idlocacao`),
  ADD KEY `movimentacaos_idrecibo_foreign` (`idrecibo`),
  ADD KEY `movimentacaos_idplano_conta_foreign` (`idplano_conta`),
  ADD KEY `movimentacaos_idhistorico_foreign` (`idhistorico`);

--
-- Índices para tabela `mov_contas`
--
ALTER TABLE `mov_contas`
  ADD PRIMARY KEY (`idmov_contas`),
  ADD KEY `mov_contas_idempresa_foreign` (`idempresa`),
  ADD KEY `mov_contas_idbanco_foreign` (`idbanco`),
  ADD KEY `mov_contas_idtransacao_foreign` (`idtransacao`),
  ADD KEY `mov_contas_idhistorico_foreign` (`idhistorico`);

--
-- Índices para tabela `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`idmunicipio`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices para tabela `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Índices para tabela `plano_contas`
--
ALTER TABLE `plano_contas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plano_contas_idempresa_foreign` (`idempresa`);

--
-- Índices para tabela `proprietario`
--
ALTER TABLE `proprietario`
  ADD PRIMARY KEY (`idproprietario`),
  ADD KEY `proprietario_idmunicipio_foreign` (`idmunicipio`),
  ADD KEY `proprietario_user_id_foreign` (`user_id`);

--
-- Índices para tabela `reajuste`
--
ALTER TABLE `reajuste`
  ADD PRIMARY KEY (`idreajuste`),
  ADD KEY `reajuste_idindice_foreign` (`idindice`);

--
-- Índices para tabela `recibo`
--
ALTER TABLE `recibo`
  ADD PRIMARY KEY (`idrecibo`),
  ADD KEY `recibo_idlocacao_foreign` (`idlocacao`),
  ADD KEY `recibo_idremessa_foreign` (`idremessa`);

--
-- Índices para tabela `remessas`
--
ALTER TABLE `remessas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Índices para tabela `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Índices para tabela `tabela_irs`
--
ALTER TABLE `tabela_irs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabela_irs_idempresa_foreign` (`idempresa`);

--
-- Índices para tabela `transacao`
--
ALTER TABLE `transacao`
  ADD PRIMARY KEY (`idtransacao`),
  ADD KEY `transacao_idempresa_foreign` (`idempresa`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `banco`
--
ALTER TABLE `banco`
  MODIFY `idbanco` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `detalhe_locacao`
--
ALTER TABLE `detalhe_locacao`
  MODIFY `iddetalhe_locacao` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `detalhe_recibo`
--
ALTER TABLE `detalhe_recibo`
  MODIFY `iddetalhe_recibo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idempresa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `idevento` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fiador`
--
ALTER TABLE `fiador`
  MODIFY `idfiador` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `historico_padraos`
--
ALTER TABLE `historico_padraos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `imovel`
--
ALTER TABLE `imovel`
  MODIFY `idimovel` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `indice`
--
ALTER TABLE `indice`
  MODIFY `idindice` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `inquilino`
--
ALTER TABLE `inquilino`
  MODIFY `idinquilino` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `lacto_indice`
--
ALTER TABLE `lacto_indice`
  MODIFY `idlacto_indice` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `locacao`
--
ALTER TABLE `locacao`
  MODIFY `idlocacao` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `movimentacaos`
--
ALTER TABLE `movimentacaos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `mov_contas`
--
ALTER TABLE `mov_contas`
  MODIFY `idmov_contas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `municipio`
--
ALTER TABLE `municipio`
  MODIFY `idmunicipio` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `plano_contas`
--
ALTER TABLE `plano_contas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `proprietario`
--
ALTER TABLE `proprietario`
  MODIFY `idproprietario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `reajuste`
--
ALTER TABLE `reajuste`
  MODIFY `idreajuste` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `recibo`
--
ALTER TABLE `recibo`
  MODIFY `idrecibo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `remessas`
--
ALTER TABLE `remessas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tabela_irs`
--
ALTER TABLE `tabela_irs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `transacao`
--
ALTER TABLE `transacao`
  MODIFY `idtransacao` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `banco`
--
ALTER TABLE `banco`
  ADD CONSTRAINT `banco_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`);

--
-- Limitadores para a tabela `detalhe_locacao`
--
ALTER TABLE `detalhe_locacao`
  ADD CONSTRAINT `detalhe_locacao_idevento_foreign` FOREIGN KEY (`idevento`) REFERENCES `evento` (`idevento`),
  ADD CONSTRAINT `detalhe_locacao_idlocacao_foreign` FOREIGN KEY (`idlocacao`) REFERENCES `locacao` (`idlocacao`);

--
-- Limitadores para a tabela `detalhe_recibo`
--
ALTER TABLE `detalhe_recibo`
  ADD CONSTRAINT `detalhe_recibo_idevento_foreign` FOREIGN KEY (`idevento`) REFERENCES `evento` (`idevento`),
  ADD CONSTRAINT `detalhe_recibo_idrecibo_foreign` FOREIGN KEY (`idrecibo`) REFERENCES `recibo` (`idrecibo`);

--
-- Limitadores para a tabela `fiador`
--
ALTER TABLE `fiador`
  ADD CONSTRAINT `fiador_idinquilino_foreign` FOREIGN KEY (`idinquilino`) REFERENCES `inquilino` (`idinquilino`),
  ADD CONSTRAINT `fiador_idmunicipio_foreign` FOREIGN KEY (`idmunicipio`) REFERENCES `municipio` (`idmunicipio`);

--
-- Limitadores para a tabela `historico_padraos`
--
ALTER TABLE `historico_padraos`
  ADD CONSTRAINT `historico_padraos_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`);

--
-- Limitadores para a tabela `imovel`
--
ALTER TABLE `imovel`
  ADD CONSTRAINT `imovel_idmunicipio_foreign` FOREIGN KEY (`idmunicipio`) REFERENCES `municipio` (`idmunicipio`),
  ADD CONSTRAINT `imovel_idproprietario_foreign` FOREIGN KEY (`idproprietario`) REFERENCES `proprietario` (`idproprietario`);

--
-- Limitadores para a tabela `inquilino`
--
ALTER TABLE `inquilino`
  ADD CONSTRAINT `inquilino_idimovel_foreign` FOREIGN KEY (`idimovel`) REFERENCES `imovel` (`idimovel`),
  ADD CONSTRAINT `inquilino_idmunicipio_foreign` FOREIGN KEY (`idmunicipio`) REFERENCES `municipio` (`idmunicipio`),
  ADD CONSTRAINT `inquilino_idproprietario_foreign` FOREIGN KEY (`idproprietario`) REFERENCES `proprietario` (`idproprietario`),
  ADD CONSTRAINT `inquilino_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `lacto_indice`
--
ALTER TABLE `lacto_indice`
  ADD CONSTRAINT `lacto_indice_idindice_foreign` FOREIGN KEY (`idindice`) REFERENCES `indice` (`idindice`);

--
-- Limitadores para a tabela `locacao`
--
ALTER TABLE `locacao`
  ADD CONSTRAINT `locacao_idimovel_foreign` FOREIGN KEY (`idimovel`) REFERENCES `imovel` (`idimovel`),
  ADD CONSTRAINT `locacao_idindice_foreign` FOREIGN KEY (`idindice`) REFERENCES `indice` (`idindice`),
  ADD CONSTRAINT `locacao_idinquilino_foreign` FOREIGN KEY (`idinquilino`) REFERENCES `inquilino` (`idinquilino`),
  ADD CONSTRAINT `locacao_idproprietario_foreign` FOREIGN KEY (`idproprietario`) REFERENCES `proprietario` (`idproprietario`);

--
-- Limitadores para a tabela `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `movimentacaos`
--
ALTER TABLE `movimentacaos`
  ADD CONSTRAINT `movimentacaos_idbanco_foreign` FOREIGN KEY (`idbanco`) REFERENCES `banco` (`idbanco`),
  ADD CONSTRAINT `movimentacaos_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`),
  ADD CONSTRAINT `movimentacaos_idevento_foreign` FOREIGN KEY (`idevento`) REFERENCES `evento` (`idevento`),
  ADD CONSTRAINT `movimentacaos_idhistorico_foreign` FOREIGN KEY (`idhistorico`) REFERENCES `historico_padraos` (`id`),
  ADD CONSTRAINT `movimentacaos_idinquilino_foreign` FOREIGN KEY (`idinquilino`) REFERENCES `inquilino` (`idinquilino`),
  ADD CONSTRAINT `movimentacaos_idlocacao_foreign` FOREIGN KEY (`idlocacao`) REFERENCES `locacao` (`idlocacao`),
  ADD CONSTRAINT `movimentacaos_idmov_contas_foreign` FOREIGN KEY (`idmov_contas`) REFERENCES `mov_contas` (`idmov_contas`),
  ADD CONSTRAINT `movimentacaos_idplano_conta_foreign` FOREIGN KEY (`idplano_conta`) REFERENCES `plano_contas` (`id`),
  ADD CONSTRAINT `movimentacaos_idproprietario_foreign` FOREIGN KEY (`idproprietario`) REFERENCES `proprietario` (`idproprietario`),
  ADD CONSTRAINT `movimentacaos_idrecibo_foreign` FOREIGN KEY (`idrecibo`) REFERENCES `recibo` (`idrecibo`),
  ADD CONSTRAINT `movimentacaos_idtransacao_foreign` FOREIGN KEY (`idtransacao`) REFERENCES `transacao` (`idtransacao`);

--
-- Limitadores para a tabela `mov_contas`
--
ALTER TABLE `mov_contas`
  ADD CONSTRAINT `mov_contas_idbanco_foreign` FOREIGN KEY (`idbanco`) REFERENCES `banco` (`idbanco`),
  ADD CONSTRAINT `mov_contas_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`),
  ADD CONSTRAINT `mov_contas_idhistorico_foreign` FOREIGN KEY (`idhistorico`) REFERENCES `historico_padraos` (`id`),
  ADD CONSTRAINT `mov_contas_idtransacao_foreign` FOREIGN KEY (`idtransacao`) REFERENCES `transacao` (`idtransacao`);

--
-- Limitadores para a tabela `plano_contas`
--
ALTER TABLE `plano_contas`
  ADD CONSTRAINT `plano_contas_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`);

--
-- Limitadores para a tabela `proprietario`
--
ALTER TABLE `proprietario`
  ADD CONSTRAINT `proprietario_idmunicipio_foreign` FOREIGN KEY (`idmunicipio`) REFERENCES `municipio` (`idmunicipio`),
  ADD CONSTRAINT `proprietario_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `reajuste`
--
ALTER TABLE `reajuste`
  ADD CONSTRAINT `reajuste_idindice_foreign` FOREIGN KEY (`idindice`) REFERENCES `indice` (`idindice`);

--
-- Limitadores para a tabela `recibo`
--
ALTER TABLE `recibo`
  ADD CONSTRAINT `recibo_idlocacao_foreign` FOREIGN KEY (`idlocacao`) REFERENCES `locacao` (`idlocacao`),
  ADD CONSTRAINT `recibo_idremessa_foreign` FOREIGN KEY (`idremessa`) REFERENCES `remessas` (`id`);

--
-- Limitadores para a tabela `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `tabela_irs`
--
ALTER TABLE `tabela_irs`
  ADD CONSTRAINT `tabela_irs_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`);

--
-- Limitadores para a tabela `transacao`
--
ALTER TABLE `transacao`
  ADD CONSTRAINT `transacao_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

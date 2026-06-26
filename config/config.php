<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "dbportal";


$conexao = new mysqli($servidor, $usuario, $senha);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if (!$conexao->select_db($banco)) {
    $sqlCriarBanco = "CREATE DATABASE IF NOT EXISTS `$banco` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$conexao->query($sqlCriarBanco)) {
        die("Falha ao criar banco de dados: " . $conexao->error);
    }
    $conexao->select_db($banco);
}

$sqlCriarTabela = "CREATE TABLE IF NOT EXISTS `noticias` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(255) NOT NULL,
    `noticia` TEXT NOT NULL,
    `data` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `autor` int foreign key references usuarios(id),
    `imagem` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (!$conexao->query($sqlCriarTabela)) {
    die("Falha ao criar tabela noticias: " . $conexao->error);
}

$resultadoTabela = $conexao->query("SELECT COUNT(*) AS total FROM noticias");
if ($resultadoTabela) {
    $row = $resultadoTabela->fetch_assoc();
    if ($row['total'] == 0) {
        $sqlInserirExemplos = "INSERT INTO `noticias` (`titulo`, `noticia`, `data`) VALUES
            ('Lançamento da nova plataforma', 'Estamos felizes em anunciar que nossa nova plataforma já está online.', NOW()),
            ('Atualização de recursos', 'Novos recursos foram adicionados para melhorar a experiência do usuário.', NOW()),
            ('Dicas de desenvolvimento', 'Confira nossas dicas de desenvolvimento para criar sites mais rápidos e seguros.', NOW())";
        $conexao->query($sqlInserirExemplos);
    }
}

$conexao->set_charset("utf8");
?>
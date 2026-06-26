<?php include 'conexao.php'; include 'funcoes.php';
$noticias = $conexao->query("SELECT * FROM noticias");
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - Portal de Notícias Culinárias</title>
</head>
<body>
    <h1>Olá, <?php echo $_SESSION['nome']; ?>!</h1>
    <p>Esta é o seu dashboard.</p>

    <a href="nova_noticia.php">Cadastrar nova notícia</a>
    <?php if ($_SESSION['nivel'] == 'admin'): ?>
        <a href="usuarios.php">Gerenciar usuários (Admin)</a>
    <?php endif; ?>
        <a href="logout.php">Sair</a>

    <h3>Suas publicações</h3>
    <?php while ($noticia = $noticias->fetch_assoc()): ?>
        <div>
            <h4><?php echo $noticia['titulo']; ?></h4>
            <p><?php echo $noticia['noticia']; ?></p>
            <p><strong>Autor:</strong> <?php echo $noticia['autor']; ?></p>
            <?php if ($noticia['imagem']): ?>
                <img src="<?php echo $noticia['imagem']; ?>" alt="Imagem da notícia" width="200">
            <?php endif; ?>
            <a href="editar_noticia.php?id=<?php echo $noticia['id']; ?>">Editar</a>
            <a href="excluir_noticia.php?id=<?php echo $noticia['id']; ?>">Excluir</a>
        </div>
    <?php endwhile; ?>
</body>
</html>
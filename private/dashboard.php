<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../classes/Noticia.php';
$noticiaModel = new Noticia($conexao);
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit;
}

if (!$usuario) {
    session_destroy();
    header('Location: ../public/login.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];
$nivel = $_SESSION['usuario_nivel'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Culinária em Foco</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <?php include '../contents/header.html'; ?>

    <main class="container dashboard-shell">
        <section class="card">
            <div class="dashboard-header">
                <div>
                    <h1>Olá, <?php echo htmlspecialchars($nome); ?>!</h1>
                    <p>Gerencie suas publicações e acompanhe o que está em destaque.</p>
                </div>
                <div class="dashboard-actions">
                    <a class="btn" href="nova_noticia.php">Nova notícia</a>
                    <?php if ($nivel === 'admin'): ?>
                        <a class="btn" href="../admin/usuarios.php">Gerenciar usuários</a>
                    <?php endif; ?>
                    <a class="btn" href="../public/logout.php">Sair</a>
                </div>
            </div>

            <h3>Suas publicações</h3>
            <div class="post-list">
                <?php $resultadoNoticias = $noticiaModel->lerNoticiasPorAutor(); ?>
                <?php while ($noticia = $resultadoNoticias->fetch_assoc()): ?>
                    <article class="post-card">
                        <a href="../public/noticia.php?id=<?php echo htmlspecialchars($noticia['id']); ?>">
                            <h4><?php echo htmlspecialchars($noticia['titulo']); ?></h4>
                        </a>
                        <p><?php echo nl2br(htmlspecialchars($noticiaModel->resumirTexto($noticia['noticia'], 100))); ?></p>
                        <?php if (!empty($noticia['imagem'])): ?>
                            <img class="post-card__image" src="<?php echo htmlspecialchars($noticia['imagem']); ?>"
                                alt="Imagem da notícia" width="200">
                        <?php endif; ?>
                        <div class="dashboard-actions" style="margin-top: 12px;">
                            <a class="btn" href="editar_noticia.php?id=<?php echo $noticia['id']; ?>">Editar</a>
                            <a class="btn" href="excluir_noticia.php?id=<?php echo $noticia['id']; ?>">Excluir</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <?php include '../contents/footer.html'; ?>
</body>

</html>
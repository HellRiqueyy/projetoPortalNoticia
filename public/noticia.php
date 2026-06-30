<?php
session_start();
include_once '../config/config.php';
include_once '../classes/Noticia.php';
include_once '../classes/Comentario.php';
$noticiaModel = new Noticia($conexao);
$comentarioModel = new Comentario($conexao);
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de notícia inválido.');
}
$id = $_GET['id'];
$noticia = $noticiaModel->lerNoticiaPorId($id);
if (!$noticia) {
    die('Notícia não encontrada.');
}

// Tratamento de envio de comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    if (!isset($_SESSION['usuario_id'])) {
        $error_msg = 'Você precisa estar logado para comentar.';
    } else {
        $_SESSION['noticia_id'] = $id;
        $comentarioModel->criarComentario(trim($_POST['comentario']));
        header('Location: noticia.php?id=' . $id);
        exit;
    }
}

$comentarios = $comentarioModel->lerComentarios($id);
$nivel = $_SESSION['usuario_nivel'];
$usuario = $_SESSION['usuario_id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $noticia['titulo']; ?></title>
</head>

<body>
    <?php include '../contents/header.html'; ?>
    <h1><?php echo $noticia['titulo']; ?></h1>
    <p>Por <?php echo $noticia['autorNome']; ?> em <?php echo date('d M Y - H:i', strtotime($noticia['data'])); ?></p>
    <?php
    $imagemSrc = $noticiaModel->resolverImagemUrl($noticia['imagem']);
    ?>
    <?php if (!empty($imagemSrc)): ?>
        <img src="<?php echo htmlspecialchars($imagemSrc); ?>" alt="Imagem da notícia">
    <?php endif; ?>
    <p><?php echo nl2br($noticia['noticia']); ?></p>
    <br><br>
    <h2>Comentários(<?php echo $comentarios->num_rows; ?>)</h2>

    <?php while ($comentario = $comentarios->fetch_assoc()): ?>
        <div class="comentario">
            <p><strong><?php echo htmlspecialchars($comentario['usuarioNome']); ?></strong> comentou em
                <?php echo date('d/m/Y H:i', strtotime($comentario['data'])); ?></p>
            <p><?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?></p>
            <?php if ($usuario === $comentario['autor']): ?>
                <button>Editar comentario</button>
                <button>Apagar comentario</button>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<br><br>
    <?php if (!empty($error_msg)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_msg); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['usuario_id'])): ?>
        <form method="post" action="noticia.php?id=<?php echo $id; ?>">
            <label for="comentario">Deixe um comentário</label>
            <textarea id="comentario" name="comentario" rows="4" required></textarea>
            <button type="submit">Enviar comentário</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Faça login</a> para comentar.</p>
    <?php endif; ?>

    <?php include '../contents/footer.html'; ?>
</body>
</html>
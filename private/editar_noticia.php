<?php include 'conexao.php'; include 'funcoes.php';

$id = $_GET['id'];
$smtmt = $conexao->prepare("DELETE FROM noticias WHERE id = ?");
$smtmt->bind_param("i", $id);
$smtmt->execute();
$noticia = $smtmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $noticia = $_POST['noticia'];
    $smtmt = $conexao->prepare("UPDATE noticias SET titulo = ?, noticia = ? WHERE id = ?");
    $smtmt->bind_param("ssi", $titulo, $noticia, $id);
    $smtmt->execute();
    header("Location: dashboard.php");
}
?>

<form method="POST">
    <h2>Editar Publicação</h2>
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
    <label for="noticia">Notícia:</label>
    <textarea id="noticia" name="noticia" required><?= htmlspecialchars($noticia['noticia']) ?></textarea>
    <button type="submit">Atualizar</button>
</form>
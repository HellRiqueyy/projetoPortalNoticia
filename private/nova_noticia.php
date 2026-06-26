<?php include 'conexao.php'; include 'funcoes.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $noticia = $_POST['noticia'];
    $autor = $_POST['autor'];
    $imagem = null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem = uploadImagem($_FILES['imagem']);
    }

    $stmt = $conexao->prepare("INSERT INTO noticias (titulo, noticia, autor, imagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $titulo, $noticia, $autor, $imagem);

    if ($stmt->execute()) {
        echo "Notícia cadastrada com sucesso!";
    } else {
        echo "Erro ao cadastrar notícia: " . $stmt->error;
    }

    $stmt->close();
}
?>
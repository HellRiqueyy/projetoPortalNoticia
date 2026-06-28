<?php
class Comentario
{
    private $conn;
    private $table_name = "comentarios";

    public function __construct($banco)
    {
        $this->conn = $banco;
    }

    public function criarComentario($comentario)
    {
        $noticia_id = $_SESSION['noticia_id'];
        $usuario_id = $_SESSION['usuario_id'];
        $query = "INSERT INTO " . $this->table_name . " (noticia, autor, comentario) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("iis", $noticia_id, $usuario_id, $comentario);
        $stmt->execute();

        return $stmt;
    }
    public function apagarComentario($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt;
    }
    public function atualizarComentario($id, $comentario)
    {
        $query = "UPDATE " . $this->table_name . " SET comentario = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("si", $comentario, $id);
        $stmt->execute();
        return $stmt;
    }
    public function lerComentarios($noticia_id)
    {
        $query = "SELECT comentarios.id, comentarios.comentario, comentarios.data, comentarios.autor, usuarios.nome as usuarioNome FROM " . $this->table_name .
         " JOIN usuarios ON comentarios.autor = usuarios.id WHERE comentarios.noticia = ? ORDER BY comentarios.data DESC";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $noticia_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
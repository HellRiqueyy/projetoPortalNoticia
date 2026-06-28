<?php
class Comentarios {
    private $conn;
    private $table_name = "comentarios";

    public function __construct($banco) {
        $this->conn = $banco;
    }

    public function criarComentario($comentario) {
        $noticia_id = $_SESSION['noticia_id'];
        $usuario_id = $_SESSION['usuario_id'];
        $query = "INSERT INTO " . $this->table_name . " (noticia_id, usuario_id, comentario) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("iis", $noticia_id, $usuario_id, $comentario);
        $stmt->execute();

        return $stmt;
    }

    public function lerComentarios($noticia_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE noticia_id = ?";
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
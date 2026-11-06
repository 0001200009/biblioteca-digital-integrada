<?php
namespace App\Domain\Models;

class Livro extends Material {
    private string $autor;
    private string $isbn;

    public function __construct(int $id, string $titulo, int $quantidadeDisponivel, string $autor, string $isbn) {
        parent::__construct($id, $titulo, $quantidadeDisponivel);
        $this->autor = $autor;
        $this->isbn = $isbn;
    }

    public function getAutor(): string { return $this->autor; }
    public function getIsbn(): string { return $this->isbn; }
    public function getTipo(): string { return 'Livro'; }
}
<?php
namespace App\Domain\Models;

abstract class Material {
    protected int $id;
    protected string $titulo;
    protected int $quantidadeDisponivel;

    public function __construct(int $id, string $titulo, int $quantidadeDisponivel) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->quantidadeDisponivel = $quantidadeDisponivel;
    }

    public function getId(): int { return $this->id; }
    public function getTitulo(): string { return $this->titulo; }
    public function getQuantidadeDisponivel(): int { return $this->quantidadeDisponivel; }

    public function isDisponivel(): bool {
        return $this->quantidadeDisponivel > 0;
    }

    public function meDecrementar(): void {
        if (!$this->isDisponivel()) {
            throw new \DomainException("Material indisponível para empréstimo.");
        }
        $this->quantidadeDisponivel--;
    }

    public function meIncrementar(): void {
        $this->quantidadeDisponivel++;
    }

    abstract public function getTipo(): string;
}
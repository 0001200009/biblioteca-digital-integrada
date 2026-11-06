<?php
namespace App\Domain\Models;

abstract class Usuario {
    protected int $id;
    protected string $nome;
    protected string $email;

    public function __construct(int $id, string $nome, string $email) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
    }

    public function getId(): int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getEmail(): string { return $this->email; }

    abstract public function getTipo(): string;
    abstract public function getLimiteEmprestimos(): int;
    abstract public function getPrazoExtraDias(): int;
    abstract public function getMultaPorDia(): float;

    public function calcularPrazoDevolucao(\DateTime $dataBase = new \DateTime()): \DateTime {
        $prazoBase = 7; // 7 dias padrão
        $diasTotais = $prazoBase + $this->getPrazoExtraDias();
        $dataDevolucao = clone $dataBase;
        $dataDevolucao->modify("+{$diasTotais} days");
        return $dataDevolucao;
    }
}
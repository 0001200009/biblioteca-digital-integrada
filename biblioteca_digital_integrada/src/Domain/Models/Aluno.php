<?php
namespace App\Domain\Models;

class Aluno extends Usuario {
    public function getTipo(): string { return 'Aluno'; }
    public function getLimiteEmprestimos(): int { return 3; }
    public function getPrazoExtraDias(): int { return 0; }
    public function getMultaPorDia(): float { return 2.00; }
}
<?php
namespace App\Domain\Models;

class Professor extends Usuario {
    public function getTipo(): string { return 'Professor'; }
    public function getLimiteEmprestimos(): int { return 5; }
    public function getPrazoExtraDias(): int { return 7; }
    public function getMultaPorDia(): float { return 1.00; }
}
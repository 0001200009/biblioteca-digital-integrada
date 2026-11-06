<?php
namespace App\Domain\Models;

class Pesquisador extends Usuario {
    public function getTipo(): string { return 'Pesquisador'; }
    public function getLimiteEmprestimos(): int { return 7; }
    public function getPrazoExtraDias(): int { return 5; }
    public function getMultaPorDia(): float { return 1.50; }
}
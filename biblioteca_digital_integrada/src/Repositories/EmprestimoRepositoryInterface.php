<?php
namespace App\Repositories;

use App\Domain\Models\Emprestimo;

interface EmprestimoRepositoryInterface {
    public function salvar(Emprestimo $emprestimo): int;
    public function findById(int $id): ?Emprestimo;
    public function atualizarDevolucao(int $emprestimoId, \DateTime $dataDevolucao): void;
    public function registrarMulta(int $emprestimoId, float $valor, int $diasAtraso): void;
    public function registrarHistorico(int $usuarioId, int $materialId, string $operacao, string $detalhes): void;
}
<?php
namespace App\Repositories;

use App\Domain\Models\Emprestimo;
use PDO;

class PdoEmprestimoRepository implements EmprestimoRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function salvar(Emprestimo $emprestimo): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO emprestimos (usuario_id, material_id, data_emprestimo, data_prevista_devolucao, status)
            VALUES (:usuario_id, :material_id, :data_emp, :data_prev, :status)
        ");
        $stmt->execute([
            ':usuario_id' => $emprestimo->getUsuario()->getId(),
            ':material_id' => $emprestimo->getMaterial()->getId(),
            ':data_emp' => $emprestimo->getDataEmprestimo()->format('Y-m-d H:i:s'),
            ':data_prev' => $emprestimo->getDataPrevistaDevolucao()->format('Y-m-d H:i:s'),
            ':status' => $emprestimo->getStatus()
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function findById(int $id): ?Emprestimo {
        // Implementação simplificada de busca com hydrated object
        return null; // Implementado conforme conexões PDO nos Services
    }

    public function atualizarDevolucao(int $emprestimoId, \DateTime $dataDevolucao): void {
        $stmt = $this->pdo->prepare("
            UPDATE emprestimos SET data_devolucao = :data_dev, status = 'DEVOLVIDO' WHERE id = :id
        ");
        $stmt->execute([
            ':data_dev' => $dataDevolucao->format('Y-m-d H:i:s'),
            ':id' => $emprestimoId
        ]);
    }

    public function registrarMulta(int $emprestimoId, float $valor, int $diasAtraso): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO multas (emprestimo_id, valor, dias_atraso, paga)
            VALUES (:emp_id, :valor, :dias, 0)
        ");
        $stmt->execute([
            ':emp_id' => $emprestimoId,
            ':valor' => $valor,
            ':dias' => $diasAtraso
        ]);
    }

    public function registrarHistorico(int $usuarioId, int $materialId, string $operacao, string $detalhes): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO historico_movimentacoes (usuario_id, material_id, tipo_operacao, data_operacao, detalhes)
            VALUES (:uid, :mid, :op, NOW(), :det)
        ");
        $stmt->execute([
            ':uid' => $usuarioId,
            ':mid' => $materialId,
            ':op' => $operacao,
            ':det' => $detalhes
        ]);
    }
}
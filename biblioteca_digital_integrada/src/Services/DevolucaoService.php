<?php
namespace App\Services;

use App\Repositories\EmprestimoRepositoryInterface;
use App\Repositories\MaterialRepositoryInterface;
use App\Exceptions\DevolucaoDuplicadaException;
use PDO;

class DevolucaoService {
    private PDO $pdo;
    private EmprestimoRepositoryInterface $emprestimoRepo;
    private MaterialRepositoryInterface $materialRepo;

    public function __construct(PDO $pdo, EmprestimoRepositoryInterface $emprestimoRepo, MaterialRepositoryInterface $materialRepo) {
        $this->pdo = $pdo;
        $this->emprestimoRepo = $emprestimoRepo;
        $this->materialRepo = $materialRepo;
    }

    public function realizarDevolucao(int $emprestimoId, \DateTime $dataDevolucao): array {
        $this->pdo->beginTransaction();
        try {
            $emprestimo = $this->emprestimoRepo->findById($emprestimoId);
            if (!$emprestimo) {
                throw new \InvalidArgumentException("Empréstimo não encontrado.");
            }

            if ($emprestimo->getStatus() === 'DEVOLVIDO') {
                throw new DevolucaoDuplicadaException("Devolução já realizada anteriormente.");
            }

            $valorMulta = $emprestimo->registrarDevolucao($dataDevolucao);

            $this->emprestimoRepo->atualizarDevolucao($emprestimoId, $dataDevolucao);

            $material = $emprestimo->getMaterial();
            $material->meIncrementar();
            $this->materialRepo->atualizarQuantidadeDisponivel($material->getId(), $material->getQuantidadeDisponivel());

            if ($valorMulta > 0) {
                $intervalo = $emprestimo->getDataPrevistaDevolucao()->diff($dataDevolucao);
                $diasAtraso = (int)$intervalo->format('%a');
                $this->emprestimoRepo->registrarMulta($emprestimoId, $valorMulta, $diasAtraso);
            }

            $this->emprestimoRepo->registrarHistorico(
                $emprestimo->getUsuario()->getId(),
                $material->getId(),
                'DEVOLUCAO',
                "Devolução concluída. Multa gerada: R$ {$valorMulta}"
            );

            $this->pdo->commit();

            return [
                'emprestimo_id' => $emprestimoId,
                'status' => 'DEVOLVIDO',
                'multa_aplicada' => $valorMulta
            ];
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
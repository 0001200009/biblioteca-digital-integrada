<?php
namespace App\Services;

use App\Repositories\UsuarioRepositoryInterface;
use App\Repositories\MaterialRepositoryInterface;
use App\Repositories\EmprestimoRepositoryInterface;
use App\Domain\Models\Emprestimo;
use App\Exceptions\IndisponivelException;
use App\Exceptions\LimiteEmprestimoExcedidoException;
use PDO;

class EmprestimoService {
    private PDO $pdo;
    private UsuarioRepositoryInterface $usuarioRepo;
    private MaterialRepositoryInterface $materialRepo;
    private EmprestimoRepositoryInterface $emprestimoRepo;

    public function __construct(
        PDO $pdo,
        UsuarioRepositoryInterface $usuarioRepo,
        MaterialRepositoryInterface $materialRepo,
        EmprestimoRepositoryInterface $emprestimoRepo
    ) {
        $this->pdo = $pdo;
        $this->usuarioRepo = $usuarioRepo;
        $this->materialRepo = $materialRepo;
        $this->emprestimoRepo = $emprestimoRepo;
    }

    public function realizarEmprestimo(int $usuarioId, int $materialId): array {
        $this->pdo->beginTransaction();
        try {
            $usuario = $this->usuarioRepo->findById($usuarioId);
            $material = $this->materialRepo->findById($materialId);

            if (!$usuario || !$material) {
                throw new \InvalidArgumentException("Usuário ou Material não encontrado.");
            }

            if (!$material->isDisponivel()) {
                throw new IndisponivelException("O material não está disponível para empréstimo.");
            }

            $ativos = $this->usuarioRepo->countEmprestimosAtivos($usuarioId);
            if ($ativos >= $usuario->getLimiteEmprestimos()) {
                throw new LimiteEmprestimoExcedidoException("Limite de empréstimos excedido para o perfil.");
            }

            $agora = new \DateTime();
            $dataPrevista = $usuario->calcularPrazoDevolucao($agora);

            $material->meDecrementar();
            $this->materialRepo->atualizarQuantidadeDisponivel($material->getId(), $material->getQuantidadeDisponivel());

            $emprestimo = new Emprestimo(null, $usuario, $material, $agora, $dataPrevista);
            $empId = $this->emprestimoRepo->salvar($emprestimo);

            $this->emprestimoRepo->registrarHistorico(
                $usuarioId,
                $materialId,
                'EMPRESTIMO',
                "Empréstimo ID #{$empId} registrado com sucesso."
            );

            $this->pdo->commit();

            return [
                'id' => $empId,
                'usuario' => $usuario->getNome(),
                'material' => $material->getTitulo(),
                'data_emprestimo' => $agora->format('Y-m-d H:i:s'),
                'data_prevista_devolucao' => $dataPrevista->format('Y-m-d H:i:s')
            ];

        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
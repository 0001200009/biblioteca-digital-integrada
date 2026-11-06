<?php
namespace App\Domain\Models;

class Emprestimo {
    private ?int $id;
    private Usuario $usuario;
    private Material $material;
    private \DateTime $dataEmprestimo;
    private \DateTime $dataPrevistaDevolucao;
    private ?\DateTime $dataDevolucao;
    private string $status;

    public function __construct(
        ?int $id,
        Usuario $usuario,
        Material $material,
        \DateTime $dataEmprestimo,
        \DateTime $dataPrevistaDevolucao,
        ?\DateTime $dataDevolucao = null,
        string $status = 'ATIVO'
    ) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->material = $material;
        $this->dataEmprestimo = $dataEmprestimo;
        $this->dataPrevistaDevolucao = $dataPrevistaDevolucao;
        $this->dataDevolucao = $dataDevolucao;
        $this->status = $status;
    }

    public function getId(): ?int { return $this->id; }
    public function getUsuario(): Usuario { return $this->usuario; }
    public function getMaterial(): Material { return $this->material; }
    public function getDataEmprestimo(): \DateTime { return $this->dataEmprestimo; }
    public function getDataPrevistaDevolucao(): \DateTime { return $this->dataPrevistaDevolucao; }
    public function getDataDevolucao(): ?\DateTime { return $this->dataDevolucao; }
    public function getStatus(): string { return $this->status; }

    public function registrarDevolucao(\DateTime $dataDevolucao): float {
        if ($this->status === 'DEVOLVIDO') {
            throw new \DomainException("Empréstimo já foi devolvido.");
        }
        $this->dataDevolucao = $dataDevolucao;
        $this->status = 'DEVOLVIDO';

        if ($dataDevolucao > $this->dataPrevistaDevolucao) {
            $intervalo = $this->dataPrevistaDevolucao->diff($dataDevolucao);
            $diasAtraso = (int)$intervalo->format('%a');
            return $diasAtraso * $this->usuario->getMultaPorDia();
        }

        return 0.0;
    }
}
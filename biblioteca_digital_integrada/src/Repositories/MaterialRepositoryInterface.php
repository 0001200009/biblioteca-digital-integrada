<?php
namespace App\Repositories;

use App\Domain\Models\Material;

interface MaterialRepositoryInterface {
    public function findById(int $id): ?Material;
    public function atualizarQuantidadeDisponivel(int $id, int $novaQuantidade): void;
}
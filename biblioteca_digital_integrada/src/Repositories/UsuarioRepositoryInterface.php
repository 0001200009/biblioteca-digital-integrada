<?php
namespace App\Repositories;

use App\Domain\Models\Usuario;

interface UsuarioRepositoryInterface {
    public function findById(int $id): ?Usuario;
    public function countEmprestimosAtivos(int $usuarioId): int;
}
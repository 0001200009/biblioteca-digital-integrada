<?php
namespace App\Middleware;

use App\Exceptions\NaoAutorizadoException;

class AuthMiddleware {
    public static function autenticarEAutorizar(array $perfisPermitidos): array {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        if (!$token || !str_starts_with($token, 'Bearer ')) {
            http_response_code(401);
            echo json_encode([
                'sucesso' => false,
                'erro' => ['codigo' => 'NAO_AUTENTICADO', 'mensagem' => 'Token de autenticação ausente ou inválido.']
            ]);
            exit;
        }

        // Simulação de verificação de token / sessão decodificada
        $perfilUsuario = $_SERVER['HTTP_X_USER_ROLE'] ?? 'Leitor';

        if (!in_array($perfilUsuario, $perfisPermitidos)) {
            http_response_code(403);
            echo json_encode([
                'sucesso' => false,
                'erro' => ['codigo' => 'ACESSO_NEGADO', 'mensagem' => 'Perfil sem autorização para esta operação.']
            ]);
            exit;
        }

        return ['perfil' => $perfilUsuario];
    }
}
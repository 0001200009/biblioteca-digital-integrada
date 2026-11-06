<?php
namespace App\Controllers;

use App\Services\EmprestimoService;
use App\Services\DevolucaoService;
use App\Exceptions\IndisponivelException;
use App\Exceptions\LimiteEmprestimoExcedidoException;
use App\Exceptions\DevolucaoDuplicadaException;

class EmprestimoController {
    private EmprestimoService $emprestimoService;
    private DevolucaoService $devolucaoService;

    public function __construct(EmprestimoService $emprestimoService, DevolucaoService $devolucaoService) {
        $this->emprestimoService = $emprestimoService;
        $this->devolucaoService = $devolucaoService;
    }

    public function criar(array $body): void {
        header('Content-Type: application/json');
        try {
            $usuarioId = $body['usuario_id'] ?? null;
            $materialId = $body['material_id'] ?? null;

            if (!$usuarioId || !$materialId) {
                http_response_code(400);
                echo json_encode([
                    'sucesso' => false,
                    'erro' => ['codigo' => 'DADOS_INVALIDOS', 'mensagem' => 'Parâmetros usuario_id e material_id são obrigatórios.']
                ]);
                return;
            }

            $resultado = $this->emprestimoService->realizarEmprestimo($usuarioId, $materialId);
            http_response_code(201);
            echo json_encode([
                'sucesso' => true,
                'dados' => $resultado
            ]);

        } catch (IndisponivelException $e) {
            http_response_code(409);
            echo json_encode([
                'sucesso' => false,
                'erro' => ['codigo' => 'MATERIAL_INDISPONIVEL', 'mensagem' => $e->getMessage()]
            ]);
        } catch (LimiteEmprestimoExcedidoException $e) {
            http_response_code(422);
            echo json_encode([
                'sucesso' => false,
                'erro' => ['codigo' => 'LIMITE_EXCEDIDO', 'mensagem' => $e->getMessage()]
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'erro' => ['codigo' => 'ERRO_INTERNO', 'mensagem' => $e->getMessage()]
            ]);
        }
    }

    public function devolver(int $id, array $body): void {
        header('Content-Type: application/json');
        try {
            $dataDevolucaoStr = $body['data_devolucao'] ?? 'now';
            $dataDevolucao = new \DateTime($dataDevolucaoStr);

            $resultado = $this->devolucaoService->realizarDevolucao($id, $dataDevolucao);
            http_response_code(200);
            echo json_encode([
                'sucesso' => true,
                'dados' => $resultado
            ]);
        } catch (DevolucaoDuplicadaException $e) {
            http_response_code(409);
            echo json_encode([
                'sucesso' => false,
                'erro' => ['codigo' => 'DEVOLUCAO_DUPLICADA', 'mensagem' => $e->getMessage()]
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'erro' => ['codigo' => 'ERRO_INTERNO', 'mensagem' => $e->getMessage()]
            ]);
        }
    }
}
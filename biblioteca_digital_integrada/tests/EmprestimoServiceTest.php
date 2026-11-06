<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Domain\Models\Aluno;
use App\Domain\Models\Professor;
use App\Domain\Models\Pesquisador;
use App\Domain\Models\Livro;
use App\Domain\Models\Emprestimo;
use App\Exceptions\IndisponivelException;

class EmprestimoServiceTest extends TestCase {
    public function testCalculoMultaPesquisadorComAtraso(): void {
        $pesquisador = new Pesquisador(3, 'Marina Costa', 'marina@email.com');
        $livro = new Livro(1, 'PHP POO', 3, 'Autor X', '12345');

        $dataEmp = new \DateTime('2026-01-01 10:00:00');
        // Prazo pesquisador: 7 base + 5 extra = 12 dias -> Dev: 2026-01-13
        $dataPrev = $pesquisador->calcularPrazoDevolucao($dataEmp);

        $emprestimo = new Emprestimo(1, $pesquisador, $livro, $dataEmp, $dataPrev);

        // Devolução 6 dias após o prazo -> 2026-01-19
        $dataDev = clone $dataPrev;
        $dataDev->modify('+6 days');

        $multa = $emprestimo->registrarDevolucao($dataDev);

        // 6 dias * R$ 1.50 = R$ 9.00
        $this->assertEquals(9.00, $multa);
    }

    public function testRejeicaoMaterialIndisponivel(): void {
        $aluno = new Aluno(1, 'Ana Souza', 'ana@email.com');
        $livroIndisponivel = new Livro(1, 'PHP POO', 0, 'Autor X', '12345');

        $this->expectException(\DomainException::class);
        $livroIndisponivel->meDecrementar();
    }
}
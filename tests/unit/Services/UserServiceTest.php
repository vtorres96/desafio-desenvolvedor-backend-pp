<?php

namespace ICSSegurancaLvTests\Unit\Services\Autenticacao\Mfa\Validacao;

use Fig\Http\Message\StatusCodeInterface;
use ICSSegurancaLv\Exceptions\ICSHttpException;
use ICSSegurancaLv\Services\Autenticacao\Mfa\Validacao\ValidacaoService;
use ICSSegurancaLv\Services\BCryptService;
use ICSSegurancaLv\Services\BCryptServiceInterface;
use ICSSegurancaLvTests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\MockInterface;

/**
 * class ValidacaoServiceTest
 * @package ICSSegurancaLvTests\Unit\Services\Autenticacao\Mfa\Validacao
 * @author Victor Torres <victor.torres_taking@totalexpress.com.br>
 * @copyright Total Express <www.totalexpress.com.br>
 */
class ValidacaoServiceTest extends TestCase
{
    use WithFaker;

    /** @var \Mockery\MockInterface|\ICSSegurancaLv\Services\BCryptServiceInterface $bcryptServico */
    private MockInterface $bcryptServico;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->bcryptServico = Mockery::mock(
            BCryptService::class,
            BCryptServiceInterface::class
        );
    }

    /**
     * @return \ICSSegurancaLv\Services\Autenticacao\Mfa\Validacao\ValidacaoService
     */
    public function obterClasseConcreta()
    {
        return new ValidacaoService(
            $this->bcryptServico
        );
    }

    public function testValidarEmailComEmailValido()
    {
        $this->expectNotToPerformAssertions();
        $this->obterClasseConcreta()->validarEmail('test@example.com');
    }

    public function testValidarEmailComEmailInvalido()
    {
        try {
            $this->obterClasseConcreta()->validarEmail('invalidemail');
            $this->fail('Exceção RuntimeException esperada.');
        } catch (ICSHttpException $exception) {
            $this->assertEquals(
                StatusCodeInterface::STATUS_BAD_REQUEST,
                $exception->getCode()
            );
        }
    }

    public function testIsMfaExpirada()
    {
        $this->assertFalse($this->obterClasseConcreta()->isMfaExpirada(['loginTimestamp' => time() - 100]));
        $this->assertTrue($this->obterClasseConcreta()->isMfaExpirada(['loginTimestamp' => 0]));
    }

    public function testHasMfaPendente()
    {
        $funcionarioMfa = [
            "funcionarioId" => "64333",
            "codigo" => "196896",
            "loginTimestamp" => "0",
            "criacaoTimestamp" => "1714701627"
        ];
        $expiracaoSucesso = ['expiracaoTimestamp' => time() + 100];
        $expiracaoErro = ['expiracaoTimestamp' => time() - 100];
        $this->assertTrue(
            $this->obterClasseConcreta()->hasMfaPendente(
                array_merge($funcionarioMfa, $expiracaoSucesso)
            )
        );
        $this->assertFalse(
            $this->obterClasseConcreta()->hasMfaPendente(
                array_merge($funcionarioMfa, $expiracaoErro)
            )
        );
    }

    public function testIsDominioTotalExpress()
    {
        $this->assertTrue($this->obterClasseConcreta()->isDominioTotalExpress('user@totalexpress.com'));
        $this->assertFalse($this->obterClasseConcreta()->isDominioTotalExpress('user@example.com'));
    }

    public function testCanEnviarEmail()
    {
        $this->assertFalse($this->obterClasseConcreta()->canEnviarEmail('test@example.com'));
        $this->assertTrue($this->obterClasseConcreta()->canEnviarEmail('user@totalexpress.com'));
    }
}

<?php

namespace ICSSegurancaLvTests\Unit\Http\Controllers\Autenticacao\Mfa;

use Faker\Factory;
use Fig\Http\Message\StatusCodeInterface;
use ICSSegurancaLv\Http\Controllers\Autenticacao\Mfa\MfaController;
use ICSSegurancaLv\Http\Requests\AutenticacaoRequest;
use ICSSegurancaLv\Services\Autenticacao\Mfa\MfaServiceFactoryMethodInterface;
use ICSSegurancaLv\Services\Autenticacao\Mfa\MfaServiceInterface;
use ICSSegurancaLvTests\TestCase;
use Illuminate\Http\JsonResponse;
use Mockery;

/**
 * Class MfaControllerTest
 * @package   ICSSegurancaLvTests\Unit\Http\Controllers\Autenticacao\Mfa
 * @author    Victor Torres <victor.torres_taking@totalexpress.com.br>
 * @copyright Total Express <www.totalexpress.com.br>
 */
class MfaControllerTest extends TestCase
{
    /** @var \ICSSegurancaLv\Services\Autenticacao\Mfa\MfaServiceFactoryMethodInterface|Mockery\MockInterface */
    private $mfaFabrica;

    /** @var \ICSSegurancaLv\Services\Autenticacao\Mfa\MfaServiceInterface|Mockery\MockInterface */
    private $mfaServico;

    /** @var \Faker\Generator */
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mfaFabrica = Mockery::mock(MfaServiceFactoryMethodInterface::class);
        $this->mfaServico = Mockery::mock(MfaServiceInterface::class);
        $this->faker = Factory::create('pt_BR');
    }

    private function obterClasseConcreta(): MfaController
    {
        return new MfaController($this->mfaFabrica);
    }

    public function testAutenticarRetornandoStatusOk(): void
    {
        $dados = [
            'login' => 'tex_2024',
            'password' => 'Total@1234'
        ];
        $token = 'token_ics_authentication';
        $resposta = [
            'data' => [
                'ICS-Authentication' => $token
            ]
        ];

        $request = $this->prophesize(AutenticacaoRequest::class);
        $request->validated()->willReturn($dados);
        $this->mfaFabrica->shouldReceive('factoryByLogin')
            ->with($dados['login'])->andReturn($this->mfaServico);
        $this->mfaServico->shouldReceive('autenticar')->andReturn($resposta);

        $retorno = $this->obterClasseConcreta()->autenticar($request->reveal());

        $this->assertInstanceOf(JsonResponse::class, $retorno);
        $this->assertEquals(StatusCodeInterface::STATUS_OK, $retorno->getStatusCode());
    }

    public function testAutenticarRetornandoStatusMovedPermanently(): void
    {
        $dados = [
            'login' => 'tex_2024',
            'password' => 'Total@1234'
        ];
        $token = 'access_token_mfa';
        $resposta = [
            'data' => $token
        ];

        $request = $this->prophesize(AutenticacaoRequest::class);
        $request->validated()->willReturn($dados);
        $this->mfaFabrica->shouldReceive('factoryByLogin')
            ->with($dados['login'])->andReturn($this->mfaServico);
        $this->mfaServico->shouldReceive('autenticar')->andReturn($resposta);

        $retorno = $this->obterClasseConcreta()->autenticar($request->reveal());

        $this->assertInstanceOf(JsonResponse::class, $retorno);
        $this->assertEquals(StatusCodeInterface::STATUS_MOVED_PERMANENTLY, $retorno->getStatusCode());
    }
}

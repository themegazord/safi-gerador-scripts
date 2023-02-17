<?php
namespace Options\Cadastros;

require_once 'vendor/autoload.php';
require_once '_Utils/_Utils.php';
require_once 'DTO/CadastrosDTO.php';
require_once 'Options/Cadastros/MenuCadastros.php';

use Ark4ne\XlReader\Exception\ReaderException;
use Ark4ne\XlReader\Exception\UnsupportedFileFormat;
use Ark4ne\XlReader\Factory;
use DTO\CadastrosDTO;

use _Utils\Utils;
class Fornecedor
{
    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    public static function geraTXT(): void {
           Utils::cleanCMD();
           Fornecedor::captaDados();
    }

    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    private static function captaDados(): void
    {
        $file = 'layout-cadastro.xlsx';
        $reader = Factory::createReader($file);
        $reader->load();
        foreach ($reader->read(1) as $row) {
            $cadastro = new CadastrosDTO(
                razaosocial: Utils::removingSingleQuotesFromString($row['A']),
                namefantasia: Utils::removingSingleQuotesFromString($row['B']),
                cnpj: Utils::removingCharactersFromNumbers($row['C']),
                cpf: 'NOPE',
                endereco: Utils::removingSingleQuotesFromString($row['D']),
                numero: intval($row['E']),
                bairro: Utils::removingSingleQuotesFromString($row['F']),
                cidade: intval($row['G']),
                cep: Utils::removingCharactersFromNumbers($row['H']),
                telefone: Utils::removingCharactersFromNumbers($row['I']),
                email: $row['J'],
                ie: $row['K'] == 'ISENTO' ? $row['K'] : Utils::removingCharactersFromNumbers($row['K'])
            );
            Fornecedor::insereNoBlocoDeNotas($cadastro);
        }
        Fornecedor::cleanTwoFirstLines('script-cadastros.sql');
        MenuCadastros::cadastros_menu('script-cadastros.sql');
    }

    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    private static function insereNoBlocoDeNotas(CadastrosDTO $cadastros): void
    {
        $arquivo = fopen("script-cadastros.sql", "a+");

        if(!$arquivo) {
            echo "Não foi possivel abrir o arquivo";
            sleep(3);
            MenuCadastros::cadastros_menu('');
        }
        $scriptCadastroGeral = "INSERT INTO CADASTROGERAL (COD_CADASTRO, CLIENTE, FORNECEDOR, PESSOA, NOME, FANTASIA, NOMECONTATO, ENDERECO, BAIRRO, CEP, TELEFONE, CNPJ_CPF, INSCMUNICIPAL, INSCINSS, MALADIRETA, CONTABANCARIA, OBSERVACOES, EMAIL, COD_CIDADE, COD_VENDEDOR, CONTACONTAB, TRANSPORTADOR, OBSSTR, PREFERENCIA_COBRANCA, BLOQINFORMACOESFINANCEIRAS, DATACADASTRO, DATAATUALIZACAO, LIMITEDECREDITO, MENSAGEMFINANCEIRO, PCODIGO, PF_DATANASCIMENTO, PF_NOMEDAMAE, PF_NOMEDOPAI, PF_NOMEDOCONJUGE, PF_NOMELOCALTRABALHO, PF_FONELOCALTRABALHO, PF_CARGO, PF_TEMPOTRABALHO, PF_RESIDENCIAPROPRIA, PF_VALORALUGUEL, PF_CJ_NASCIMENTO, PF_CJ_RG, PF_CJ_NOMEDAMAE, PF_CJ_NOMEDOPAI, NUMEROENDERECO, COD_CONVENIO, COD_ASSOCIADO, MATRICULA, EMAIL_NFE, PF_RG, CODIGOSUFRAMA, COMPLEMENTO, REGIMETRIBUTARIO, DESCONTO_ICMS, FUNCIONARIO, PERC_DESC_PONTUALIDADE, PERC_DESC_REDE, TIPO_CADASTRO, DIAS_INATIVO_AVISO, PF_SEXO, PF_ESTADOCIVIL, PF_NATURALIDADE, ASSOCIADO, NOMEFONETICO, EMITE_NF, DESCONTO_FOLHA, AUTORIZACAO_DOWN_NFE, COD_DECRETO, PERC_DESCONTO_PIS, PERC_DESCONTO_COFINS, REPRESENTANTE, RECOLHE_ST_ANTECIPADO, COD_TABELA_PRECO, TABELADEPRECO, MOTIVO_DESCONTO_ICMS, DIA_FECHAMENTO_FINANCEIRO, RECUSADO_INTEGRACAO, COD_BAIRRO, COMPRACOMREQUISICAO, SITUACAO) VALUES (gen_id(cod_cadastrogen, 1), 'S', 'N', 'J', '$cadastros->razaosocial', '$cadastros->namefantasia', NULL, '$cadastros->endereco', '$cadastros->bairro', '$cadastros->cep', '67$cadastros->telefone', '$cadastros->cnpj', NULL, NULL, NULL, NULL, NULL, '$cadastros->email', $cadastros->cidade, NULL, NULL, 'N', NULL, NULL, 'N', '27-MAR-2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', NULL, NULL, NULL, NULL, NULL, '6325', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3', 'N', 'N', 0, 0, 'C', 0, NULL, NULL, NULL, NULL, 'DIT DE POD ALI SAMO AMLE TDA', NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'A');\r\n";
        $scriptInscricao = "INSERT INTO INSCRICAO (COD_INSCRICAO, NOME, FANTASIA, CONTATO, ENDERECO, NUMEROENDERECO, BAIRRO, CEP, TELEFONE, EMAIL, ROTEIRO, COD_CADASTRO, COD_CIDADE, DATAROTEIRO, INSCRICAO, ROT_STR, PCODIGO, COMPLEMENTO, COD_BAIRRO, SITUACAO) VALUES (gen_id(cod_inscricaogen, 1), '$cadastros->razaosocial', '$cadastros->namefantasia', NULL, '$cadastros->endereco', '$cadastros->numero', '$cadastros->bairro', '$cadastros->cep', '$cadastros->telefone', '$cadastros->email', NULL, gen_id(cod_cadastrogen, 0), $cadastros->cidade, NULL, '$cadastros->ie', NULL, NULL, '', NULL, 'A');\r\n";
        fwrite($arquivo, $scriptCadastroGeral);
        fwrite($arquivo, $scriptInscricao);
        fclose($arquivo);
    }

    private static function cleanTwoFirstLines(string $nome_arquivo):void {

        $conteudo = file_get_contents($nome_arquivo);

        $linhas = explode("\r\n", $conteudo);

        array_splice($linhas, 0, 2);

        $novo_conteudo = implode("\r\n", $linhas);

        file_put_contents($nome_arquivo, $novo_conteudo);
    }
}
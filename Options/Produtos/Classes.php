<?php

namespace Options\Produtos;

require_once 'vendor/autoload.php';
require_once '_Utils/_Utils.php';
require_once 'DTO/ClassesDTO.php';
require_once 'Options/Produtos/MenuProdutos.php';

use _Utils\Utils;
use Ark4ne\XlReader\Exception\ReaderException;
use Ark4ne\XlReader\Exception\UnsupportedFileFormat;
use Ark4ne\XlReader\Factory;
use DTO\ClassesDTO;
use Options\Cadastros\MenuCadastros;

class Classes
{
    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    public static function geraTXT(): void {
        Utils::cleanCMD();
        Classes::captaDados();
    }

    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    public static function captaDados(): void
    {
        $file = 'layout-classe-produtos.xlsx';
        $reader = Factory::createReader($file);
        $reader->load();
        foreach ($reader->read(1) as $row) {
            $classes = new ClassesDTO(
                descricao_classe: Utils::removingSingleQuotesFromString($row['A'])
            );
            Classes::insereNoBlocoDeNotas($classes);
        }
        Classes::cleanFirstLine('script-classes-produtos.sql');
        MenuProdutos::produtos_menu('script-classes-produtos.sql');
    }

    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    public static function insereNoBlocoDeNotas(ClassesDTO $classe): void
    {
        $arquivo = fopen("script-classes-produtos.sql", "a+");

        if(!$arquivo) {
            echo "Não foi possivel abrir o arquivo";
            sleep(3);
            MenuCadastros::cadastros_menu('');
        }

        $scriptClassesProdutos = "INSERT INTO EST_CLASSE (COD_CLASSE, DESCRICAOCLASSE) VALUES (gen_id(cod_classegen, 1), '$classe->descricao_classe');\r\n";
        fwrite($arquivo, $scriptClassesProdutos);
        fclose($arquivo);
    }

    public static function cleanFirstLine(string $nome_arquivo): void
    {
        $conteudo = file_get_contents($nome_arquivo);

        $linhas = explode("\r\n", $conteudo);

        array_splice($linhas, 0, 1);

        $novo_conteudo = implode("\r\n", $linhas);

        file_put_contents($nome_arquivo, $novo_conteudo);
    }
}

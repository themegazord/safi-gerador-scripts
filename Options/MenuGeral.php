<?php
namespace Options;
require_once 'vendor/autoload.php';
require_once 'Cadastros/MenuCadastros.php';
require_once 'Produtos/MenuProdutos.php';
require_once '_Utils/_Utils.php';

use _Utils\Utils;

use Ark4ne\XlReader\Exception\ReaderException;
use Ark4ne\XlReader\Exception\UnsupportedFileFormat;
use Options\Cadastros\MenuCadastros;
use Options\Produtos\MenuProdutos;

class MenuGeral
{
    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    public static function geral_menu(): void {
        Utils::cleanCMD();
        $opcao = 0;
        echo "Menu de Importação: \n";
        echo "1 - Cadastros\n";
        echo "2 - Produtos\n";
        echo "Opção: ";
        $opcao = intval(fgets(STDIN));

        match ($opcao) {
            1 => MenuCadastros::cadastros_menu(''),
            2 => MenuProdutos::produtos_menu(''),
        };
    }
}
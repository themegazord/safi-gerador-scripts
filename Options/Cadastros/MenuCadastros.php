<?php
namespace Options\Cadastros;

require_once 'vendor/autoload.php';
require_once 'Options/Cadastros/Fornecedor.php';
require_once 'Options/MenuGeral.php';
require_once '_Utils/_Utils.php';

use Ark4ne\XlReader\Exception\ReaderException;
use Ark4ne\XlReader\Exception\UnsupportedFileFormat;
use Options\MenuGeral;
use _Utils\Utils;

class MenuCadastros
{

    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    public static function cadastros_menu(string $arquivo): void
    {
        $opcao = 0;
        Utils::cleanCMD();
        if(!empty($arquivo)) {
            echo "---***---***---***---***---***---***---***---***---\n";
            echo "Arquivo $arquivo criado com sucesso\n";
            echo "---***---***---***---***---***---***---***---***---\n\n";
        }
        echo "Menu de Cadastros:\n";
        echo "1 - Fornecedores\n";
        echo "2 - Voltar\n";
        echo "Opção: ";
        $opcao = intval(fgets(STDIN));

        match($opcao) {
            1 => Fornecedor::geraTXT(),
            2 => MenuGeral::geral_menu(),
        };
    }
}
<?php

namespace Options\Produtos;

require_once 'vendor/autoload.php';
require_once 'Options/Produtos/Classes.php';
require_once 'Options/MenuGeral.php';
require_once '_Utils/_Utils.php';

use Ark4ne\XlReader\Exception\ReaderException;
use Ark4ne\XlReader\Exception\UnsupportedFileFormat;
use Options\MenuGeral;
use _Utils\Utils;
class MenuProdutos
{
    /**
     * @throws UnsupportedFileFormat
     * @throws ReaderException
     */
    public static function produtos_menu(string $arquivo): void
    {
        Utils::cleanCMD();
        if(!empty($arquivo)) {
            echo "---***---***---***---***---***---***---***---***---\n";
            echo "Arquivo $arquivo criado com sucesso\n";
            echo "---***---***---***---***---***---***---***---***---\n\n";
        }
        echo "Menu de CadastrosDTO:\n";
        echo "1 - Classes\n";
        echo "2 - Voltar\n";
        echo "Opção: ";
        $opcao = intval(fgets(STDIN));

        match($opcao) {
            1 => Classes::geraTXT(),
            2 => MenuGeral::geral_menu(),
        };
    }
}
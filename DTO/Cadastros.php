<?php

namespace DTO;

readonly class Cadastros
{
    public function __construct(
        public string $razaosocial,
        public string $namefantasia,
        public string $cnpj,
        public string $cpf,
        public string $endereco,
        public int    $numero,
        public string $bairro,
        public int $cidade,
        public string $cep,
        public string $telefone,
        public string $email,
        public string $ie,
    ){}
}
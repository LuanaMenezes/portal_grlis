<?php

namespace App\Imports;

use App\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ClientesImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
            return new Cliente([
                'nome'  => $row['nome'],
                'cnpj' => $row['cnpj'],
                'cga' => $row['cga'],
                'senha' => $row['senha'],
                'uniprofissional' => $row['uniprofissional'],
                'qtd_socios' => $row['quantidade_de_socios'],
            ]);
       
    }

 /*   /**
    * @return int
    */
  /*  public function startRow(): int
    {
        return 1;
    }
*/

    public function rules(): array
    {
        return [
            '*.nome' => 'required|string|max:255',
            '*.cnpj' => 'required|unique:clientes,cnpj|max:18|cnpj',
            '*.cga' => 'required|unique:clientes,cga|max:14|',
            '*.senha' => 'required',
            '*.uniprofissional' => 'required',
            '*.qtd_socios' => 'required_if:uniprofissional,S',
        ];
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;
use App\Models\ProdutosModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\HTTP\ResponseInterface;

class Produtos extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $produtosModel = new ProdutosModel();
        $categoriasModel = new CategoriaModel();

        $page = $this->request->getGet('page') ?? 1;
        $itensPorPagina = 10;

        $produtos = $produtosModel
            ->orderBy('produtos.id', 'DESC')
            ->where('produtos.status !=', 0)
            ->paginate($itensPorPagina);

        // Adiciona o nome da categoria em cada produto
        foreach ($produtos as &$produto) {
            $categoria = $categoriasModel->find($produto['categoria_id']); // assume que o campo do id é categoria_id
            $produto['categoria'] = $categoria ? $categoria['nome'] : 'Sem categoria';
        }
        unset($produto); // importante para não manter referência

        $paginacao = $produtosModel->pager;

        $data = [
            'produtos' => $produtos,
            'paginacao' => $paginacao,
            'pager' => $page
        ];

        return view('/dashboard/cadastros/produtos/index', $data);
    }



    public function cadastrar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $categoriasModel = new CategoriaModel();

        $data = [
            'categorias' => $categoriasModel->getAtivos(),
        ];

        return view('/dashboard/cadastros/produtos/cadastrar', $data);
    }

    public function salvar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $produtosModel = new ProdutosModel();

        $data = [
            'nome' => $this->request->getPost('nome'),
            'numero_serie' => $this->request->getPost('numero_serie'),
            'sku' => $this->request->getPost('sku'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'preco_diaria' => $this->request->getPost('preco_diaria'),
            'valor_minimo' => $this->request->getPost('valor_minimo'),
            'quantidade' => $this->request->getPost('quantidade'),
            'obs' => $this->request->getPost('obs'),
            'acessorios' => $this->request->getPost('acessorios'),
            'aditivo_contratual' => $this->request->getPost('aditivo_contratual'),
            'preco_produto' => $this->request->getPost('preco_produto'),
            'data_compra' => $this->request->getPost('data_compra'),
        ];

        $id = $produtosModel->insert($data);
        if (is_int($id)) {
            return redirect()->to('/produtos')->with('success', 'Cliente cadastrada com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }
    }

    public function edita($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $produtosModel = new ProdutosModel();
        $categoriasModel = new CategoriaModel();
        $page = $this->request->getGet('page') ?? 1;
        $data = [
            'produto' => $produtosModel->find($id),
            'categorias' => $categoriasModel->getAtivos(),
            'pager' =>  $page
        ];
        return view('dashboard/cadastros/produtos/editar', $data);
    }

    public function editar($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $produtosModel = new ProdutosModel();
        $produtosModel->find($id);

        $data = [
            'nome' => $this->request->getPost('nome'),
            'numero_serie' => $this->request->getPost('numero_serie'),
            'sku' => $this->request->getPost('sku'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'preco_diaria' => $this->request->getPost('preco_diaria'),
            'valor_minimo' => $this->request->getPost('valor_minimo'),
            'quantidade' => $this->request->getPost('quantidade'),
            'obs' => $this->request->getPost('obs'),
            'acessorios' => $this->request->getPost('acessorios'),
            'aditivo_contratual' => $this->request->getPost('aditivo_contratual'),
            'preco_compra' => $this->request->getPost('preco_compra'),
            'data_compra' => $this->request->getPost('data_compra'),
        ];

        $page = $this->request->getPost('page') ?? 1;

        $id = $produtosModel->update($id, $data);
        if ($id) {
            return redirect()->to('/produtos?page=' . $page)
                ->with('success', 'Produto atualizado com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar produto.');
        }
    }

    public function excluir($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $produtosModel = new ProdutosModel();
        $produtosModel->find($id);

        $dados = [
            'status' => 0,
        ];

        $produtosModel->update($id, $dados);
        return redirect()->to('/produtos')->with('success', 'Produto desativado com sucesso.');
    }

    public function buscar()
    {
        $nome = $this->request->getGet('nome');

        $produtosModel = new ProdutosModel();

        if (empty($nome)) {
            $produtos = $produtosModel->where('produtos.status !=', 0)->findAll();
        } else {
            $produtos = $produtosModel->like('nome', $nome, 'both')->where('produtos.status !=', 0)->findAll();
        }

        return $this->response->setJSON($produtos);
    }

    public function salvarCategoria()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Usuário não logado']);
        }

        $categoriasModel = new CategoriaModel();

        $dadosRecebidos = $this->request->getJSON(true); // Pega o JSON enviado

        if (!isset($dadosRecebidos['nome']) || empty(trim($dadosRecebidos['nome']))) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nome da categoria é obrigatório']);
        }

        $nome = trim($dadosRecebidos['nome']);

        // Você pode colocar uma validação extra para não cadastrar nomes duplicados, se quiser

        $data = [
            'nome' => $nome,
            'ativo' => 1 // Se você usa controle de ativo/inativo
        ];

        $id = $categoriasModel->insert($data);

        if (is_int($id) || is_numeric($id)) {
            return $this->response->setJSON([
                'success' => true,
                'categoria' => [
                    'id' => $id,
                    'nome' => $nome
                ]
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Erro ao salvar a categoria']);
        }
    }
    public function exportarExcel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $produtosModel = new \App\Models\ProdutosModel();
        $categoriaModel = new \App\Models\CategoriaModel();

        $produtos = $produtosModel
            ->where('status !=', 0)
            ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cabeçalhos
        $cabecalhos = [
            'ID',
            'Nome',
            'Categoria',
            'Número de Série',
            'SKU',
            'Preço Diária',
            'Valor Mínimo',
            'Quantidade',
            'Observações',
            'Aditivo Contratual',
            'Acessórios',
            'Status',
            'Criado em',
            'Atualizado em',
            'Data Compra',
            'Preço Compra'
        ];

        $coluna = 'A';
        foreach ($cabecalhos as $titulo) {
            $sheet->setCellValue($coluna . '1', $titulo);
            $coluna++;
        }

        // Dados
        $linha = 2;
        foreach ($produtos as $p) {
            $categoria = $categoriaModel->find($p['categoria_id']);
            $categoriaNome = $categoria ? $categoria['nome'] : 'Sem categoria';

            $sheet->setCellValue('A' . $linha, $p['id']);
            $sheet->setCellValue('B' . $linha, $p['nome']);
            $sheet->setCellValue('C' . $linha, $categoriaNome);
            $sheet->setCellValue('D' . $linha, $p['numero_serie']);
            $sheet->setCellValue('E' . $linha, $p['sku']);
            $sheet->setCellValue('F' . $linha, $p['preco_diaria']);
            $sheet->setCellValue('G' . $linha, $p['valor_minimo']);
            $sheet->setCellValue('H' . $linha, $p['quantidade']);
            $sheet->setCellValue('I' . $linha, $p['obs']);
            $sheet->setCellValue('J' . $linha, $p['aditivo_contratual']);
            $sheet->setCellValue('K' . $linha, $p['acessorios']);
            $sheet->setCellValue('L' . $linha, $p['status']);
            $sheet->setCellValue('M' . $linha, $p['created_at']);
            $sheet->setCellValue('N' . $linha, $p['updated_at']);
            $sheet->setCellValue('O' . $linha, $p['data_compra']);
            $sheet->setCellValue('P' . $linha, $p['preco_compra']);
            $linha++;
        }

        // Ajusta o tamanho automático das colunas
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Gera o arquivo Excel
        $writer = new Xlsx($spreadsheet);

        $filename = 'produtos_' . date('Y-m-d_His') . '.xlsx';

        // Envia o arquivo para download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}

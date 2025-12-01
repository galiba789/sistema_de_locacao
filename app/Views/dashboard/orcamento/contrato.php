<div style="display: flex;justify-content: center;width: 100vw;">
    <img src="<?= PL_BASE_DIST . '/images/play.png' ?>" alt="play" style="width: 150px;">
</div>
<div style="margin: 0; margin-top: -12px;color: #779EE5;font-family: calibri;font-size: 16px;font-weight: bold;text-align: center;">
    <p>Orçamento de Locação de Equipamentos Eletrônicos</p>
</div>
<div style="width: 850px; margin: 0px auto;">
    <div style="text-align: center;">
        <span style="font-size: 16px;"><b>ORÇAMENTO</b></span>
        <span style="float: right;">N°: <?= $orcamento['id'] ?></span>
    </div>

    <div style="text-align: justify;">
        <p>
            Orçamento emitido para <b><?= $cliente['tipo'] == 1 ? $cliente['nome'] : $cliente['razao_social'] ?></b>, CPF/CNPJ: <b><?= $cliente['tipo'] == 1 ? $cliente['cpf'] : $cliente['cnpj'] ?></b>, residente em <b><?= $cliente['logradouro'] ?></b>, número <b><?= $cliente['numero'] ?></b>, <b><?= $cliente['bairro'] ?></b>, <b><?= $cliente['localidade'] ?></b>/<b><?= $cliente['estado'] ?></b>, CEP: <b><?= $cliente['cep'] ?></b>.
        </p>
    </div>

    <div style="font-family: Arial; line-height: 20px;font-size: 14px;">
        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">Itens do Orçamento</div>
        <table cellspacing="0" cellpadding="5px" border="solid" style="font-size:14px; width: 100%;">
            <tbody>
                <tr>
                    <th style="width:150px;">N° de Série</th>
                    <th style="width: 600px;">Equipamento</th>
                    <th style="width: 100px">Quantidade</th>
                </tr>
                <?php foreach ($orcamento_produtos as $produto): ?>
                    <tr>
                        <td style="text-align: center;"> <?= $produto['numero_serie'] ?> </td>
                        <td> <?= $produto['nome'] ?> <br> <?= $produto['acessorios'] ?> </td>
                        <td style="text-align: center;"> <?= $produto['quantidade'] ?> </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="margin: 20px 0;">
            <strong>Acessórios da locação:</strong>
            <p><?= $orcamento['acessorios'] ?></p>
        </div>
        <div style="margin: 20px 0;">
            <strong>Valor Total do Orçamento: R$ </strong><?= $orcamento['valor_total'] ?>
            <br>
            <strong>Forma de pagamento: </strong><?= $orcamento['forma_pagamento'] ?>
            <br>
            <strong>Data prevista para retirada: </strong><?= date('d/m/Y', strtotime($orcamento['data_entrega'])) ?>
            <br>
            <strong>Data prevista para devolução: </strong><?= date('d/m/Y', strtotime($orcamento['data_devolucao'])) ?>
        </div>
    </div>
</div>
<div style="text-align: center; margin-top: 50px; color: #779EE5; font-family: calibri; font-size: 14px; font-weight: bold;">
    <p>PLAYCOM TECNOLOGIA LTDA</p>
    <p>Telefones: (38) 9147-7706 / (38) 3082-4909</p>
    <p>Rua Juca Macedo, 801 - CEP: 39401-044 – Bairro Funcionários – Montes Claros / MG</p>
    <p>www.playlocacoes.com.br / contato@playlocacoes.com.br</p>
</div>

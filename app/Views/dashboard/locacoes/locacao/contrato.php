<div style="font-family: Arial;
  line-height: 20px;
  font-size: 12px;">
<div style="display: flex;justify-content: center;width: 100vw;">
    <img src="<?= PL_BASE_DIST . '/images/play.png' ?>" alt="play" style="width: 150px;">
</div>
<div style="margin: 0; margin-top: -12px;color: #779EE5;font-family: calibri;font-size: 16px;font-weight: bold;text-align: center;">
    <p>Aluguel de Equipamentos Eletrônicos</p>
</div>
<div style="width: 850px; margin: 0px auto;">
    <div style="text-align: center;">
        <span style="font-size: 16px;">
            <b>CONTRATO DE LOCAÇÃO</b>
        </span>
        <span style="float: right;">
            N°: <?= $locacao['id'] ?>
        </span>
    </div>

    <div style="text-align: justify;">
        <p>
            Pelo presente instrumento particular de contrato <b><?= $cliente['tipo'] == 1 ? $cliente['nome'] : $cliente['razao_social'] ?></b>, inscrita sob CNPJ/CPF de número <b><?= $cliente['tipo'] == 1 ? $cliente['cpf'] : $cliente['cnpj'] ?></b> e residente no endereço <b><?= $cliente['logradouro'] ?></b>, número <b><?= $cliente['numero'] ?></b>, <b><?= $cliente['bairro'] ?></b>, <b><?= $cliente['localidade'] ?></b>/<b><?= $cliente['estado'] ?></b>, CEP: <b><?= $cliente['cep'] ?></b>, doravante denominada LOCATÁRIO juntamente com <b>PLAYCOM TECNOLOGIA LTDA</b>, inscrita sob CNPJ de número <b>20.364.612/0001-70</b> e localizada no endereço Rua Juca Macedo, 801 – Funcionários – Montes Claros/MG doravante denominado LOCADOR vem por meio deste firmar um contrato de locação nas condições e equipamento(s) listado(s) abaixo:
        </p>
    </div>

    <div style="font-family: Arial;font-size: 12px;">
        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">
            Cláusula Primeira - Dos Objetos
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>1. 1</strong> Constitui objetos deste instrumento a locação de:
        </div>
        <table cellspacing="0" cellpadding="5px" border="solid" style="font-size:12px;">
            <tbody>
                <tr>
                    <th style="width:150px;">N° de Série</th>
                    <th style="width: 600px;">Equipamento</th>
                    <th style="width: 100px">Quantidade</th>
                </tr>

                <?php foreach ($locacao_produtos as $locacao_produto):?>

                    <tr>
                        <td style="text-align: center;"><?= $locacao_produto['numero_serie'] ?></td>
                        <td><?= $locacao_produto['nome'] ?> <br> <?=$locacao_produto['acessorios']?></td>
                        <td style="text-align: center;"><?= $locacao_produto['quantidade'] ?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <div style="display: block;margin: 20px 0;">
            <strong>Acessorios da locação:</strong>
            <p><?=$locacao['acessorios']?></p>
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>1. 2</strong> Proibição de transferência: O locatário não pode sublocar, emprestar ou ceder os equipamentos sem autorização do locador.
        </div>
        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">
            Cláusula Segunda – Obrigações do locador
        </div>
        <div style="display: block;margin: 20px 0;">
            Constituem Obrigações do locador:
            <br>
             Entregar os equipamentos em perfeito funcionamento e dentro do prazo combinado.

Garantir a entrega no endereço acordado.

Substituir equipamentos com defeito (não causados por mau uso) em até 24h, conforme disponibilidade.
        </div>

        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">
            Cláusula terceira – Obrigações do locatário
        </div>

        <div style="display: block;margin: 20px 0; text-align:justify">
            Constituem Obrigações do locador:
            <br>
        Zelar pelos equipamentos e, em caso de danos, perda ou roubo, substituir ou pagar pelo valor de mercado em até 5 dias.

Não realizar consertos por conta própria; deve comunicar o locador em caso de falhas.

Reembolsar reparos por danos parciais ou uso inadequado.

Ressarcir o locador por recusa de devolução ou por danos, incluindo lucros cessantes.

Não mudar o local de uso dos equipamentos sem autorização prévia.
        </div>
        

        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">
            Cláusula quarta – Vigência e pagamento
        </div>
        <div style="display: block;">
        O contrato pode ser encerrado por qualquer parte com 24h de aviso prévio.
O pagamento deve ser feito na entrega dos equipamentos.
        </div>
        
        <div style="display: block;">
        Pela locação, as partes ajustam o valor e a vigência contratual:
            <br>
            <b>Valor da Locação: R$ </b><?= $locacao['valor_total'] ?>
            <br>
            <b>Forma de pagamento: </b><?= $locacao['condicao'] == 1 ? 'A vista' : ' ' ?> <?= $locacao['forma_pagamento'] ?>
            <br>
            <b>Data de retirada: </b><?= date('d/m/Y H:i:s', strtotime($locacao['data_entrega'])) ?>
            <br>
            <b>Data de entrega: </b><?= date('d/m/Y H:i:s', strtotime($locacao['data_devolucao'])) ?>
        
            <p>Por estarem assim justos e contratados, firmam o presente instrumento em duas vias de igual teor.</p>
        </div>

        <?php setlocale(LC_TIME, 'pt_BR.utf8');?>
        <div style="text-align: center;">
            Montes Claros, <?= strftime('%d de %B de %Y', strtotime('today')) ?>
        </div>
        <div>
            <div style="float:left; margin-left: 35px;">______________________________</div>
            <div style="float: right; margin-right: 35px;">______________________________</div>
        </div>
        <div style="clear: both;"></div>
        <div>
        <div style="float:left; margin-left: 120px; margin-top: -15px;"><br>LOCATÁRIO</div>
        <div style="float: right; margin-right: 130px; margin-top: -15px;"><br>LOCADOR</div>
        </div>
    </div>
</div>
<div style="margin: 0;margin-top: 50px;color: #779EE5;font-family: calibri;font-size: 12px;font-weight: bold;text-align: center;">
    <p>PLAYCOM TECNOLOGIA LTDA</p>
    <p>Telefones: (38) 9147-7706 / (38) 3082-4909</p>
    <p>Rua Juca Macedo, 801 - CEP: 39401-044 – Bairro Funcionários – Montes Claros / MG</p>
    <p>www.playlocacoes.com.br / contato@playlocacoes.com.br</p>
</div>
</div>
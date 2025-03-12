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

    <div style="font-family: Arial; line-height: 20px;font-size: 14px;">
        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">
            Cláusula Primeira - Dos Objetos
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>1. 1</strong> Constitui objetos deste instrumento a locação de:
        </div>
        <table cellspacing="0" cellpadding="5px" border="solid" style="font-size:14px;">
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
            <br>
            <p><?=$locacao['acessorios']?></p>
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>1. 2</strong> O locatário não poderá, sem prévia autorização do locador, sublocar, emprestar, ou ceder o bem objeto da locação.
        </div>
        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">
            Cláusula Segunda – Obrigações do locador
        </div>
        <div style="display: block;margin: 20px 0;">
            Constituem Obrigações do locador:
            <br><br>
            <strong>2.1</strong> Disponibilizar ao locatário os equipamentos em perfeito estado de funcionamento, sendo testado e aceito pelo mesmo no prazo estabelecido neste contrato.
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>2.2</strong> Entregar o equipamento e seus acessórios no endereço estabelecido neste contrato, como combinado com o locatário anteriormente, pelos meios de comunicação utilizados durante a negociação.
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>2.3</strong> Efetuar a substituição dos equipamentos, caso apresentem problemas não ocasionados por mau uso, mediante disponibilidade de estoque, em até 24 horas.
        </div>

        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">
            Cláusula terceira – Obrigações do locatário
        </div>

        <div style="display: block;margin: 20px 0;">
            Constituem Obrigações do locador:
            <br><br>
            <strong>3.1</strong> O locatário se responsabiliza pelo uso e cuidados dos objetos durante o período em que estiver com os mesmos. Em caso de dano, depreciação por mau uso, perda/extravio, furto ou roubo à algum aparelho, o locatário se compromete a substituir em um prazo de 5 dias corridos, o aparelho danificado por um de mesma qualidade ou de qualidade superior. Também será possível restituir o valor total do bem à época do fato, considerando o valor de mercado, que será cobrado em fatura de cobrança lançada em favor do locatário.
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>3.2</strong> O locatário não poderá prestar por si só ou por intermédio de terceiros não credenciador, reparos ou consertos nos equipamentos. Quaisquer falhas no desempenho dos equipamentos deverão ser comunicadas pelo locatário, o mais rápido possível, ao locador.
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>3.3</strong> Em caso de dano parcial, queda, uso inadequado, o locatário reembolsará à locadora o valor equivalente aos reparos e demais despesas que se fizerem necessários em razão do ocorrido.
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>3.4</strong> A recusa da devolução do equipamento ou o dano nele produzido obriga o locatário, ao ressarcimento pelos danos e lucros cessantes, estes pelo período em que o equipamento deixar de ser utilizado pelo locador.
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>3.5</strong> O locatário não poderá alterar o local de utilização dos equipamentos, aqui qualificado no preâmbulo deste contrato, sem prévia autorização do locador.
        </div>

        <div style="text-align:center; font-weight: bold; margin-bottom: 15px;">
            Cláusula quarta – Vigência e pagamento
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>4.1</strong> O contrato poderá ser rescindido a qualquer época, por ambas as partes, desde que comunique a outra parte com antecedência de 24 horas.
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>4.2</strong> A liquidação do custo deverá ser efetuada no momento da entrega dos objetos.
        </div>
        <div style="display: block;margin: 20px 0;">
            <strong>4.3</strong> Pela locação, as partes ajustam o valor e a vigência contratual:
            <br>
            <b>Valor da Locação: R$ </b><?= $locacao['valor_total'] ?>
            <br>
            <b>Forma de pagamento: </b><?= $locacao['condicao'] == 1 ? 'A vista' : ' ' ?> , <?= $locacao['forma_pagamento'] ?>
            <br>
            <b>Data de retirada: </b><?= date('d/m/Y', strtotime($locacao['data_entrega'])) ?>
            <br>
            <b>Data de entrega: </b><?= date('d/m/Y', strtotime($locacao['data_devolucao'])) ?>
            <br>
            <br>
            <br>
            <br>
            <p>Por estarem assim justos e contratados, firmam o presente instrumento em duas vias de igual teor.</p>
        </div>

        <?php setlocale(LC_TIME, 'pt_BR.utf8');?>
        <div style="text-align: center;">
            Montes Claros, <?= strftime('%d de %B de %Y', strtotime('today')) ?>
        </div>
        <br>
        <br>
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
<div style="margin: 0;margin-top: 50px;color: #779EE5;font-family: calibri;font-size: 14px;font-weight: bold;text-align: center;">
    <p>PLAYCOM TECNOLOGIA LTDA</p>
    <p>Telefones: (38) 9147-7706 / (38) 3082-4909</p>
    <p>Rua Juca Macedo, 801 - CEP: 39401-044 – Bairro Funcionários – Montes Claros / MG</p>
    <p>www.playlocacoes.com.br / contato@playlocacoes.com.br</p>
</div>
<?php

use App\Models\LocacoesModel;

function getNotificacoes()
{
    $model = new LocacoesModel();
    return $model->getLocacoesProximasDeEntrega(1);
}

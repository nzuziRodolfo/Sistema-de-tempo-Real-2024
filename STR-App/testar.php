<?php

/* Preparação das Variáveis  */
$tarefas = [];
$num_tarefas = $_POST['numero_tarefas'];

if(isset($_POST['deadline'.(1)])){
    for($i = 0; $i < $num_tarefas; $i++){
        $tarefas[$i] = (object)array(
            'nome' => $_POST['tarefa'.($i+1)],
            'periodo' => $_POST['periodo'.($i+1)],
            'D' => $_POST['deadline'.($i+1)],
            'tempo_computacao' => $_POST['tempo_computacao'.($i+1)]
        );
    }
}else{
    for($i = 0; $i < $num_tarefas; $i++){

        $tarefas[$i] = (object)array(
            'nome' => $_POST['tarefa'.($i+1)],
            'periodo' => $_POST['periodo'.($i+1)],
            'tempo_computacao' => $_POST['tempo_computacao'.($i+1)]
        );
    }
}

 /**- ---------------------------------------------------------------------------------------- */

/** Funções de Teste do RM*/

function Teste_Menor_Majorante($n, $tarefas):String{
    $taxa_utilizacao = 0.0;

    /* Somatório da taxa de utilização */
    for($i = 0; $i < $n; $i++){ // n = Total de tarefas
        $taxa_utilizacao += ($tarefas[$i]->tempo_computacao/$tarefas[$i]->periodo);
    }

    if($taxa_utilizacao <= $n*(pow(2, 1/$n) - 1)){
        return "Tarefas_Escalonaveis";
    }
    else{
        return "Tarefas_Nao_Escalonaveis";
    }
}

/** ------------------------------------------------------------------------------------------------ */

/** Funções de Teste do DM*/

function Teste_DM($n, $tarefas):String{
    $taxa_utilizacao = 0.0;

    /* Somatório da taxa de utilização */
    for($i = 0; $i < $n; $i++){ // n = Total de tarefas
        $taxa_utilizacao += ($tarefas[$i]->tempo_computacao/$tarefas[$i]->D);
    }

    if($taxa_utilizacao <= $n*(pow(2, 1/$n) - 1)){
        return "Tarefas_Escalonaveis";
    }
    else{
        return "Tarefas_Nao_Escalonaveis";
    }
}

/** ------------------------------------------------------------------------------------------------ */

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo/bootstrap/css/bootstrap.min.css">
    <title>Teste</title>

    <style>
        body{
            background-color: #ddd;
        }
        div{
            width: 100%;
            display: flex;
            justify-content: center;
        }
        canvas{
            margin-top: 5px;
            border: 1px solid #999;
            background-color: white;
        }
        #diagrama{
            position: relative;
            left: 8px;
        }
        a{
            position: relative;
        }
        a#comportamento{
            left: 6px;
            color: white;
        }
        #btn_voltar{
            left: 50%;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <?php if(isset($tarefas[0]->D)):?>
        <?php if(Teste_DM($num_tarefas, $tarefas)=="Tarefas_Escalonaveis"): ?>
            <form>
                <?php
                    for($i = 0; $i < $num_tarefas; $i++):?> 
                        <input type="hidden" value=<?php echo $tarefas[$i]->nome; ?> id=<?php echo "tarefa".$i?> >
                        <input type="hidden" value=<?php echo $tarefas[$i]->periodo;?> id=<?php echo "periodo".$i?>> 
                        <input type="hidden" value=<?php echo $tarefas[$i]->D;?> id=<?php echo "D".$i?>> 
                        <input type="hidden" value=<?php echo $tarefas[$i]->tempo_computacao; ?> id=<?php echo "tempo".$i?>>
                <?php endfor; ?>
                
                <input type="hidden" value=<?php echo $num_tarefas; ?> id="num_tarefas">
                <a class="btn btn-secondary" id="comportamento">Ver comportamento</a>
                <button id="diagrama" class="btn btn-secondary">Ver Diagrama</button>
            </form>

            <div>
                <canvas id="canvas" width="1350" height="600"> </canvas>
            </div>

            <a href="index.php" id="btn_voltar">Voltar</a>
            <script src="DM.js"></script>

        <?php else: ?>
                <h3>Conjunto de tarefas não escalonáveis</h3>
                <a href="index.php">Voltar</a>
        <?php endif; ?>
    <?php else:  ?>
        <?php if(Teste_Menor_Majorante($num_tarefas, $tarefas)=="Tarefas_Escalonaveis"): ?>

            <form>
                <?php
                    for($i = 0; $i < $num_tarefas; $i++):?> 
                        <input type="hidden" value=<?php echo $tarefas[$i]->nome; ?> id=<?php echo "tarefa".$i?> > <br>
                        <input type="hidden" value=<?php echo $tarefas[$i]->periodo;?> id=<?php echo "periodo".$i?>> <br>
                        <input type="hidden" value=<?php echo $tarefas[$i]->tempo_computacao; ?> id=<?php echo "tempo".$i?>>
                <?php endfor; ?>
                
                <input type="hidden" value=<?php echo $num_tarefas; ?> id="num_tarefas">
                <a class="btn btn-secondary" id="comportamento">Ver comportamento</a>
                <button id="diagrama" class="btn btn-secondary">Ver Diagrama</button>
            </form>

            <div>
                <canvas id="canvas" width="1350" height="600"> </canvas>
            </div>
            
            <a href="index.php" id="btn_voltar">Voltar</a>
            <script src="RM.js"></script>

        <?php else: ?>
            <h3>Conjunto de tarefas não escalonáveis</h3>
            <a href="index.php">Voltar</a>
        <?php endif; ?>

    <?php endif; ?>
        
</body>
</html>
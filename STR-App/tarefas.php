
<?php
    $numero_tarefas = $_POST['total_tarefas'];
    $algoritmo = $_POST['algoritmo'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo/bootstrap/css/bootstrap.min.css">
    <title>Tarefas</title>
    <style>
        ::placeholder{
            font-size: 12pt;
        }
        div{
            width: 100%;
            display: flex;
            overflow: scroll;
            justify-content: center;
        }
        form{
            width: 50%;
            margin-top: 4em;
        }
        h2{
            position: fixed;
            width: 100%;
            text-align: center;
            background-color: blue;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: 16pt;
            color: white;
            padding: 10px;
        }
        span{
            font-size: 6pt;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        input{
            margin-top: 0px;
        }
    </style>
</head>
<body>
    <h2>Entre com as tarefas</h2>
    <div>
    <form action="testar.php" method="POST">

        <?php
            $j = 1;
            for($i = 0; $i < $numero_tarefas; $i++):?> 
                <?php echo $j; ?>ª<span class="blockquote"> tarefa</span><input type="text" name=<?php echo "tarefa".($i+1);?> placeholder="Nome da tarefa" class="form-control" required>
                <span class="blockquote">Periódo</span> <input type="text" name=<?php echo "periodo".($i+1);?> placeholder="Entre um número natural" class="form-control" required>
                <?php if($algoritmo === "DM"):?>
                    <span class="blockquote">Deadline</span><?php echo $j; ?> <input type="text" name=<?php echo "deadline".($i+1);?> placeholder="Deadline" class="form-control" required> 
                <?php endif; ?>
                <span class="blockquote">c</span><?php echo $j; ?> <input type="text" name=<?php echo "tempo_computacao".($i+1);?> placeholder="Tempo de execução" class="form-control" required>
                <?php $j++; ?>
                <input type="hidden" name="numero_tarefas" value=<?php echo $numero_tarefas; ?>>
                <br>
        <?php endfor; ?>

        <input type="submit" value="Testar Tarefas" class="btn btn-secondary mb-2">


    </form>
    </div>
    
</body>
</html>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo/bootstrap/css/bootstrap.min.css">
    <title>Algoritmos</title>
    <style>
        ::placeholder{
            font-size: 12pt;
        }
        div{
            width: 100%;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    
    <div>
    <form action="tarefas.php" method="post" class="form-group">
        <p class="blockquote">Número de tarefas <input type="number" name="total_tarefas" placeholder="Digite um número" class="form-control" required></p> 
        <p class="blockquote">Algoritmo <select name="algoritmo" id="" class="custom-select">
            <option value="RM" selected>Rate Monotonic</option>
            <option value="DM">Deadline Monotonic</option>
        </select></p>
        <input type="submit" value="Avançar" target="blank" class="btn btn-secondary">
    </form>
    </div>
</body>
</html>
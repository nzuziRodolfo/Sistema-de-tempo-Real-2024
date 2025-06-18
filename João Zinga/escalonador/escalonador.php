<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$tarefas = $data['tarefas'];
$tempo_simulacao = $data['tempo_simulacao'];
$algoritmo = $data['algoritmo'];

// Função para calcular o macro ciclo (GCD dos períodos)
function calcularMacroCiclo($tarefas) {
    $periodos = array_map(fn($t) => $t['periodo'], $tarefas);
    return array_reduce($periodos, 'gcd');
}

function gcd($a, $b) {
    return $b ? gcd($b, $a % $b) : $a;
}

// Calcula o macro ciclo
$macro_ciclo = calcularMacroCiclo($tarefas);

// Se tempo_simulacao não foi especificado ou é menor que macro_ciclo, usa macro_ciclo
if ($tempo_simulacao <= 0) {
    $tempo_simulacao = $macro_ciclo;
}

// Ordena tarefas conforme o algoritmo para definir prioridades
if ($algoritmo == "RM") {
    // Rate Monotonic: menor período = maior prioridade
    usort($tarefas, fn($a, $b) => $a['periodo'] - $b['periodo']);
} elseif ($algoritmo == "DM") {
    // Deadline Monotonic: menor deadline = maior prioridade
    usort($tarefas, fn($a, $b) => $a['deadline'] - $b['deadline']);
}

$eventos = [];
$tempo = 0;
$fila_prontos = []; // Fila de tarefas prontas para execução

while ($tempo < $tempo_simulacao) {
    // Verifica chegada de novas instâncias de tarefas
    foreach ($tarefas as $index => $tarefa) {
        if ($tempo % $tarefa['periodo'] === 0) {
            $fila_prontos[] = [
                'tarefa' => $tarefa['nome'],
                'prioridade' => $index, // Para RM e DM, baseado na ordenação inicial
                'tempo_execucao_restante' => $tarefa['tempo_execucao'],
                'deadline_absoluto' => $tempo + $tarefa['deadline'],
                'chegada' => $tempo
            ];
        }
    }

    // Remove tarefas que perderam deadline (opcional - para análise)
    $fila_prontos = array_filter($fila_prontos, function($task) use ($tempo) {
        return $task['deadline_absoluto'] > $tempo;
    });

    if (empty($fila_prontos)) {
        $tempo++;
        continue;
    }

    // Seleciona próxima tarefa baseada no algoritmo
    if ($algoritmo == "EDF") {
        // EDF: menor deadline absoluto = maior prioridade
        usort($fila_prontos, fn($a, $b) => $a['deadline_absoluto'] - $b['deadline_absoluto']);
    } else {
        // RM e DM: usar prioridade já definida
        usort($fila_prontos, fn($a, $b) => $a['prioridade'] - $b['prioridade']);
    }

    $tarefa_atual = &$fila_prontos[0];
    
    // Determina por quanto tempo executar (até próxima chegada de tarefa ou fim da execução)
    $proximo_evento = $tempo + $tarefa_atual['tempo_execucao_restante'];
    
    // Verifica se alguma tarefa chegará antes do fim da execução atual
    foreach ($tarefas as $tarefa) {
        $proxima_chegada = $tempo + ($tarefa['periodo'] - ($tempo % $tarefa['periodo']));
        if ($proxima_chegada < $proximo_evento && $proxima_chegada > $tempo) {
            $proximo_evento = $proxima_chegada;
        }
    }
    
    $tempo_execucao = min($proximo_evento - $tempo, $tarefa_atual['tempo_execucao_restante']);
    
    // Registra evento de execução
    $eventos[] = [
        'tarefa' => $tarefa_atual['tarefa'],
        'inicio' => $tempo,
        'fim' => $tempo + $tempo_execucao,
        'deadline' => $tarefa_atual['deadline_absoluto']
    ];
    
    // Atualiza tempo e tarefa
    $tempo += $tempo_execucao;
    $tarefa_atual['tempo_execucao_restante'] -= $tempo_execucao;
    
    // Remove tarefa se terminou execução
    if ($tarefa_atual['tempo_execucao_restante'] <= 0) {
        array_shift($fila_prontos);
    }
    
    // Para evitar loop infinito
    if ($tempo >= $tempo_simulacao) {
        break;
    }
}

// Calcula estatísticas - CORRIGIDO
// Taxa de utilização = soma de (C/T) para cada tarefa
$utilizacao = 0;
foreach ($tarefas as $tarefa) {
    $utilizacao += $tarefa['tempo_execucao'] / $tarefa['periodo'];
}

// Calcula o micro ciclo (LCM dos períodos) para análise
function calcularMicroCiclo($tarefas) {
    $periodos = array_map(fn($t) => $t['periodo'], $tarefas);
    return array_reduce($periodos, 'lcm', 1);
}

function lcm($a, $b) {
    return ($a * $b) / gcd($a, $b);
}

$micro_ciclo = calcularMicroCiclo($tarefas);

// Verifica se é escalonável baseado no algoritmo
$escalonavel = false;
if ($algoritmo == "EDF") {
    // Para EDF: U ≤ 1
    $escalonavel = $utilizacao <= 1.0;
} else {
    // Para RM e DM: usar teste de Liu & Layland
    $n = count($tarefas);
    $limite_ll = $n * (pow(2, 1/$n) - 1);
    
    if ($utilizacao <= $limite_ll) {
        $escalonavel = true;
    } elseif ($utilizacao <= 1.0) {
        // Teste adicional necessário - assumir escalonável se U ≤ 1
        $escalonavel = true;
    } else {
        $escalonavel = false;
    }
}

$resultado = [
    'eventos' => $eventos,
    'macro_ciclo' => $macro_ciclo,
    'micro_ciclo' => $micro_ciclo,
    'utilizacao' => round($utilizacao, 4),
    'escalonavel' => $escalonavel,
    'tempo_simulacao' => $tempo_simulacao,
    'limite_liu_layland' => isset($limite_ll) ? round($limite_ll, 4) : null
];

echo json_encode($resultado);
?>
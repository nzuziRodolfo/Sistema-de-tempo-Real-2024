var num_tarefas = document.getElementById('num_tarefas').value;
var lista_tarefas = [];

  for (let i = 0; i <num_tarefas; i++) {
    lista_tarefas[i] = {
        'nome': document.getElementById('tarefa'+i).value,
        'periodo': Number(document.getElementById('periodo'+i).value),
        'ci': Number(document.getElementById('tempo'+i).value),
        'deadline':Number(document.getElementById('D'+i).value),
        'prioridade':1000
    }
}

var periodos = [];
for (let i = 0; i < lista_tarefas.length; i++) {
    periodos.push(lista_tarefas[i].periodo);
}

var macro_ciclo = MMC(periodos);

var tarefas = prioridades(lista_tarefas);

let p = 1;
    for(let i = 0; i < tarefas.length; i++){
        tarefas[i].prioridade = p;
        p++;
    }
  
  // Função para executar todas as tarefas
  function executeAlltarefas() {
    var tempo_atual = 0;
    var total_tempo_execucao = macro_ciclo; // Tempo total de execução
  
    while (tempo_atual < total_tempo_execucao) {
      var tarefa_prioritaria = null;
  
      // Percorre todas as tarefas
      for (var i = 0; i < tarefas.length; i++) {
        var tarefa = tarefas[i];
  
        // Verifica se é hora de executar a tarefa e se é a tarefa de maior prioridade
        if (tempo_atual % tarefa.periodo === 0 && (!tarefa_prioritaria || tarefa.prioridade < tarefa_prioritaria.prioridade)) {
          tarefa_prioritaria = tarefa;
        }
      }
  
      if (tarefa_prioritaria) {
        console.log('Executando', tarefa_prioritaria.nome, 'no tempo', tempo_atual);
        // Aqui você pode adicionar a lógica de execução da tarefa
  
        tarefa_prioritaria.deadline -= tarefa_prioritaria.periodo;
  
        // Verifica se a tarefa não conseguiu ser concluída dentro do prazo
        if (tarefa_prioritaria.deadline < 0) {
          // console.log('A tarefa', tarefa_prioritaria.nome, 'não foi concluída dentro do prazo.');
        }
      }
  
      tempo_atual++;
    }

    console.log("Todas as tarefas foram executadas")
  }


var btn_comportamento = document.getElementById('comportamento');
var btn_diagrama = document.getElementById('diagrama');

btn_comportamento.addEventListener('click', function(evt){
    evt.preventDefault();

    alert("Nota: Comportamento só é visualizado no console do javascript")
    // Inicia o escalonamento
    executeAlltarefas();
})


btn_diagrama.addEventListener('click', function(evt){
    evt.preventDefault();
    alert("Diagrama só disponível no algoritmo Rate Monotonic!")
})

  
function prioridades(lista){
    for(i = 0; i < lista.length - 1; i++){
        for(j = i + 1; j < lista.length; j++){
            if(lista[i].periodo < lista[j].periodo){
                aux = lista[i];
                lista[i] = lista[j];
                lista[j] = aux;
            }
        }
    }
    return lista;
}
  

function MMC(numbers) {
    var result = 1;
    for (var i = 0; i < numbers.length; i++) {
        result = lcm(result, numbers[i]);
    }
    return result;
}

function gcd(a, b) {
    while (b !== 0) {
        var temp = b;
        b = a % b;
        a = temp;
    }
    return a;
}

function lcm(a, b) {
    return (a * b) / gcd(a, b);
}

var lista_tarefas = []; // Pega todas as tarefas 
var num_tarefas = document.getElementById('num_tarefas').value;

var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');

var pos = 0;
var cores = ['black', 'red', 'blue', 'yellow', 'grey', 'green', 'orange'];

for (let i = 0; i <num_tarefas; i++) {
    lista_tarefas[i] = {
        'nome': document.getElementById('tarefa'+i).value,
        'periodo': Number(document.getElementById('periodo'+i).value),
        'ci': Number(document.getElementById('tempo'+i).value),
        'cor':cores[i],
        'ativacoes':[],
        'posicao':pos,
        'prioridade':1000
    }
    pos += 35;
}

/* periodos - usados para pegar o Macro e Micro ciclo */
var periodos = [];
for (let i = 0; i < lista_tarefas.length; i++) {
    periodos.push(lista_tarefas[i].periodo);
} 
var macro_ciclo = MMC(periodos);
var micro_ciclo = MDC(periodos);


/* Controlo do ambiente do canvas */
if(macro_ciclo > 1350){
    canvas.width = macro_ciclo;
    canvas.height = 10*70;
    alert("Nota: periodos muito grande como esses que colocaste vai comprometer a visualização correta das tarefas. Obrigado.")
}


/* Controlo do corpo das tarefas */
var incremento;
if(macro_ciclo < 50){
    incremento = 40;
}
else if(macro_ciclo >= 50 && macro_ciclo <= 100){
    incremento = 20;
}
else{
    incremento = 5;
}


/* Activações das tarefas */
for (let i = 0; i <num_tarefas; i++) {
    let n = 0;
    for(let j = 0; j < macro_ciclo; j = n*lista_tarefas[i].periodo){
        lista_tarefas[i].ativacoes[n] =
        {
            'valor':Number(n*lista_tarefas[i].periodo),
            'ci':Number(lista_tarefas[i].ci),
            'deadline': (n*lista_tarefas[i].periodo + lista_tarefas[i].periodo),
        } 
        n++;
    }
} 

var tarefas_restantes = [];

var btn_comportamento = document.getElementById('comportamento');
var btn_diagrama = document.getElementById('diagrama');

btn_comportamento.addEventListener('click', function(evt){
    evt.preventDefault();

    alert("Nota: Comportamento só é visualizado no console do javascript")

    // Inicia o escalonamento
    programacao_Tarefa();

})

var tempoDecorrido = 0;
var tempoMaximo = macro_ciclo*1000;
var tempoAtual = 0;
var timeInterval = 1; // Intervalo de tempo em que o escalonamento é feito (em unidades de tempo)


// Função para escalonar as tarefas
function programacao_Tarefa() {

    tarefas = prioridades(lista_tarefas);

    let p = 1;
    for(let i = 0; i < tarefas.length; i++){
        tarefas[i].prioridade = p;
        p++;
    }

    var tarefa_atual = null;

    // Encontra a tarefa com maior prioridade que está pronta para ser executada
    for (var i = 0; i < tarefas.length; i++) {
        var task = tarefas[i];

        // Verifica se a tarefa está pronta para ser executada no tempo atual
        if (tempoAtual % task.periodo === 0) {
            if (!tarefa_atual || task.prioridade < tarefa_atual.prioridade) {
                tarefa_atual = task;
            }
        }
    }

    // Executa a tarefa agendada (se houver)
    if (tarefa_atual) {
        console.log("Executando Tarefa", tarefa_atual.nome + " no instante de ativação: " + tempoAtual);
        tarefa_atual.ci--;

        // Verifica se a tarefa foi concluída
        if (tarefa_atual.ci === 0) {
            console.log("Tarefa", tarefa_atual.nome, "concluída.");
            tarefa_atual.ci = tarefa_atual.ci; // Reinicia o tempo de execução da tarefa
        }
    } else {
        console.log("Nenhuma tarefa agendada no tempo", tempoAtual);
    }

    tempoAtual += timeInterval;

    if (tempoDecorrido >= tempoMaximo) {
        console.log("Todas as tarefas foram executadas");
        return;
      }

    // Chama a função novamente após o intervalo de tempo
    setTimeout(programacao_Tarefa, timeInterval * 1000); // Multiplica por 1000 para converter para milissegundos
    tempoDecorrido += 1000;
}

var tarefas = [];
btn_diagrama.addEventListener('click', function(evt){
    evt.preventDefault();
    
    tarefas = prioridades(lista_tarefas);
    grafico();

});

function grafico(){

    /* Desenhe as instancias das tarefas com maior prioridade com base as suas ativações */
    for(let i = 0; i < tarefas[0].ativacoes.length; i++){
        let tarefa = tarefas[0].ativacoes[i];

        let a = tarefas[0].ativacoes[i].valor;
        desenhaTarefa(a*incremento, tarefas[0].posicao, tarefas[0].ci*incremento, 30, tarefas[0].cor);
    }

    /* Desenhando outras tarefa */
    for(let i = 1; i < num_tarefas; i++){
        /* Para cada tarefa, acessar as suas ativacoes e preencher */
        for (let k = 0; k < tarefas[i].ativacoes.length; k++) {
            let a = tarefas[i].ativacoes[k].valor;
            desenhaTarefa(a*incremento, tarefas[i].posicao, tarefas[i].ci*incremento, 30, tarefas[i].cor);
            a += tarefas[i].periodo;
        }
            
    }

    desenhaTexto("NÃO CONSEGUI APLICAR O CONCEITO DE PRIORIDADES DAS TAREFAS - PRIORIDADE É MODO HARD", canvas.width/2-400, 250, 'red'); 
    
    var x = canvas.width/2;  
    var pos = 280;  
    // var h = 500;
    for(let i = 0; i < tarefas.length; i++){
        let mgs = tarefas[i].nome;
        desenhaTexto(mgs, canvas.width/2-400, pos-2, 'red');
        desenhaTarefa(canvas.width/2-400, pos, tarefas[i].ci*incremento, 30, tarefas[i].cor);
        pos += 60;
        // h += 20;
    }
    
}

function desenhaTarefa(x, posicao, ci, y, cor){
    context.fillStyle = cor;
    context.fillRect(x, posicao, ci, y);
}

function desenhaTexto(mgs, x, y, cor){
    context.fillStyle = cor;
    context.font = '15px sans-serif';
    context.fillText(mgs, x, y);
}

function prioridades(lista){
    for(i = 0; i < lista.length - 1; i++){
        for(j = i + 1; j < lista.length; j++){
            if(lista[i].periodo > lista[j].periodo){
                aux = lista[i];
                lista[i] = lista[j];
                lista[j] = aux;
            }
        }
    }
    return lista;
}

// Função para calcular o MMC de dois números usando o MDC
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

// Função para calcular o MDC de vários números
function MDC(numbers) {
    var result = numbers[0];
    for (var i = 1; i < numbers.length; i++) {
        result = gcd(result, numbers[i]);
    }
    return result;
}
 
 const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    function mmc(a, b) {
      return (a * b) / mdc(a, b);
    }

    function mdc(a, b) {
      return b === 0 ? a : mdc(b, a % b);
    }

    function calcularMacroCiclo(tarefas) {
      const periodos = tarefas.map(t => Number(t.period || 1));
      return periodos.reduce((acc, p) => mmc(acc, p), 1);
    }

    function gerarInstancias(tarefas, macroCiclo) {
      const instancias = [];

      [...tarefas].reverse().forEach((tarefa) => {
        const periodo = Number(tarefa.period || macroCiclo);
        const execTime = Number(tarefa.execTime);

        for (let tempo = 0; tempo < macroCiclo; tempo += periodo) {
          const deadline = tempo + periodo;
          if (deadline <= macroCiclo) {
            instancias.push({
              name: tarefa.name,
              execTime: execTime,
              remaining: execTime,
              releaseTime: tempo,
              deadline: deadline,
              priority: periodo
            });
          }
        }
      });

      return instancias;
    }

    function escalonarPorDM(instancias, macroCiclo) {
      const timeline = [];
      const fila = [];

      for (let tempo = 0; tempo < macroCiclo; tempo++) {
        instancias.forEach(inst => {
          if (inst.releaseTime === tempo) fila.push({ ...inst });
        });

        for (let i = fila.length - 1; i >= 0; i--) {
          if (fila[i].remaining <= 0) fila.splice(i, 1);
        }

        fila.sort((a, b) => a.priority - b.priority);

        if (fila.length > 0) {
          fila[0].remaining--;
          timeline.push({ time: tempo, name: fila[0].name, deadline: fila[0].deadline });
        } else {
          timeline.push({ time: tempo, name: "idle" });
        }
      }

      return timeline;
    }

    const coresTarefa = {};
    const coresDisponiveis = ['#1abc9c', '#e74c3c', '#3498db', '#9b59b6', '#f1c40f', '#e67e22', '#2ecc71', '#34495e'];
    let corIndex = 0;

    function corDaTarefa(nome) {
      if (!coresTarefa[nome]) {
        coresTarefa[nome] = coresDisponiveis[corIndex % coresDisponiveis.length];
        corIndex++;
      }
      return coresTarefa[nome];
    }

    function drawTasks(tasks) {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      const macroCiclo = calcularMacroCiclo(tasks);
      const instancias = gerarInstancias(tasks, macroCiclo);
      const escalonado = escalonarPorDM(instancias, macroCiclo);

      for (let t = 0; t <= macroCiclo; t++) {
        const x = t * 10;
        ctx.fillStyle = '#000';
        ctx.fillText(t, x, 10);
        ctx.beginPath();
        ctx.moveTo(x, 15);
        ctx.lineTo(x, 100);
        ctx.strokeStyle = '#ccc';
        ctx.stroke();
      }

      escalonado.forEach((exec) => {
        const x = exec.time * 10;
        const y = 30;

        if (exec.name === "idle") {
          ctx.fillStyle = '#bdc3c7';
        } else {
          ctx.fillStyle = corDaTarefa(exec.name);
        }

        ctx.fillRect(x, y, 10, 30);
        ctx.fillStyle = '#fff';
        ctx.fillText(exec.name[0], x + 2, y + 20);
      });

      console.log("Macro ciclo:", macroCiclo);
    }

    function start() {
      const input = document.getElementById("tarefas").value;
      const tarefas = JSON.parse(input);
      drawTasks(tarefas);
    }


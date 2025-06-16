using System.Xml.Linq;

namespace EscalonamentoEDF_RM_DM
{
    public class Tarefa
    {
        public int Id { get; set; }
        public string Nome { get; set; }
        public int Periodo { get; set; }          
        public int TempoExecucao { get; set; }    
        public int Deadline { get; set; }         
        public int DeadlineAbsoluto { get; set; } 
        public int TempoRestante { get; set; }    
        public int ProximaLiberacao { get; set; } 

        public Tarefa(int id, string name, int periodo, int tempoExecucao, int? deadline = null)
        {
            Id = id;
            Nome = name;
            Periodo = periodo;
            this.TempoExecucao = tempoExecucao;
            Deadline = deadline ?? periodo; // Se deadline não for especificado, usa o período
            TempoRestante = tempoExecucao;
            ProximaLiberacao = 0;
            DeadlineAbsoluto = Deadline;
        }

        public void Reset()
        {
            TempoRestante = TempoExecucao;
            DeadlineAbsoluto = ProximaLiberacao + Deadline;
        }
    }

}
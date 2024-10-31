<?php
require_once '../models/Frequencia.php';

class FrequenciaController {
    private $frequenciaModel;

    public function __construct() {
        $this->frequenciaModel = new Frequencia();
    }

    public function saveFrequencia($alunoId, $turmaId, $dataChamada, $presenca) {
        return $this->frequenciaModel->saveFrequencia($alunoId, $turmaId, $dataChamada, $presenca);
    }
    public function getFrequencias($turmaId, $dataChamada) {
        return $this->frequenciaModel->getFrequenciasByTurmaAndData($turmaId, $dataChamada);
    }
}
?>

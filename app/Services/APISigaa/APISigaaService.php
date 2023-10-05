<?php

namespace App\Services\APISigaa;

use stdClass;

/**
 * Classe responsável em fazer as requisições da API do Sigaa.
 */
class APISigaaService
{

    private $baseUrlAuth = "https://autenticacao.ufrn.br/";
    private $baseUrl = "https://api.ufrn.br/";
    private $clientId = "portal-disciplinas-id-m3brevIfR75Tudlw";
    private $clientSecret = "t3etHuCHo4a4r7N8histunaPEdrIb3pH";
    private $apiKey = "mX5ZV9wY8p1RahQkZ8viPwOB9XlBVxGt";
    private $grantType = "client_credentials";
    //private $token = "2de62b66-6901-47f9-ace3-323a2a72a9d9";
    private $tokenData = null;
    private $qtdNewTokens = 0;

    /**
     * Função auxiliar para fazer as requisições para a API
     * @param string $url Url que será concatenada com a url base para obter o recurso.
     * @method string $method Métodos da requisição.
     * @return array
     */
    private function fetch($url, $method)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseUrl . $url,
            CURLOPT_ENCODING => '',
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            // CURLOPT_MAXREDIRS => 10,
            //CURLOPT_TIMEOUT => 5,
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $this->tokenData['token_type'] . " " . $this->tokenData['access_token'],
                "x-api-key: " . $this->apiKey
            )

        ));

        $response = curl_exec($curl);
        $errors = curl_error($curl);
        curl_close($curl);
        if ($errors) {
            dd("Contem erros: " . $errors);
        } else {
            $decodedJson = json_decode($response, true);
            return $decodedJson;
        }
    }

    /**
     * Obtém um token
     *
     */
    private function getToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseUrlAuth . "authz-server/oauth/token?client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret . "&grant_type=" . $this->grantType,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => ''
        ));

        $response = curl_exec($curl);
        $errors = curl_error($curl);
        if ($errors) {
            dd("Contem errors . " . $errors);
        } else {
            $this->tokenData = json_decode($response, true);
            $this->qtdNewTokens++;
        }
        curl_close($curl);
    }

    /**
     * Obtem todas as turmas de um determinado discente.
     * @param string idDiscente Identificador único do discente.
     * @return array
     */
    public function getTurmasDiscente($idDiscente)
    {

        $this->getToken();
        $turma = $this->fetch("turma/v1/boletim?id-discente=" . $idDiscente, "GET");
        return $turma;
    }

    /**
     * Obtem as turmas consolidadas pelo código do componente.
     * @param string $codigoComponente Código do componente.
     * @param int $ano Ano em que a turma foi aberta.
     * @param int $periodo Periodo em que a turma foi aberta.
     * @return array
     */
    public function getTurmasPorComponente($codigoComponente, $ano, $periodo)
    {

        $strAno = "";
        $strPeriodo = "";
        if ($ano != null) {
            $strAno = "&ano=" . $ano;
        }

        if ($periodo != null) {
            $strPeriodo = "&periodo=" . $periodo;
        }

        if ($this->tokenData == null) {
            $this->getToken();
        }
        $turmas = $this->fetch("turma/v1/turmas?codigo-componente=" . $codigoComponente . "&id-situacao-turma=3" . $strAno . $strPeriodo, "GET");
        return $turmas;
    }

    public function getDisciplineData($codigoComponente, $idTurma, $ano, $periodo)
    {
        $tempoInicio = microtime(true);
        ini_set('max_execution_time', 3600);
        $strAno = "";
        $strPeriodo = "";
        if ($ano != null) {
            $strAno = "&ano=" . $ano;
        }
        if ($strPeriodo == "") {
            $strPeriodo = "&periodo=" . $periodo;
        }

        $lotacaoTurma = [];
        $idsTurma = [];
        $docentes = [];
        if ($this->tokenData == null) {
            $this->getToken();
        }
        $qtdTurmas = 0;
        $qtdAlunos = 0;
        $qtdAlunosComMedia = 0;
        $somaMedias = 0;
        $maiorMedia=0;
        $menorMedia=10;
        $qtdAprovados = 0;
        $qtdReprovados = 0;
        $qtdDesconhecidos = 0;
        $qtdOutros = 0;
        $situacaoDesconhecida = "";
        $turmas = $this->fetch("turma/v1/turmas?codigo-componente=" . $codigoComponente . "&id-situacao-turma=3" . $strAno . "" . $strPeriodo, "GET");
        $alunosTurma = [];
        foreach ($turmas as $turma) {
            if ($idTurma == null || $turma['id-turma'] == $idTurma) {
                $qtdTurmas++;
                array_push($idsTurma, $turma['id-turma']);
                $fetchDocentes = $this->fetch("turma/v1/turmas/" . $turma['id-turma'] . "/docentes","GET");
                $docentesTurma = [];
                foreach($fetchDocentes as $docente){
                    $docenteClass= new stdClass();
                    if($docente['nome-docente'])
                    {
                        $docenteClass->nome = $docente['nome-docente'];
                    }else{
                        $docenteClass->nome = "sem professor";
                    }
                    //$docenteClass->nome = $docente['nome-docente'];
                    
                    array_push($docentesTurma, json_encode($docenteClass));
                }
                array_push($docentes, $docentesTurma);
                

                $limit = 100;
                $alunosTurmaFetch = $this->fetch("turma/v1/participantes?id-turma=" . $turma['id-turma'] . "&id-tipo-participante=4&limit=" . $limit, "GET");
                if (count($alunosTurmaFetch) == $limit) {
                    $alunosTurmaFetch2 = $this->fetch("turma/v1/participantes?id-turma=" . $turma['id-turma'] . "&id-tipo-participante=4&limit=100&offset=" . $limit, "GET");
                    $alunosTurmaFetch = array_merge($alunosTurmaFetch, $alunosTurmaFetch2);
                }
                array_push($lotacaoTurma, count($alunosTurmaFetch));
                
                $alunosTurma = array_merge($alunosTurma, $alunosTurmaFetch);
               
                if ($idTurma != null) {
                    break;
                }
            }
        }
        $qtdAlunosTurma = 0;
        foreach ($alunosTurma as $aluno) {
            $qtdAlunos++;
            $qtdAlunosTurma++;
            $boletinsAluno = $this->fetch("turma/v1/boletim?id-discente=" . $aluno['id-participante'] . "&ano=" . $ano . "&periodo=" . $periodo, "GET");
            $encontrouBoletim = false;

            foreach ($boletinsAluno as $boletimAluno) {
                if ($boletimAluno["id-turma"] == $aluno["id-turma"]) {
                    $encontrouBoletim = true;
                    $qtdAlunosComMedia++;
                    $somaMedias += $boletimAluno["media-final"];
                    $menorMedia = min($menorMedia, $boletimAluno["media-final"]);
                    $maiorMedia = max($maiorMedia, $boletimAluno["media-final"]);
                    if ($boletimAluno["situacao"] == "APROVADO" || $boletimAluno["situacao"] == "APROVADO POR NOTA") {
                        $qtdAprovados++;
                    } else if (
                        $boletimAluno["situacao"] == "REPROVADO"
                        || $boletimAluno["situacao"] == "REPROVADO POR FALTAS"
                        || $boletimAluno["situacao"] == "REPROVADO POR MÉDIA E POR FALTAS"
                    ) {
                        $qtdReprovados++;
                    } else {
                        $qtdOutros++;
                    }

                    break;
                }
            }

            if (!$encontrouBoletim) {
                $qtdDesconhecidos++;
                $situacaoDesconhecida .= $aluno["id-participante"] . " ";
            }
        }
        
        $dados = null;

        if ($qtdAlunos == 0) {
            return null;
        } else {
            $dados = array(
                "soma-medias" => $somaMedias,
                "maior-media" => $maiorMedia,
                "menor-media" => $menorMedia,
                "quantidade-discentes" => $qtdAlunos,
                "quantidade-aprovados" => $qtdAprovados,
                "quantidade-reprovados" => $qtdReprovados,
                "diferente-aprov-reprov" => $qtdOutros,
                "situacao-desconhecida" => $situacaoDesconhecida,
                "tempo-total" => (microtime(true) - $tempoInicio),
                "quantidade-turmas" => $qtdTurmas,
                "ids-turma" => $idsTurma,
                "lotacoes-turma" => $lotacaoTurma,
                "docentes" => json_encode($docentes)
            );
        }
        return $dados;
    }
}

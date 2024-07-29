<?php

namespace App\Services\APISigaa;

use App\Exceptions\APISistemasIncorrectRequestExceception;
use App\Exceptions\APISistemasRequestLimitException;
use App\Exceptions\APISistemasServerErrorException;
use App\Exceptions\APISistemasUnavailableException;
use App\Exceptions\ApiUnknownValueException;
use Exception;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use stdClass;

/**
 * Classe responsável em fazer as requisições da API do Sigaa.
 */
class APISigaaService
{
    public $baseUrlAuth;
    private $baseUrl;
    private $clientId;
    private $clientSecret;
    private $apiKey;
    private $grantType;
    private $tokenData;
    private $qtdNewTokens;

    public function __construct()
    {
        $this->baseUrlAuth = env('API_SISTEMAS_BASE_URL_AUTH');
        $this->baseUrl = env('API_SISTEMAS_BASE_URL');
        $this->clientId = env('API_SISTEMAS_CLIENT_ID');
        $this->clientSecret = env('API_SISTEMAS_CLIENT_SECRET');
        $this->apiKey = env('API_SISTEMAS_API_KEY');
        $this->grantType = env('API_SISTEMAS_GRANT_TYPE');
        $this->tokenData = null;
        $this->qtdNewTokens = 0;
    }

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
        $statusCode = curl_getinfo($curl)['http_code'];

        if ($statusCode == 429) {
            throw new APISistemasRequestLimitException();
        } else if ($statusCode >= 400 && $statusCode < 500) {
            throw new APISistemasIncorrectRequestExceception();
        } else if ($statusCode >= 500 && $statusCode < 600) {
            throw new APISistemasServerErrorException();
        }
        $errors = curl_error($curl);
        curl_close($curl);
        if ($errors) {
            throw new APISistemasUnavailableException();
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
        $statusCode = curl_getinfo($curl)['http_code'];
        if ($statusCode == 429) {
            throw new APISistemasRequestLimitException();
        } else if ($statusCode >= 400 && $statusCode < 500) {
            throw new APISistemasIncorrectRequestExceception();
        } else if ($statusCode >= 500 && $statusCode < 600) {
            throw new APISistemasServerErrorException();
        }
        $errors = curl_error($curl);
        if ($errors) {
            throw new APISistemasUnavailableException();
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
        $turmas = $this->fetch("turma/v1/turmas?codigo-componente=" . $codigoComponente . "&id-situacao-turma=3" . $strAno . $strPeriodo . "&sigla-nivel=G", "GET");
        return $turmas;
    }

    public function getDisciplineData($codigoComponente, $idTurma, $ano, $periodo)
    {
        $tempoInicio = microtime(true);
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
        $somaMediasUnidade1 = 0;
        $somaMediasUnidade2 = 0;
        $somaMediasUnidade3 = 0;
        $unidade1ComNota = true;
        $unidade2ComNota = true;
        $unidade3ComNota = true;
        $maiorMedia = 0;
        $menorMedia = 10;
        $qtdAprovados = 0;
        $qtdReprovados = 0;
        $qtdDesconhecidos = 0;
        $situacaoDesconhecida = "";
        $turmas = $this->fetch("turma/v1/turmas?codigo-componente=" . $codigoComponente . "&id-situacao-turma=3" . $strAno . "" . $strPeriodo . "&sigla-nivel=G", "GET");
        $alunosTurma = [];
        foreach ($turmas as $turma) {
            if ($idTurma == null || $turma['id-turma'] == $idTurma) {
                $qtdTurmas++;
                array_push($idsTurma, $turma['id-turma']);
                $fetchDocentes = $this->fetch("turma/v1/turmas/" . $turma['id-turma'] . "/docentes", "GET");
                $docentesTurma = [];
                foreach ($fetchDocentes as $docente) {
                    $docenteClass = "SEM NOME";
                    if ($docente['nome-docente']) {
                        $docenteClass = $docente['nome-docente'];
                    } else if ($docente["id-docente-externo"]) {
                        $docenteClass = "DOCENTE EXTERNO";
                        $docenteExterno = $this->fetch("/docente-externo/v1/docentes-externos/" . $docente['id-docente-externo'], "GET");
                        if ($docenteExterno['nome']) {
                            $docenteClass = $docenteExterno['nome'];
                        }
                    }

                    array_push($docentesTurma, $docenteClass);
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
                    foreach($boletimAluno["notas-unidades"] as $nota){
                        if($nota["unidade"] == 1){
                            if(!is_null($nota["media"])){
                                $somaMediasUnidade1 += $nota["media"];
                            }
                            else{
                                $unidade1ComNota = false;
                                //Log::info('U1 -> ' . 'id-turma: ' . $boletimAluno['id-turma'] . ' ano: ' . $boletimAluno['ano'] . ' perido: ' . $boletimAluno['periodo'] . ' id-aluno: ' . $aluno['id-participante']);
                            }
                        }elseif($nota["unidade"] == 2){
                            if(!is_null($nota["media"])){
                                $somaMediasUnidade2 += $nota["media"];
                            }
                            else{
                                $unidade2ComNota = false;
                                
                                //Log::info('U2 -> ' . 'id-turma: ' . $boletimAluno['id-turma'] . ' ano: ' . $boletimAluno['ano'] . ' perido: ' . $boletimAluno['periodo'] . ' id-aluno: ' . $aluno['id-participante']);
                            }
                        }elseif($nota["unidade"] == 3){
                            if(!is_null($nota["media"])){
                                $somaMediasUnidade3 += $nota["media"];
                            }
                            else{
                                $unidade3ComNota = false;
                                //Log::info('U3 -> ' . 'id-turma: ' . $boletimAluno['id-turma'] . ' ano: ' . $boletimAluno['ano'] . ' perido: ' . $boletimAluno['periodo'] . ' id-aluno: ' . $aluno['id-participante']);
                            }
                        }else{
                            Log::warning("Unidade desconhecida: " . " unidade: " . $nota["unidade"]);
                        }
                    }
                    $menorMedia = min($menorMedia, $boletimAluno["media-final"]);
                    $maiorMedia = max($maiorMedia, $boletimAluno["media-final"]);
                    if ($boletimAluno["situacao"] == "APROVADO" || $boletimAluno["situacao"] == "APROVADO POR NOTA") {
                        $qtdAprovados++;
                    } else if (
                        $boletimAluno["situacao"] == "REPROVADO"
                        || $boletimAluno["situacao"] == "REPROVADO POR FALTAS"
                        || $boletimAluno["situacao"] == "REPROVADO POR MÉDIA E POR FALTAS"
                        || $boletimAluno["situacao"] == "REPROVADO POR NOTA E FALTA"
                        || $boletimAluno["situacao"] == "REPROVADO POR NOTA"
                    ) {
                        $qtdReprovados++;
                    } else {
                        Log::info("Erro: situação do boletim não tratada: " . $boletimAluno["situacao"]);
                        throw new ApiUnknownValueException("Situção " . $boletimAluno["situacao"] . " não tratada.");
                    }

                    break;
                }
            }

            if (!$encontrouBoletim) {
                $qtdDesconhecidos++;
                $situacaoDesconhecida .= $aluno["id-participante"] . " ";
            }
        }

        $dados = array(
            "soma-medias" => $somaMedias,
            "soma-medias-unidade1" => $somaMediasUnidade1,
            "soma-medias-unidade2" => $somaMediasUnidade2,
            "soma-medias-unidade3" => $somaMediasUnidade3,
            "unidade1-com-nota" => $unidade1ComNota,
            "unidade2-com-nota" => $unidade2ComNota,
            "unidade3-com-nota" => $unidade3ComNota,
            "maior-media" => $maiorMedia,
            "menor-media" => $menorMedia,
            "quantidade-discentes" => $qtdAlunos,
            "quantidade-aprovados" => $qtdAprovados,
            "quantidade-reprovados" => $qtdReprovados,
            "situacao-desconhecida" => $situacaoDesconhecida,
            "tempo-total" => (microtime(true) - $tempoInicio),
            "quantidade-turmas" => $qtdTurmas,
            "ids-turma" => $idsTurma,
            "lotacoes-turma" => $lotacaoTurma,
            "docentes" => json_encode($docentes)
        );
        return $dados;
    }

    public function getDisciplineTurmas($codigo) 
    {
        try {
            if ($this->tokenData == null) {
                $this->getToken();
            }
    
            $url = "turma/v1/turmas?sigla-nivel=G&limit=100&order-desc=ano&codigo-componente=" . $codigo;
            $classes = $this->fetch($url, "GET");

            return $classes;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getClassTeacher($class_id) {
        try {
            if ($this->tokenData == null) {
                $this->getToken();
            }
    
            $url = "turma/v1/turmas/". $class_id . "/docentes";
            $teacher = $this->fetch($url, "GET");

            return $teacher;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getComponentesCurriculares($codigo) {
        try {
            if ($this->tokenData == null) {
                $this->getToken();
            }

            $disciplineComponenteId = $this->getDisciplineComponenteId($codigo);
    
            $url = "curso/v1/componentes-curriculares/".$disciplineComponenteId;
            $data = $this->fetch($url, "GET");

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getReferenciasBibliograficas($codigo) {
        try {
            if ($this->tokenData == null) {
                $this->getToken();
            }
    
            $disciplineComponenteId = $this->getDisciplineComponenteId($codigo);

            $url = "curso/v1/componentes-curriculares/".$disciplineComponenteId."/referencias-bibliograficas?limit=100";
            $data = $this->fetch($url, "GET");

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function getDisciplineComponenteId($codigo) {
        try {
            if ($this->tokenData == null) {
                $this->getToken();
            }
    
            $url = "curso/v1/componentes-curriculares?nivel=G&codigo=".$codigo;
            $data = $this->fetch($url, "GET");

            return $data[0]["id-componente"];
        } catch (Exception $e) {
            return $e;
        }
    }
}

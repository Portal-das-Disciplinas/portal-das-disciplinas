<?php

namespace App\Services\APISigaa;

/**
 * Classe responsável em fazer as requisições da API do Sigaa.
 */
class APISigaaService{

    private $baseUrlAuth = "https://autenticacao.info.ufrn.br/";
    //private $baseUrlAuth = "https://autenticacao.ufrn.br/";
    private $baseUrl = "https://api.info.ufrn.br/";
    //private $baseUrl = "https://api.ufrn.br/";
    private $clientId = "portal-diciplinas-imd-id-flcHo08eSp2vawLs";
    private $clientSecret = "segredo";
    private $apiKey = "4VYbeRFSLYT9CZRN5q1onPSaYx4ZUtpN";
    private $grantType = "client_credentials";
    private $token; 
    private $tokenType;
    private $expiresIn;
    private $scope;

    /**
     * Função auxiliar para fazer as requisições para a API
     * @param string $url Url que será concatenada com a url base para obter o recurso.
     * @method string $method Métodos da requisição.
     * @return array
     */
    private function fetch($url, $method){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseUrl . $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => array(
                "Authorization: ". $this->tokenType. " ". $this->token,
                "x-api-key: " . $this->apiKey
            )

        ));

        $response = curl_exec($curl);
        $errors = curl_error($curl);
        curl_close($curl);
        if($errors){
            dd("Contem erros: " . $errors);
        }
        else{
            return json_decode($response,true);
        }
    }

    /**
     * Obtém um token
     *
     */
    private function getToken(){

        $curl = curl_init();

        curl_setopt_array($curl,array(
            CURLOPT_URL => $this->baseUrlAuth."authz-server/oauth/token?client_id=". $this->clientId . "&client_secret=".$this->clientSecret."&grant_type=".$this->grantType,
            CURLOPT_CUSTOMREQUEST=>"POST",
            CURLOPT_RETURNTRANSFER =>true
        ));

        $response = curl_exec($curl);
        $errors = curl_error($curl);
        if($errors){
            dd("Contem errors . ". $errors);
        }
        
        else{
            $jsonData = json_decode($response, true);
            $this->token = $jsonData['access_token'];
            $this->tokenType = $jsonData['token_type'];
            $this->expiresIn  = $jsonData['expires_in'];
            $this->scope = $jsonData['scope'];
        }
        curl_close($curl);   
    }

    /**
     * Obtem todas as turmas de um determinado discente.
     * @param string idDiscente Identificador único do discente.
     * @return array
     */
    public function getTurmasDiscente($idDiscente){

        $this->getToken();
        $turma = $this->fetch("turma/v1/boletim?id-discente=" . $idDiscente,"GET");
        return $turma;
    }

    /**
     * Obtem as turmas consolidadas pelo código do componente
     * @param string $codigoComponente Código do componente
     * @return array
     */
    public function getTurmasPorComponente($codigoComponente){

        $this->getToken();
        $turmas = $this->fetch("turma/v1/turmas?codigo-componente=" . $codigoComponente . "&id-situacao-turma=3","GET");
        return $turmas;
    }

    public function getDisciplineData($codigoComponente, $ano){
        $this->getToken();
        $qtdAlunos = 0;
        $qtdAlunosComMedia = 0;
        $media = 0;
        $qtdAprovados = 0;
        $qtdReprovados = 0;
        $qtdTrancados = 0;

        $turmas = $this->fetch("turma/v1/turmas?codigo-componente=" . $codigoComponente . "&id-situacao-turma=3&ano=" . $ano,"GET");
        foreach($turmas as $turma){
            $alunosTurma = $this->fetch("turma/v1/participantes?id-turma=" . $turma['id-turma'] . "&id-tipo-participante=4", "GET");
            $qtdAlunos += count($alunosTurma);
            foreach($alunosTurma as $aluno){
                $discente = $this->fetch("discente/v1/discentes?cpf-cnpj=" . $aluno["cpf-cnpj"],"GET")[0];

                $boletinsAluno = $this->fetch("turma/v1/boletim?id-discente=" . $discente['id-discente'],"GET");
                $encontrouBoletim=false;
                foreach($boletinsAluno as $boletimAluno){
                    
                    if($boletimAluno["id-turma"] == $turma["id-turma"]){
                        $encontrouBoletim = true;
                        $qtdAlunosComMedia++;
                        $media+= $boletimAluno["media-final"];
                        if($boletimAluno["situacao"] == "APROVADO"){
                            $qtdAprovados++;
                        }
                        else if($boletimAluno["situacao"] == "REPROVADO"){
                            $qtdReprovados++;
                        }
                        break;
                    }
                }
                if(!$encontrouBoletim){
                    $qtdTrancados++;
                }
            }
        }

        $dados = null;

        if($qtdAlunos == 0){
            return null;

        }else{

            if($qtdAlunosComMedia == 0){
                $dados = array(
                    "mediaGeral" => 0,
                    "aprovadosPercentagem" => ($qtdAprovados/$qtdAlunos) * 100,
                    "reprovadosPercentagem" =>($qtdReprovados/$qtdAlunos) * 100,
                    "trancadosPercentagem" => ($qtdTrancados/$qtdAlunos) * 100
                );
            }
            else{
                $dados = array(
                    "mediaGeral" => $media/$qtdAlunosComMedia,
                    "aprovadosPercentagem" => ($qtdAprovados/$qtdAlunos) * 100,
                    "reprovadosPercentagem" =>($qtdReprovados/$qtdAlunos) * 100,
                    "trancadosPercentagem" => ($qtdTrancados/$qtdAlunos) * 100
                );
            }
        }
       return $dados;

    }

}

?>
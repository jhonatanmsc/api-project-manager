<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

class Atividade extends CI_Controller{
	function __construct(){
		parent::__construct();
		header('Content-Type: application/json');
	}

	public function all() {
		$activities = $this->doctrine->em->getRepository("Entity\Atividade")
						->createQueryBuilder('activity')				
						->getArrayResult();

		echo json_encode($activities);
	}
	
	public function projeto($id){
		$data = [];
		$atividades = $this->doctrine->em->getRepository("Entity\Atividade")
									 ->findBy(array("idProjeto"=>$id),array("dataCadastro"=>"asc"));	
		foreach($atividades as $ativadade){
			$data[] = [
				"id"=>$ativadade->getId(),
				"createdAt"=>$ativadade->getDataCadastro(),
				"descricao"=>$ativadade->getDescricao(),
			];
		}			
			 			
		echo json_encode($data);
    }

    public function get($id){
		$data = [];
		$atividade = $this->doctrine->em->find("Entity\Atividade",$id);
		$data[] = [
            "id"=>$atividade->getId(),
            "data"=>$atividade->getDataCadastro(),
			"descricao"=>$atividade->getDescricao(),
			"project"=>$atividade->getIdProjeto()->getDescricao()
		];			 	
		echo json_encode($data);
	}
	
	public function criar() {
		$activityJson = json_decode(file_get_contents('php://input'));
		$this->db->insert('atividade',["descricao" => $activityJson->activity->descr, "idProjeto"=> (int) $activityJson->activity->project]);
		echo json_encode(['created' => $activityJson->project->descr]);
	}

	public function editar($id) {
		$activityJson = json_decode(file_get_contents('php://input'));
		$this->db->update('activity', ['descricao'=> $activityJson->project->descr], ["id"=>$id]);
		echo json_encode(['updated' => 'activity ID: ' . $id]);
	}

	public function deletar($id) {
		$this->db->delete('atividade', array('id'=>$id));
		echo json_encode(['deleted' => 'activity ID: ' . $id]);
	}
    
}
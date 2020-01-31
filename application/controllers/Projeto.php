<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


class Projeto extends CI_Controller{
	function __construct(){
		parent::__construct();
		header('Content-Type: application/json');
	}

	public function all() {
		$data = [];
		$projects = $this->doctrine->em->getRepository("Entity\Projeto")->findAll();
		
		$atividadesRepo = $this->doctrine->em->getRepository("Entity\Atividade");

		foreach($projects as $project) {
			$activities = $atividadesRepo->findBy(array("idProjeto"=>$project->getId()));
			$data[] = [
				"id" => $project->getId(),
				"descricao" => $project->getDescricao(),
				"count" => count($activities)
			];
		}
		echo json_encode($data);
	}

    public function get($id){
		$data = [];
		$atividadesRepo = $this->doctrine->em->getRepository("Entity\Atividade");
		$project = $this->doctrine->em->find("Entity\Projeto",$id);
		$activities = $atividadesRepo->findBy(array("idProjeto"=>$project->getId()));
		$data[] = [
            "id"=>$project->getId(),
			"descricao"=>$project->getDescricao(),
			"count" => count($activities)
		];			 	
		echo json_encode($data);
    }
	
	public function criar() {
		$projectJson = json_decode(file_get_contents('php://input'));
		$this->db->insert('projeto',["descricao" => $projectJson->project->descr]);
		echo json_encode(['created' => $projectJson->project->descr]);
	}

	public function editar($id) {
		$projectJson = json_decode(file_get_contents('php://input'));
		$this->db->update('projeto', ['descricao'=> $projectJson->project->descr], ["id"=>$id]);
		echo json_encode(['updated' => 'project ID: ' . $id]);
	}

	public function deletar($id) {
		$this->db->delete('atividade', array('id'=>$id));
		echo json_encode(['deleted' => 'project ID: ' . $id]);
	}
}
<?php

/**
 * Service zur Erstellung von BikeParts und BikePartTypes.
 *
 * @author ju
 */
class BikePartService {

	/** BikePartRepository Instanz */
	private $repository;

	function __construct() {
		require_once 'classes/bikePartRepository.php';
		$this->repository = new BikePartRepository();
	}

	/**
	 * Erstellt Bike Parts. Die zugehoerige Ontologie
	 * der Typen wird on the fly mit erstellt.
	 * @param array $request $POST
	 * @return array mit BikeParts
	 */
	function mergeBikeParts($data) {
		require_once 'classes/bikePart.php';


		$bikeParts = array();

		foreach ($data['comptype'] as $key => $type) {
			$name = $data['compname'][$key];
			if (isset($type)
					&& isset($name)
					&& strlen(trim($type)) > 0
					&& strlen(trim($name)) > 0) {

				$bikePartType = new BikePartType($type);
				$this->repository->mergeBikePartType($bikePartType);

				$bikePart = new BikePart($name, $bikePartType);
				$bikeParts[] = $bikePart;
			}
		}
		foreach($bikeParts as $part) {
			$this->repository->mergeBikePart($part);
		}
		
		return $bikeParts;
	}

}
?>



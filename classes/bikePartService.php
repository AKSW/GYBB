<?php

/**
 * Description of bikePartService
 *
 * @author ju
 */
class BikePartService {

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
	function createBikeParts($request) {
		require_once 'classes/bikePart.php';

    // FIXME this variable is not used later?
		$bikeParts = array();

		foreach ($request['comptype'] as $key => $type) {
			$name = $request["compname"][$key];

			$bikePartType = new BikePartType($type);
			$this->repository->mergeBikePartType($bikePartType);

			$bikePart = new BikePart($name, $type);

      // FIXME this variable is not used later?
			$bikeParts[] = $bikePart;
		}
	}


}

?>



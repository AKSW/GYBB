<?php
require_once('classes/bikeImages.php');
require_once('views/json.view.php');

/**
 *
 **/
class BikeImagesController {

	function __construct() {
	}


	public function execute()  {

		if (isset($_GET['bikeID']) && !empty($_GET['bikeID']))  {
			$limit = (!empty($_GET['limit'])) ? (int) $_GET['limit'] : false;

			$bi = new BikeImages($_GET['bikeID'], $limit);
			$images = $bi->getImages();

			if (is_array($images) && !empty($images))  {
				return new JsonView($images);
			} else  {
        return new ErrorView();
			}

		} else {
      return new ErrorView();
		}

	}


}

?>


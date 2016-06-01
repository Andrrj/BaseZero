<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * OAuthController
 *
 * @author
 *
 * @version
 *
 */
class OAuthController extends AbstractActionController {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated OAuthController::indexAction() default action
		return new ViewModel ();
	}
}
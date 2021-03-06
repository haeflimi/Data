<?php
defined('C5_EXECUTE') or die('Access Denied.');

class DataDashboardBaseController extends DashboardBaseController {

	protected $message;
	protected $success;
	protected $navigation = array();

	public function on_start() {
		parent::on_start();
		$this->addHeaderItem(
			Loader::helper('html')->css('dashboard.css', 'data')
		);
		$this->set('dashboard', Loader::helper('concrete/dashboard'));
		$this->set('token', Loader::helper('validation/token'));
	}

	public function on_before_render() {
		parent::on_before_render();
		if (isset($_SESSION['flash_error'])) {
			$this->error->add($_SESSION['flash_error']);
		}

		if (isset($_SESSION['flash_message'])) {
			$this->message = $_SESSION['flash_message'];
		}

		if (isset($_SESSION['flash_success'])) {
			$this->success = $_SESSION['flash_success'];
		}

		unset($_SESSION['flash_success']);
		unset($_SESSION['flash_message']);
		unset($_SESSION['flash_error']);

		$this->set('error', $this->error);
		$this->set('message', $this->message);
		$this->set('success', $this->success);

		$navigation = array();
		foreach ($this->navigation as $name => $path) {
			$p = new Page;
			$p->cID = 0;
			$p->cvName = $name;
			$p->cPath = $path;
			$navigation[] = $p;
		}
		if ($navigation) {
			$this->set('navigation', $navigation);
		}
	}

	protected function flashSuccess($text) {
		$_SESSION['flash_success'] = $text;
	}

	protected function flashMessage($text) {
		$_SESSION['flash_message'] = $text;
	}

	protected function flashError($text) {
		$_SESSION['flash_error'] = $text;
	}

	public function render($view) {
		return parent::render($this->path($view));
	}

	protected function path($view = '') {
		if ($view === '') {
			return $this->getCollectionObject()->getCollectionPath();
		}
		return $this->getCollectionObject()->getCollectionPath() . '/' . $view;
	}

}

<?php


final class DiagramPresenter extends ModellingPresenter {


	public function createComponentSourceControl() {
		return $this->sourceControlFactory->create($this->project, $this->diagram);
	}

}

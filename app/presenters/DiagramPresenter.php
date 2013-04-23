<?php


final class DiagramPresenter extends ModellingPresenter {


	protected function createComponentSourceControl() {
		return $this->sourceControlFactory->create($this->project, $this->diagram);
	}

}

<?php


namespace App\Service;


class ValidationErrorService
{
	public function getErrorMessages(\Symfony\Component\Form\Form $form)
	{
		$errors = array();
		foreach ($form->getErrors() as $key => $error) {
			$errors[] = $error->getMessage();
		}
		foreach ($form->all() as $child) {
			if (!$child->isValid()) {
				$errors[$child->getName()] = $this->getErrorMessages($child);
			}
		}
		return $errors;
	}

}
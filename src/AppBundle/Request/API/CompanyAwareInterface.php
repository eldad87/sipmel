<?php

namespace AppBundle\Request\API;

use AppBundle\Entity\Company;

interface CompanyAwareInterface {
	public function setCompany(Company $company);
	public function getCompany();
}
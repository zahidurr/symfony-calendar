<?php

namespace App\Tests\Mock;

use App\DataPersister\AgreementDataPersister as Base;
use App\Entity\Agreement;

class AgreementDataPersister extends Base
{
    public function notifyWizardBuilderService(Agreement $data)
    {
    }
}

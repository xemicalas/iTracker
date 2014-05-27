<?php

namespace KTU\CountersBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KTUCountersBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}

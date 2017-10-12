<?php

namespace Minds\Interfaces;

interface BoostReviewInterface {
    function setBoost($boost);

    function accept();

    function reject($reason);

    function revoke();
}
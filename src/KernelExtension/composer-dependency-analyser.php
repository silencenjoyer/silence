<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return (new Configuration())
    ->addPathsToScan([__DIR__], false)
    ->addPathsToExclude(['vendor', __FILE__])
;

<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return (new Configuration())
    ->addPathsToScan([__DIR__], false)
    ->addPathsToExclude(['Tests', 'vendor', __FILE__])
;

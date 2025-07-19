<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return (new Configuration())
    ->addPathsToScan(['src'], false)
    ->addPathRegexToExclude('/Tests/')
;

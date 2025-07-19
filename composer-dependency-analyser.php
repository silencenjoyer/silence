<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return (new Configuration())
    ->addPathsToScan(['src'], false)
    ->addPathRegexesToExclude(['/Tests/', sprintf('/%s/', basename(__FILE__))])
;

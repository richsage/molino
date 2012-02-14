<?php

set_time_limit(0);

if (!is_dir($vendorDir = __DIR__.'/vendor')) {
    mkdir($vendorDir, 0777, true);
}

$revs = array(
    'doctrine-common' => isset($_SERVER['DOCTRINE_COMMON_REV']) ? $_SERVER['DOCTRINE_COMMON_REV'] : 'origin/master',
    'doctrine-dbal'   => isset($_SERVER['DOCTRINE_DBAL_REV']) ? $_SERVER['DOCTRINE_DBAL_REV'] : 'origin/master',
    'doctrine-orm'    => isset($_SERVER['DOCTRINE_ORM_REV']) ? $_SERVER['DOCTRINE_ORM_REV'] : 'origin/master',
);

$deps = array(
    array('symfony', 'git://github.com/symfony/symfony', 'origin/master'),
    array('pagerfanta', 'git://github.com/whiteoctober/Pagerfanta', 'origin/master'),
    array('twig', 'git://github.com/fabpot/Twig', 'origin/master'),
    array('mondator', 'git://github.com/mandango/mondator', 'origin/master'),
    array('mandango', 'git://github.com/mandango/mandango', 'origin/master'),
    array('doctrine-common', 'git://github.com/doctrine/common', $revs['doctrine-common']),
    array('doctrine-dbal', 'git://github.com/doctrine/dbal', $revs['doctrine-dbal']),
    array('doctrine-orm', 'git://github.com/doctrine/doctrine2', $revs['doctrine-orm']),
    array('doctrine-mongodb', 'git://github.com/doctrine/mongodb', 'origin/master'),
    array('doctrine-mongodb-odm', 'git://github.com/doctrine/mongodb-odm', 'origin/master'),
);

foreach ($deps as $dep) {
    if (3 === count($dep)) {
        list($name, $url, $rev) = $dep;
        $target = null;
    } else {
        list($name, $url, $rev, $target) = $dep;
    }

    if (null === $rev) {
        $rev = 'origin/master';
    }

    if (null !== $target) {
        $installDir = $vendorDir.'/'.$target;
    } else {
        $installDir = $vendorDir.'/'.$name;
    }

    $install = false;
    if (!is_dir($installDir)) {
        $install = true;
        echo "> Installing $name\n";

        system(sprintf('git clone -q %s %s', escapeshellarg($url), escapeshellarg($installDir)));
    }

    if (!$install) {
        echo "> Updating $name\n";
    }

    system(sprintf('cd %s && git fetch origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)));
}

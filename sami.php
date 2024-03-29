<?php

use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in($dir = __DIR__.'/lib');

$versions = GitVersionCollection::create($dir)
    ->addFromTags(['0.8.0', '0.9.0', '0.10.0', '0.11.0', 'v1.*'])
    ->add('main', 'main branch');

return new Sami($iterator, [
    'versions' => $versions,
    'title' => 'Stampie API',
    'build_dir' => __DIR__.'/api/%version%',
    'cache_dir' => __DIR__.'/cache/%version%',
    'remote_repository' => new GitHubRemoteRepository('Stampie/Stampie', dirname($dir)),
    'default_opened_level' => 2,
]);

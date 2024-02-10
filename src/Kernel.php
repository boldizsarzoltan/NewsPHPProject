<?php

namespace App;

require_once './vendor/autoload.php';

use App\Repositories\CommentRepository;
use App\Repositories\NewsRepository;
use App\Utils\EnvLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Kernel
{
    private ContainerBuilder $containerBuilder;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($this->containerBuilder, new FileLocator(__DIR__));
        $loader->load("../configurations/config.yaml");
        $this->init();
    }

    private function init()
    {
        $envLoader = $this->containerBuilder->get(EnvLoader::class);
        /** @var $envLoader EnvLoader */
        $envLoader->load(".env");
    }

    public function getCommentRepository(): CommentRepository
    {
        return $this->containerBuilder->get(CommentRepository::class, ContainerInterface::NULL_ON_INVALID_REFERENCE);
    }

    public function getNewsRepository(): NewsRepository
    {
        return $this->containerBuilder->get(NewsRepository::class, ContainerInterface::NULL_ON_INVALID_REFERENCE);
    }
}
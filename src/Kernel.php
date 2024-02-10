<?php

/**
 * The main source of everything
 */

namespace App;

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

    public function getCommentRepository(): CommentRepository
    {
        /** @var CommentRepository $commentRepository */
        $commentRepository = $this->containerBuilder->get(
            CommentRepository::class,
            ContainerInterface::NULL_ON_INVALID_REFERENCE
        );
        return $commentRepository;
    }

    public function getNewsRepository(): NewsRepository
    {
        /** @var NewsRepository $newsRepository */
        $newsRepository = $this->containerBuilder->get(
            NewsRepository::class,
            ContainerInterface::NULL_ON_INVALID_REFERENCE
        );
        return $newsRepository;
    }

    /**
     * @return string
     */
    protected function getEnvFile(): string
    {
        return ".env";
    }

    private function init(): void
    {
        $envLoader = $this->containerBuilder->get(EnvLoader::class);
        /**
         * @var EnvLoader $envLoader
         */
        $envLoader->load($this->getEnvFile());
    }
}

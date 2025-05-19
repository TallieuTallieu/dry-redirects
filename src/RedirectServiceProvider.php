<?php

namespace Tnt\Redirects;

use dry\admin\Module;
use dry\admin\Portal;
use dry\http\Request;
use dry\http\Response;
use Oak\Application;
use Oak\Contracts\Container\ContainerInterface;
use Oak\Contracts\Dispatcher\DispatcherInterface;
use Oak\Migration\MigrationManager;
use Oak\Migration\Migrator;
use Oak\ServiceProvider;
use Tnt\Redirects\Admin\RedirectLogManager;
use Tnt\Redirects\Admin\RedirectManager;
use Tnt\Redirects\Contracts\RedirectPortalInterface;
use Tnt\Redirects\Events\RouteWasHit;
use Tnt\Redirects\Model\Redirect;
use Tnt\Redirects\Model\RedirectLog;
use Tnt\Redirects\Repository\RedirectLogRepository;
use Tnt\Redirects\Revisions\CreateRedirectLogTable;
use Tnt\Redirects\Revisions\CreateRedirectTable;
use Tnt\Redirects\Revisions\UpdateRedirectTableAddUniqueSourcePath;

class RedirectServiceProvider extends ServiceProvider
{
    /**
     * @param Application $app
     * @return mixed|void
     */
    public function register(ContainerInterface $app)
    {
        if ($app->isRunningInConsole()) {

            $migrator = $app->getWith(Migrator::class, [
                'name' => 'redirects',
            ]);

            $migrator->setRevisions([
                CreateRedirectTable::class,
                CreateRedirectLogTable::class,
                UpdateRedirectTableAddUniqueSourcePath::class,
            ]);

            $app->get(MigrationManager::class)
                ->addMigrator($migrator);
        }

        $app->set(RedirectPortalInterface::class, function() {
            return $this->registerPortal();
        });
    }

    /**
     * @param Application $app
     * @return mixed|void
     */
    public function boot(ContainerInterface $app)
    {
        $dispatcher = $app->get(DispatcherInterface::class);

        $this->bootEventListeners($dispatcher);

        try {

            foreach (Redirect::getActiveRedirects() as $redirect) {
                $resolvedParameterRedirect = str_replace('{', '(?<', $redirect->source_path);
                $resolvedParameterRedirect = str_replace('}', '>[^/]+)', $resolvedParameterRedirect);

                if (substr($resolvedParameterRedirect, -1) !== '/') {
                    $resolvedParameterRedirect .= '/';
                }

                \dry\route\Router::register(null, null, [

                    $resolvedParameterRedirect => function(Request $request) use ($redirect, $dispatcher) {

                        foreach ($request->parameters->get_data() as $key => $value) {
                            $redirect->target_path = str_replace("{{$key}}", $value, $redirect->target_path);
                        }

                        $dispatcher->dispatch(RouteWasHit::class, new RouteWasHit($redirect));
                        Response::redirect($redirect->target_path, $redirect->status_code);
                    },
                ]);
            }

        } catch (\Exception $e) {}
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [RedirectPortalInterface::class];
    }

    /**
     * @return Portal
     */
    private function registerPortal(): Portal
    {
        $modules = [];

        $modules[] = new RedirectManager();
        $modules[] = new RedirectLogManager();

        return new Portal('redirects', 'Redirects', $modules, [
            'icon' => Module::ICON_ARCHIVE
        ]);
    }

    /**
     * @param DispatcherInterface $dispatcher
     */
    private function bootEventListeners(DispatcherInterface $dispatcher)
    {
        $dispatcher->addListener(RouteWasHit::class, function($routeWasHitEvent) {

            $redirect = $routeWasHitEvent->getRedirect();

            $redirectLog = new RedirectLog();
            $redirectLog->created = time();
            $redirectLog->updated = time();
            $redirectLog->target_path = $redirect->target_path;
            $redirectLog->source_path = $redirect->source_path;
            $redirectLog->status_code = $redirect->status_code;
            $redirectLog->redirect = $redirect;
            $redirectLog->save();

            if (RedirectLogRepository::count() > 20000) {
                RedirectLogRepository::deleteAllBut(20000);
            }
        });
    }
}

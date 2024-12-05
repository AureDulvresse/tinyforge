<?php

namespace Forge\Http\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Forge\Http\Controllers\ErrorController;

class Route
{
    /** @var array $routes Les routes enregistrées */
    private static array $routes = [];

    /**
     * Enregistre une route GET
     *
     * @param string $uri URI de la route
     * @param callable|array $callback Callback ou contrôleur à appeler
     * @param string|null $name Nom de la route
     * @param callable|null $middleware Middleware à appliquer
     */
    public static function get(string $uri, callable|array $callback, ?string $name = null, ?callable $middleware = null): void
    {
        self::addRoute('GET', $uri, $callback, $name, $middleware);
    }

    /**
     * Enregistre une route POST
     *
     * @param string $uri URI de la route
     * @param callable|array $callback Callback ou contrôleur à appeler
     * @param string|null $name Nom de la route
     * @param callable|null $middleware Middleware à appliquer
     */
    public static function post(string $uri, callable|array $callback, ?string $name = null, ?callable $middleware = null): void
    {
        self::addRoute('POST', $uri, $callback, $name, $middleware);
    }

    /**
     * Enregistre une route PUT
     *
     * @param string $uri URI de la route
     * @param callable|array $callback Callback ou contrôleur à appeler
     * @param string|null $name Nom de la route
     * @param callable|null $middleware Middleware à appliquer
     */
    public static function put(string $uri, callable|array $callback, ?string $name = null, ?callable $middleware = null): void
    {
        self::addRoute('PUT', $uri, $callback, $name, $middleware);
    }

    /**
     * Enregistre une route DELETE
     *
     * @param string $uri URI de la route
     * @param callable|array $callback Callback ou contrôleur à appeler
     * @param string|null $name Nom de la route
     * @param callable|null $middleware Middleware à appliquer
     */
    public static function delete(string $uri, callable|array $callback, ?string $name = null, ?callable $middleware = null): void
    {
        self::addRoute('DELETE', $uri, $callback, $name, $middleware);
    }

    /**
     * Ajoute une route générique
     *
     * @param string $method Méthode HTTP (GET, POST, etc.)
     * @param string $uri URI de la route
     * @param callable|array $callback Callback ou contrôleur à appeler
     * @param string|null $name Nom de la route
     * @param callable|null $middleware Middleware à appliquer
     */
    private static function addRoute(string $method, string $uri, callable|array $callback, ?string $name = null, ?callable $middleware = null): void
    {
        $uri = rtrim($uri, '/');
        self::$routes[$method][$uri] = [
            'callback' => $callback,
            'name' => $name,
            'middleware' => $middleware,
        ];
    }

    /**
     * Enregistre un groupe de routes avec un préfixe et des middlewares
     *
     * @param string $prefix Préfixe pour les URI
     * @param callable $callback Fonction contenant les routes du groupe
     * @param callable|null $middleware Middleware à appliquer au groupe
     */
    public static function group(string $prefix, callable $callback, ?callable $middleware = null): void
    {
        $prefix = rtrim($prefix, '/');
        // Appel de la fonction callback avec le préfixe et le middleware
        $callback($prefix, $middleware);
    }

    /**
     * Résout et exécute la route correspondant à la requête HTTP
     */
    public static function dispatch(): void
    {
        $request = Request::createFromGlobals();
        $uri = rtrim($request->getPathInfo(), '/');
        $method = $request->getMethod();

        // Vérification des routes définies pour la méthode HTTP
        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $route => $data) {
                $pattern = self::getPatternFromRoute($route);
                if (preg_match("#^$pattern$#", $uri, $matches)) {
                    // Exécution du middleware s'il est défini
                    if ($data['middleware'] && !$middlewareResponse = call_user_func($data['middleware'], $request)) {
                        $middlewareResponse->send();
                        return;
                    }

                    // Appel du callback (fonction ou contrôleur)
                    $response = self::resolveCallback($data['callback'], array_merge([$request], array_slice($matches, 1)));

                    // Envoi de la réponse
                    self::sendResponse($response);
                    return;
                }
            }
        }

        // Si aucune route ne correspond, afficher une erreur 404
        (new ErrorController())->show404($request);
    }

    /**
     * Génère le pattern correspondant à une route
     *
     * @param string $route URI de la route
     * @return string Pattern de la route
     */
    private static function getPatternFromRoute(string $route): string
    {
        return preg_replace('/{(\w+)}/', '([^/]+)', $route);
    }

    /**
     * Résout le callback de la route
     *
     * @param callable|array $callback Le callback de la route
     * @param array $params Les paramètres à passer au callback
     * @return Response|string La réponse de la route
     */
    private static function resolveCallback(callable|array $callback, array $params): Response|string
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_array($callback)) {
            [$controller, $method] = $callback;
            $controller = new $controller();
            return call_user_func_array([$controller, $method], $params);
        }

        throw new \InvalidArgumentException("Le callback n'est pas valide.");
    }

    /**
     * Envoie la réponse HTTP
     *
     * @param Response|string $response La réponse à envoyer
     */
    private static function sendResponse(Response|string $response): void
    {
        if ($response instanceof Response) {
            $response->send();
        } else {
            echo $response;
        }
    }
}

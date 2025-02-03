<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        // Vérifie si la requête API contient un token d'authentification (ex: Bearer token)
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $token = $request->headers->get('Authorization');

        // Vérifie si le token est valide (tu peux l'améliorer avec JWT, API Key, etc.)
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            throw new AuthenticationException('Token invalide.');
        }

        $apiToken = substr($token, 7); // Supprime "Bearer " du token

        return new SelfValidatingPassport(new UserBadge($apiToken, function ($apiToken) {
            // Ici, récupère l'utilisateur associé au token (ex: base de données)
            // Remplace cette logique selon ton mode d'authentification
            return new class implements UserInterface {
                public function getRoles(): array { return ['ROLE_USER']; }
                public function eraseCredentials() {}
                public function getUserIdentifier(): string { return 'api_user'; }
            };
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null; // Laisse la requête continuer
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response('Échec de l\'authentification', Response::HTTP_UNAUTHORIZED);
    }
}
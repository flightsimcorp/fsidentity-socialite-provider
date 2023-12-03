<?php

namespace FlightSimCorp\FSIdentity;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    public const IDENTIFIER = 'FSIDENTITY';

    /**
     * {@inheritdoc}
     */
    protected $usesPKCE = true;

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested that are mandatory.
     *
     * @var array
     */
    protected $requiredScopes = ['openid'];

    /**
     * {@inheritDoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://sso.fsidentity.com/oauth2/auth', $state);
    }

    /**
     * {@inheritDoc}
     */
    protected function getTokenUrl()
    {
        return 'https://sso.fsidentity.com/oauth2/token';
    }

    /**
     * {@inheritDoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://sso.fsidentity.com/userinfo',
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer '.$token,
                ],
            ]
        );

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * {@inheritDoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'    => Arr::get($user, 'sub'),
            'name'  => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
            'preferred_username' => Arr::get($user, 'preferred_username'),
            'avatar_url' => Arr::get($user, 'picture'),
            'locale' => Arr::get($user, 'locale'),
            'timezone' => Arr::get($user, 'zoneinfo'),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getScopes()
    {
        return array_unique(array_merge(parent::getScopes(), $this->getRequiredScopes()));
    }

    /**
     * {@inheritDoc}
     */
    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);

        if ($requiredScopes = $this->getRequiredScopes()) {
            $fields['required_scopes'] = $this->formatScopes($requiredScopes, $this->scopeSeparator);
        }

        return $fields;
    }

    /**
     * Merge the required scopes of the requested access.
     *
     * @param  array|string  $scopes
     * @return $this
     */
    public function requiredScopes($scopes)
    {
        $this->requiredScopes = array_unique(array_merge($this->requiredScopes, (array) $scopes));

        return $this;
    }

    /**
     * Set the required scopes of the requested access.
     *
     * @param  array|string  $scopes
     * @return $this
     */
    public function setRequiredScopes($scopes)
    {
        $this->requiredScopes = array_unique((array) $scopes);

        return $this;
    }

    /**
     * Get the current required scopes.
     *
     * @return array
     */
    public function getRequiredScopes()
    {
        return $this->requiredScopes;
    }
}

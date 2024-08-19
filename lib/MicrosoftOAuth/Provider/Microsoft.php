<?php namespace Stevenmaguire\OAuth2\Client\Provider;

use Arffornia\MinecraftOauth\Exceptions\GameOwnershipCheckException;
use Arffornia\MinecraftOauth\Exceptions\ResponseValidationException;
use Arffornia\MinecraftOauth\Exceptions\XtxsTokenRetrievalException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Uri;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

class Microsoft extends AbstractProvider
{
    /**
     * Default scopes
     *
     * @var array
     */
    public array $defaultScopes = ['XboxLive.signin offline_access'];

    /**
     * Base url for authorization.
     *
     * @var string
     */
    protected string $urlAuthorize = 'https://login.microsoftonline.com/consumers/oauth2/v2.0/authorize';

    /**
     * Base url for access token.
     *
     * @var string
     */
    protected string $urlAccessToken = 'https://login.microsoftonline.com/consumers/oauth2/v2.0/token';

    /**
     * Base url for resource owner.
     *
     * @var string
     */
    protected string $urlResourceOwnerDetails = 'https://graph.windows.net/v1.0/me';

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->urlAuthorize;
    }

    /**
     * Get access token url to retrieve token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->urlAccessToken;
    }

    /**
     * Get default scopes
     *
     * @return array
     */
    protected function getDefaultScopes(): array
    {
        return $this->defaultScopes;
    }

    /**
     * Check a provider response for errors.
     *
     * @param ResponseInterface $response
     * @param $data
     * @return void
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (isset($data['error'])) {
            throw new IdentityProviderException(
                ($data['error']['message'] ?? $response->getReasonPhrase()),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return MicrosoftResourceOwner
     * @throws GameOwnershipCheckException
     * @throws ResponseValidationException
     * @throws XtxsTokenRetrievalException
     * @throws GuzzleException
     */
    protected function createResourceOwner(array $response, AccessToken $token): MicrosoftResourceOwner
    {
        return new MicrosoftResourceOwner($token, $response);
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return new Uri($this->urlResourceOwnerDetails);
    }

    protected function fetchResourceOwnerDetails(AccessToken $token)
    {
        $uri = new Uri($this->urlResourceOwnerDetails);

        $request = $this->getAuthenticatedRequest(
            self::METHOD_GET,
            $uri,
            $token,
            ['headers' => [
                'Authorization' => sprintf("Bearer %s", $token),
                'Content-Type' => "application/json"
            ]]
        );

        $response = $this->getParsedResponse($request);

        if (false === is_array($response)) {
            throw new UnexpectedValueException(
                'Invalid response received from Authorization Server. Expected JSON.'
            );
        }

        return $response;
    }
}

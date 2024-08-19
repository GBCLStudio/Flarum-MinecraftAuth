<?php namespace Stevenmaguire\OAuth2\Client\Provider;

use Arffornia\MinecraftOauth\Exceptions\GameOwnershipCheckException;
use Arffornia\MinecraftOauth\MinecraftOauth;
use Arffornia\MinecraftOauth\MinecraftProfile;
use GuzzleHttp\Exception\GuzzleException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Flarum\Locale\Translator;

class MicrosoftResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     *
     * @var array
     */
    protected array $response;
    protected MinecraftProfile $profile;

    /**
     * Creates new resource owner.
     *
     * @param AccessToken $token
     * @param array $response
     * @throws \Exception|GuzzleException
     */
    public function __construct(AccessToken $token, array $response = array())
    {
        /** @var Translator $translator */
        $translator = resolve(Translator::class);
        $this->response = $response;
        try {
            $this->profile = (new MinecraftOauth())->fetchProfile(
                $token->getToken()
            );
        } catch (GameOwnershipCheckException $e) {
            throw new \Exception($translator->trans('gbcl-oauth-minecraft.forum.error.profile_does_not_exist'));
        }
    }

    /**
     * Get user id
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->profile->uuid() ?: null;
    }

    /**
     * Get user email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->response['emails']['preferred'] ?: null;
    }

    /**
     * Get user firstname
     *
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->response['first_name'] ?: null;
    }

    /**
     * Get user lastname
     *
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->response['last_name'] ?: null;
    }

    /**
     * Get username
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->profile->username() ?: null;
    }

    /**
     * Get user urls
     *
     * @return string|null
     */
    public function getUrls(): ?string
    {
        return isset($this->response['link']) ? $this->response['link'].'/cid-'.$this->getId() : null;
    }

    /**
     * Return all the owner details available as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->response;
    }
}

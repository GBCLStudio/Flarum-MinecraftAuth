<?php

namespace Arffornia\MinecraftOauth;

use Arffornia\MinecraftOauth\Exceptions\GameOwnershipCheckException;
use Arffornia\MinecraftOauth\Exceptions\ResponseValidationException;
use Arffornia\MinecraftOauth\Exceptions\XtxsTokenRetrievalException;
use Arffornia\MinecraftOauth\DataRetrievers\GameOwnershipStatusRetriever;
use Arffornia\MinecraftOauth\DataRetrievers\MinecraftAccessTokenRetriever;
use Arffornia\MinecraftOauth\DataRetrievers\MinecraftProfileDataRetriever;
use Arffornia\MinecraftOauth\DataRetrievers\XblTokenUserHashRetriever;
use Arffornia\MinecraftOauth\DataRetrievers\XtxsTokenRetriever;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MinecraftOauth
{
    private Client $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'http_errors' => false,
        ]);
    }

    /**
     * @throws ResponseValidationException
     * @throws GameOwnershipCheckException
     * @throws GuzzleException
     * @throws XtxsTokenRetrievalException
     */
    public function fetchProfile(
        string $token,
    ): MinecraftProfile {
        $accessToken = $token;


        [$xblToken, $userHash] = (new XblTokenUserHashRetriever($this->client))
            ->retrieve($accessToken);


        $xtxsToken = (new XtxsTokenRetriever($this->client))
            ->retrieve($xblToken);

        $minecraftAccessToken = (new MinecraftAccessTokenRetriever($this->client))
            ->retrieve($userHash, $xtxsToken);

        (new GameOwnershipStatusRetriever($this->client))
            ->check($minecraftAccessToken);

        $minecraftProfileData = (new MinecraftProfileDataRetriever($this->client))
            ->retrieve($minecraftAccessToken);

        return new MinecraftProfile($minecraftProfileData);
    }
}

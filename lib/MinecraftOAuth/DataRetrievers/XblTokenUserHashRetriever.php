<?php

namespace Arffornia\MinecraftOauth\DataRetrievers;

use Arffornia\MinecraftOauth\Exceptions\ResponseValidationException;
use GuzzleHttp\Exception\GuzzleException;

class XblTokenUserHashRetriever extends DataRetriever
{
    public function expectedResponseKeys(): array
    {
        return [
            'IssueInstant',
            'NotAfter',
            'Token',
            'DisplayClaims',
            'DisplayClaims.xui',
            'DisplayClaims.xui.0.uhs',
        ];
    }

    /**
     * @throws GuzzleException
     * @throws ResponseValidationException
     */
    public function retrieve(
        string $accessToken
    ): array {
        $response = $this->client->post('https://user.auth.xboxlive.com/user/authenticate', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Xbl-Contract-Version' => '2',
                'User-Agent' => 'XboxReplay; XboxLiveAuth/4.0'
            ],
            'json' => [
                'Properties' => [
                    'AuthMethod' => 'RPS',
                    'SiteName' => 'user.auth.xboxlive.com',
                    'RpsTicket' => 'd='.$accessToken,
                ],
                'RelyingParty' => 'http://auth.xboxlive.com',
                'TokenType' => 'JWT',
            ],
        ]);

        $responseJson = $this->parseJson($response);
        
        $this->validateResponseJson($responseJson);

        return [$responseJson['Token'], $responseJson['DisplayClaims']['xui'][0]['uhs']];
    }
}

<?php

namespace Arffornia\MinecraftOauth\DataRetrievers;

use Arffornia\MinecraftOauth\Exceptions\ResponseValidationException;
use Arffornia\MinecraftOauth\Exceptions\XtxsTokenRetrievalException;
use GuzzleHttp\Exception\GuzzleException;

class XtxsTokenRetriever extends DataRetriever
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
     * @throws XtxsTokenRetrievalException
     */
    public function retrieve(
        string $xbl_token
    ): string {
        $response = $this->client->post('https://xsts.auth.xboxlive.com/xsts/authorize', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'XboxReplay; XboxLiveAuth/4.0',
                'x-xbl-contract-version' => '2',
            ],
            'json' => [
                'Properties' => [
                    'SandboxId' => 'RETAIL',
                    'UserTokens' => [
                        $xbl_token,
                    ],
                ],
                'RelyingParty' => 'rp://api.minecraftservices.com/',
                'TokenType' => 'JWT',
            ],
        ]);

        $responseJson = $this->parseJson($response);

        if ($response->getStatusCode() === 401 && array_key_exists('XErr', $responseJson)) {
            throw XtxsTokenRetrievalException::withXErr($responseJson['XErr']);
        }

        $this->validateResponseJson($responseJson);

        return $responseJson['Token'];
    }
}

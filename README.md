# Log In with Minecraft(Microsoft)

![extiverse](https://extiverse.com/extension/gbcl/minecraft-oauth/open-graph-image)

> Minecraft Login Intergration for Flarum, a extend provider based on FoF/OAuth.

## Install

```sh
composer require gbcl/minecraft-oauth:"*"
php flarum cache:clear
```

## Update

```sh
composer update gbcl/minecraft-oauth
php flarum cache:clear
```

## Remove

```sh
composer remove gbcl/minecraft-oauth
php flarum cache:clear
```

## How to use

1. Create a new SPA application in Azure Active Directory (Azure Entra ID) and restrict users to "Microsoft personal accounts only", then copy the Application (Client) ID as the `Client ID`
2. Configure the callback path, find the Client Password column in Management -> Certificate and Password/Secret, create a new client secret and copy the content in the value, saving as the `Client Secret`
3. According to [this document](https://help.minecraft.net/hc/en-us/articles/16254801392141), you need to apply for Minecraft API access from Mojang
4. Configure and enable this provider in FoF/OAuth

## Feature

- Full OAuth2 Support.

## Note

Any trademarks, service marks, collective marks, design rights, personality rights or similar rights mentioned, used or cited in this extension are owned by their respective owners. Unless otherwise stated, GBCLStudio and the extension users are not endorsed or affiliated by the above right owners.

Support my work at [afdian](http://afdian.com/a/GBCLStudio)

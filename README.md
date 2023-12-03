# FSIdentity Socialite Provider for Laravel

```bash
composer require flightsimcorp/fsidentity-socialite-provider
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Create an application

Create an application at https://fsidentity.com/clients/new

Its important that you verify the following OAuth Settings are configured for your FSIdentity application:

- Token Endpoint Authentication Method: `Client Secret Basic Auth`
- Grant Types: `Authorization Code`

### Add configuration to `config/services.php`

```php
'fsidentity' => [
  'client_id' => env('FSIDENTITY_CLIENT_ID'),
  'client_secret' => env('FSIDENTITY_CLIENT_SECRET'),
  'redirect' => env('FSIDENTITY_REDIRECT_URI'),
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \SocialiteProviders\FSIdentity\FSIdentityExtendSocialite::class.'@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('fsidentity')->redirect();
```

To add scopes to your Authentication you can use the below:

```php
return Socialite::driver('fsidentity')->scopes(['name', 'email'])->redirect();
```

To add required scopes (those the user cannot opt out from) to your Authentication you can use the below:

```php
return Socialite::driver('fsidentity')->requiredScopes(['name', 'email'])->redirect();
```

### Returned User fields

- ``id``
- ``name``
- ``email``
- ``preferred_username``
- ``avatar_url``
- ``locale``
- ``timezone``

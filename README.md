# php-steam-auth
Sign in users with Steam (stateless OpenID)  
Probably needs to be rethinked

# Requirements

- PHP >=5.5
- Guzzle 6

# Composer

``` composer require kbkk/php-steam-auth ```

# Usage example

```php
$yourAppUrl = 'http://github.com/';
$returnTo = 'http://github.com/steam/verify';
$apiKey = 'Your Steam API Key';

$steamAuth = new \SteamAuth\SteamOpenId([
    'realm' => $yourAppUrl,
    'return_to' => $returnTo,
]);

$steamApi = new \SteamAuth\SteamApi($apiKey);

// redirect the user to steam sign in page
Redirect::url($steamAuth->getRedirectUrl());


// and verify the data when the user gets back to us
$steamid = $steamAuth->verifyAssertion($_GET); //false or steam id 64 as string

// fetch user profile
if($steamid)
    $user = $steamApi->getProfile($steamid);

// example output
array (size=17)
  'steamid' => string '76561197960435530' (length=17)
  'communityvisibilitystate' => int 3
  'profilestate' => int 1
  'personaname' => string 'Robin' (length=5)
  'lastlogoff' => int 1464428303
  'profileurl' => string 'http://steamcommunity.com/id/robinwalker/' (length=41)
  'avatar' => string 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/f1/f1dd60a188883caf82d0cbfccfe6aba0af1732d4.jpg' (length=116)
  'avatarmedium' => string 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/f1/f1dd60a188883caf82d0cbfccfe6aba0af1732d4_medium.jpg' (length=123)
  'avatarfull' => string 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/f1/f1dd60a188883caf82d0cbfccfe6aba0af1732d4_full.jpg' (length=121)
  'personastate' => int 0
  'realname' => string 'Robin Walker' (length=12)
  'primaryclanid' => string '103582791429521412' (length=18)
  'timecreated' => int 1063407589
  'personastateflags' => int 0
  'loccountrycode' => string 'US' (length=2)
  'locstatecode' => string 'WA' (length=2)
  'loccityid' => int 3961

```

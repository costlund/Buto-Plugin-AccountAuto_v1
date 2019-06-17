# Buto-Plugin-AccountAuto_v1

Create an sign in link. Set up a landing page where the sign in should be done.

## Settings

```
plugin:
  account:
    auto_v1:
      enabled: true
      settings:
        mysql: 'yml:/../buto_data/_folder_/_folder_/mysql.yml'
        url: '/d/account_auto_signin'
```

## Widget

On page /d/account_auto_signin.

```
type: widget
data:
  plugin: account/auto_v1
  method: signin
```

## Usage

From a plugin generate links via account id.

```
wfPlugin::includeonce('account/auto_v1');
$account_auto = new PluginAccountAuto_v1();
wfHelp::yml_dump($account_auto->create_one('12345252345d01fd10a3cda289662904'));
```

```
account_id: 12345252345d01fd10a3cda289662904
id: 2141901345d037f47821b4595568767
url: 'http://localhost/d/account_auto_signin/key/2141901345d037f47821b4595568767'
end_date: '2019-06-15 13:04:39'
```

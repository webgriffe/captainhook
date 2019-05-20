# Webgriffe CaptainHook

A collection of useful actions to be used with [CaptainHook](https://github.com/CaptainHookPhp/captainhook).

[![Build Status](https://travis-ci.org/webgriffe/captainhook.svg?branch=master)](https://travis-ci.org/webgriffe/captainhook)

## Install

Given that you already have CaptainHook in your project you can simply use [Composer](https://getcomposer.org/) to have these `webgriffe/captainhook` actions available:

```bash
composer require --dev webgriffe/captainhook
```

## Actions

Here follows the list of implemented actions.

### 1. Prevent forced push on protected branches

For example, you can prevent forced push on `master` branch with the following CaptainHook's config:

```json
    // captainhook.json
    // ...
    "pre-push": {
        "enabled": true,
        "actions": [
            {
                "action": "\\Webgriffe\\CaptainHook\\PreventPushForce",
                "options": {"protected-branches": ["master"]}
            }
        ]
    }
    // ...
```

You can use the `protected-branches` option to add other branches to the protected branches list.

## License

This library is under the MIT license. See the complete license in the LICENSE file.

## Credits

Developed by [Webgriffe®](http://www.webgriffe.com/).

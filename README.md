# Webgriffe CaptainHook

A collection of useful conditions and actions to be used with [CaptainHook](https://github.com/CaptainHookPhp/captainhook).

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

### 2. Prevent commit if the diff is included in the message

```json
    // captainhook.json
    // ...
    "commit-msg": {
        "enabled": true,
        "actions": [
            {
                "action": "\\Webgriffe\\CaptainHook\\PreventCommitMessageWithDiff"
            }
        ]
    },
    // ...
```

### 3. Prevent commit if some files have the same name but different case

```json
    // captainhook.json
    // ...
    "commit-msg": {
        "enabled": true,
        "actions": [
            {
                "action": "\\Webgriffe\\CaptainHook\\PreventCommitCaseSensitiveSameFilename"
            }
        ]
    },
    // ...
```

## License

This library is under the MIT license. See the complete license in the LICENSE file.

## Credits

Developed by [Webgriffe®](http://www.webgriffe.com/).

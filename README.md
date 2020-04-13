Asynchronous PHP Tempo SDK
==========================

Note: Until 1.0.0 version SDK is unstable and can be rewritten completely.

## Install

```bash
composer require spacetab-io/jira-tempo-sdk
```

## Usage example

### Simple methods which returns a promise

```php
use Amp\Loop;
use Spacetab\TempoSDK\Exception\SdkErrorException;
use Spacetab\TempoSDK\TempoSDK;

Loop::run(function () {
    $jira = TempoSDK::new('https://jira.server.com', 'username', 'TokenString');

    try {
        $result = yield $jira->worklogs()->find(
            new DateTime('01.03.2020'),
            new DateTime('31.03.2020'),
        );
        dump($result);
    } catch (SdkErrorException $e) {
        dump($e->getMessage());
    }
});
```

## Supported methods

API Docs: https://www.tempo.io/server-api-documentation/timesheets#operation/searchWorklogs

* Get one worklog
* Search worklogs

## License

The MIT License

Copyright © 2020 spacetab.io, Inc. https://spacetab.io

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
